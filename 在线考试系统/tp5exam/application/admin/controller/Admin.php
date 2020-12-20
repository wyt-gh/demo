<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
/**
 * 
 */
class Admin extends Common
{
	public function index() {
		//查询出em_admin表的数据,并链接admin_role的role_id
		$list = model('Admin')->getAllData(1);
		$page = $list->render();
		$this->assign('page', $page);
		// dump($page);die;

		$this->assign('list', $list);
		return $this->fetch();
	}

	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		$ids = implode(',', $ids);
		//删除,说明:软删除(不是真正删除数据,而是改变status的值为0)
		$result = Db::name('admin')->where("id in ($ids)")->setField('status',0);
		if ($result) {
			$this->success("删除成功");
		} else {
			$this->error("删除失败");
		}
	}
	//off 状态更改为停用
	public function off() 
	{
		//获取id
		$id = input("post.id");
		$result = Db::name('admin')->where("id",$id)->setField("on_off", 0);
		if ($result) {
			return json(array('code' => 1));
		} else {
			return json(array('code' => 0, 'msg' => '关闭失败'));
		}
	}

	//on 状态更改为启用
	public function on() 
	{
		//获取id
		$id = input("post.id");
		$result = Db::name('admin')->where("id",$id)->setField("on_off", 1);
		if ($result) {
			return json(array('code' => 1));
		} else {
			return json(array('code' => 0, 'msg' => '开启失败'));
		}
	}

	//添加管理员
	public function add()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			if (!$data) {
				return json(array('code' => 0, 'msg' => '参数错误'));
			}

			$insertId = model('Admin')->addAdmin($data);
			if (!$insertId) {
				return json(array('code' => 0, 'msg' => '添加失败'));
			}
			//把新增用户id和role_id 添加进admin_role
			$role_id = implode(',', $data['role_id']);
			$data2 = [
				'admin_id' => $insertId,
				'role_id'	=> $role_id
			];
			$result = Db::name('admin_role')->insert($data2);

			if ($result) {
				return json(array('code' => 1, 'msg' => '添加成功'));
			} else {
				return json(array('code' => 0, 'msg' => '添加失败'));
			}
			
		} else {
			$role_list = Db::name('role')->where('status',1)->field('id,role_name')->select();
			$this->assign('role_list',$role_list);
			return $this->fetch();
		}
	}

	//编辑功能
	public function edit() 
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			// dump($data);die;
			//1.修改修改 admin表里的数据
			$up_admin = Db::name('admin')->where('id',$data['id'])->update([
				'username'	=>	$data['username'],
				'password'	=>	$data['password'],
			]);
			//2.1将$data['role_id']转换为字符串格式
			$role_id = implode(',', $data['role_id']);
			// dump($role_id);die;
			//2.2修改admin_role表里的数据
			$up_admin_role = Db::name('admin_role')->where('admin_id',$data['id'])->update([
				'role_id'	=>	$role_id
			]);
			//判断验证
			if ($up_admin && $up_admin_role) {
				return json(array('code' => 1, 'msg' => '编辑成功'));
			} else if($up_admin or $up_admin_role){
				return json(array('code' => 1, 'msg' => '编辑成功'));
			}else{
				return json(array('code' => 0, 'msg' => '编辑失败'));
			}
			
		} else {
			//获取当前用户的id
			$id = input('id');
			//从admin 和 admin_role表中拿到数据
			$data = model('Admin')->getAdmin($id);
			// dump($data);die;
			$role_list = Db::name('role')->where('status',1)->field('id,role_name')->select();
			$this->assign('userinfo',$data);
			$this->assign('role_list',$role_list);
			return $this->fetch();
		}
	}
}