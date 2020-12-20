<?php
namespace app\admin\controller;
use think\Db;

class Role extends Common {
	public function index() 
	{
		$role_list = model('Role')->getRole(1);
		// dump($role_list);die;
		$this->assign('role_list',$role_list);
		return $this->fetch();
	}

	//管理员删除
	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		$ids = implode(',', $ids);
		//删除,说明:软删除(不是真正删除数据,而是改变status的值为0)
		$result = Db::name('role')->where("id in ($ids)")->setField('status',0);
		if ($result) {
			$this->success("删除成功");
		} else {
			$this->error("删除失败");
		}
	}

	//编辑管理员
	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			// dump($data);die;
			$up_role = db('role')->where('id',$data['id'])->update([
				'role_name'	=>	$data['role_name'],
				'content'	=>	$data['content']
			]);
			$data['rule_id'] = implode(',', $data['rule_id']);
			$up_role_rule = db('role_rule')->where('role_id',$data['id'])->update([
				'rule_id'	=>	$data['rule_id']
			]);

			if ($up_role || $up_role_rule) {
				return json(array('code' => 1, 'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0, 'msg' => '编辑失败'));
			}
		} else {
			$id = input('id');
			$info = model('Role')->getRoleInfo($id);
			$list = model('Role')->getruletable();
			// dump($list);die;
			$this->assign('list',$list);
			$this->assign('info',$info);
			return $this->fetch();
		}
	}
	
	//添加管理员
	public function add()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			$data['rule_id'] = implode(',', $data['rule_id']);

			$in_role = Db::name('role')->insertGetId([
				'role_name'	=>	$data['role_name'],
				'content'	=>	$data['content']
			]);
			$in_role_rule = Db::name('role_rule')->insert([
				'role_id'	=>	$in_role,
				'rule_id'	=>	$data['rule_id']
			]);

			if ($in_role && $in_role_rule) {
				return json(array('code' => 1, 'msg' => '添加成功'));
			} else {
				return json(array('code' => 0, 'msg' => '添加失败'));
			}
			
		} else {
			$list = model('Role')->getruletable();
			// dump($list);die;
			$this->assign('list',$list);
			return $this->fetch();
		}
	}


}