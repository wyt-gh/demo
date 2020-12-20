<?php
namespace app\admin\behavior;
use think\Cache;
use think\Cookie;
use think\Db;
use think\Controller;

class Rule extends Controller
{
	//用于保存用户信息 角色id 权限信息
	public $user = array();
	//是否要做权限验证
	public $is_check_rule = true;
	public function run()
	{
		//2.1后台菜单显示问题
		$options = [
			//缓存类型为File
			'type' => 'File',
			//缓存有效期为永久有效
			'expire' => 0,
			//缓存前缀
			'prefix' => 'think_',
		];
		Cache::connect($options);
		//用户信息
		$admin = Cookie::get('admin');
		//用户id
		$id = Cookie::get('id');
		//读取当前用户对应的文件信息
		$this->user = Cache::get('user_'.$id);
		if (!$this->user) {
			$this->user = $admin;
			//保存角色id
			$role_info = Db::name('admin_role')->field('role_id')->where('admin_id',$id)->find();
			$this->user['role_id'] = $role_info['role_id'];
			//保存权限
			if ($this->user['role_id'] == 1) {
				//超级管理员,用有所有权限,不做权限验证
				$this->is_check_rule = false;
				$rule_list = Db::name('rule')->select();
			} else {
				//普通管理员 或教师 需要做权限验证
				//取出权限ID
				//角色id
				$role_id = $this->user['role_id'];
				$rules = Db::name('role_rule')->field('rule_id')->where("role_id in ($role_id)")->select();
				//根据这权限id $rules, 匹配权限表em_rule
				foreach ($rules as $key => $value) {
					$rules_ids[] = $value['rule_id'];
				}
				//将一维数组转换字符串格式方便使用in语法
				$rules_ids = implode(',', $rules_ids);
				$rule_list = Db::name('rule')->where("id in ($rules_ids)")->select();
			}

			//保存信息
			foreach ($rule_list as $key => $value) {
				$this->user['rules'][] = strtolower($value['module_name'] . '/' . $value['controller_name'] . '/' . $value['action_name']);
				//考虑到导航信息的显示
				if ($value['is_show'] == 1) {
					$this->user['menus'][] = $value;
				} 
			}
			Cache::set('user_'.$id,$this->user);

		}
		//2.2真正解决权限问题
		//判断角色是否为1,超级管理员,超级管理员不进行验证
		if ($this->user['role_id'] == 1) {
			$this->is_check_rule = false;
		}
		if ($this->is_check_rule) {
			$this->user['rules'][] = 'admin/index/index';
			$this->user['rules'][] = 'admin/index/welcome';
			//获取到用户访问地址(模块名/控制器/方法名)
			$action = strtolower(request()->module() . '/' .request()->controller() . '/' .request()->action());
			
			// dump($this->user);die;
			//用户请求的地址与权限进行比较
			if (!in_array($action, $this->user['rules'])) {
				if (request()->isAjax()) {
					return json(array('code' => 0, 'msg' => '没有权限'));
				} else {
					return $this->error('没有权限');
					
				}
			}

		}

	}
}