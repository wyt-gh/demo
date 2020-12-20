<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
class Rule extends Common
{
	//权限列表页面
	public function index() 
	{
		//index页面表格
		$rule_list = model('Rule')->getRule();
		//上级权限菜单
		$parent_rule = model('rule')->getTopRule();
		// dump($parent_rule);die;
		$page = $rule_list->render();
		$this->assign('page', $page);
		$this->assign('rule_list',$rule_list);
		$this->assign('parent_rule',$parent_rule);
		// dump($parent_rule);die;
		return $this->fetch();
	}

	//权限删除
	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		// dump($ids);die;
		$ids = implode(',', $ids);
		// dump($ids);die;
		//删除,说明:软删除(不是真正删除数据,而是改变status的值为0)
		$result = Db::name('rule')->where("id in ($ids)")->setField('is_show',2);
		if ($result) {
			$this->success("删除成功");
		} else {
			$this->error("删除失败");
		}
	}

	//权限添加
	public function add() 
	{
		$data = input('post.data/a');
		$data['add_time'] = date("Y-m-d H:i:s");
		// dump($data);die;
		//2.插入数据库
		$inset_rule = Db::name('rule')->insert($data);

		if ($inset_rule) {
			return json(array('code' => 1,'msg' => '添加成功'));
		} else {
			return json(array('code' => 0,'msg' => '添加失败'));
		}
	}

	//权限编辑页面
	public function edit() 
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			// dump($data);die;
			//将$data里的parent_id(其实是parent_name) 改成 parent_id
			$p_id = Db::name('rule')->field('id')->where('rule_name',$data['parent_id'])->find();
			$data['parent_id'] = $p_id['id'];
			
			//修改rule表
			$result = model('Rule')->upRule($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0,'msg' => '编辑失败,未做任何修改'));
			}
		} else {
			$id = input('id');
			$user_rule_info = Db::name('rule')->where('id',$id)->find();
			// dump($user_rule_info);die;

			//将$user_rule_info里的parent_id 的值 改成parent_name
			$parent_name = Db::name('rule')->field('rule_name')->where('id',$user_rule_info['parent_id'])->find();
			$user_rule_info['parent_id'] = $parent_name['rule_name'];
			//上级权限列表
			$parent_rule = Db::name('rule')->field('id,rule_name')->where('is_show',1)->select();

			$this->assign('user',$user_rule_info);

			$this->assign('parent_rule',$parent_rule);
			return $this->fetch();
		}
	}

}