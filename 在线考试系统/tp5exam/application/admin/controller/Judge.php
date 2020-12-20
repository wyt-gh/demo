<?php
namespace app\admin\controller;
use think\Db;

class Judge extends Common {
	public function index()
	{
		$list = Model('Judge')->getList(1);
		//加工列表
		for ($i=0; $i<count($list); $i++) {
			$m_s = Model('Judge')->get_maj_sub($list[$i]['major_id'],$list[$i]['subject_id']);
			// dump($m_s);die;
			$list[$i]['major_name'] = $m_s['major_name'];
			$list[$i]['subject_name'] = $m_s['subject_name'];
		}
		$this->assign('list',$list);

		$major = db('major')->where('status',1)->select();
		$this->assign('major',$major);

		// $subject = db('subject')->where('status',1)->select();
		// $this->assign('subject',$subject);

		return $this->fetch();
	}

	//二级联动 返回数据
	public function major()
	{
		
		$major_id = input('major_id');
		$data = Db::name('subject')->where('major_id',$major_id)->select();
		return json($data);
	}

	//单选题编辑
	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			// dump($data);die;
			$result = db('judge')->where('id',$data['id'])->update($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0,'msg' => '编辑失败'));
			}
		} else {
			//部署渲染页面
			$id = input('id');
			$data = db('judge')->where('id',$id)->find();
			// dump($data);die;
			$this->assign('data',$data);

			$major = db('major')->where('status',1)->select();
			$this->assign('major',$major);
			$subject = db('subject')->where('status',1)->select();
			$this->assign('subject',$subject);

			return $this->fetch();
		}
	}

	//单选题添加
	public function add()
	{
		if (request()->isPost()) {
			//添加进单选题数据表中
			$data = input('post.data/a');
			$data['add_time'] = date("Y-m-d H:i:s");
			$result = db('judge')->insert($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '添加成功'));
			} else {
				return json(array('code' => 0,'msg' => '添加失败'));
			}
		} else {
			//渲染页面
			$major = db('major')->where('status',1)->select();
			$this->assign('major',$major);
			$subject = db('subject')->where('status',1)->select();
			$this->assign('subject',$subject);

			return $this->fetch();
		}
	}

	//单选题删除
	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		$ids = implode(',', $ids);
		//删除,说明:软删除(不是真正删除数据,而是改变status的值为0)
		$result = Db::name('judge')->where("id in ($ids)")->setField('status',0);
		if ($result) {
			$this->success("删除成功");
		} else {
			$this->error("删除失败");
		}
	}

	//修改on_off
    public function off(){
    	//获取id
    	$id = input("post.id");
    	// dump($id);die;
    	$result = Db::name('judge')->where('id',$id)->setField('status',0);

    	if($result){
    		// $this->success("更新成功");
    		return json(array('code'=>1,'msg'=>"停用成功"));
    	}else{
    		// $this->error("更新失败");
    		return json(array('code'=>0,'msg'=>"停用失败"));
    	}
    }
    public function on(){
    	//获取id
    	$id = input("post.id");
    	// dump($id);die;
    	$result = Db::name('judge')->where("id",$id)->setField('status',1);

    	if($result){
    		// $this->success("更新成功");
    		return json(array('code'=>1,'msg'=>"开启成功"));
    	}else{
    		// $this->error("更新失败");
    		return json(array('code'=>0,'msg'=>"开启失败"));
    	}
    }
}