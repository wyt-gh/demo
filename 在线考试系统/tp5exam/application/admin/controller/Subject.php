<?php
namespace app\admin\controller;
use think\Db;

class Subject extends Common {
	public function index()
	{
		$list = Model('Subject')->getList(1);
		for ($i=0; $i<count($list); $i++) {
			$major_name = Model('Subject')->getMajor_name($list[$i]['major_id']);
			$list[$i]['major_name'] = $major_name['major_name'];
		}
		// dump($list);die;
		$this->assign('list',$list);
		return $this->fetch();
	}


	//科目编辑
	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			$result = db('Subject')->where('id',$data['id'])->update($data);
			
			if ($result) {
				return json(array('code' => 1,'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0,'msg' => '编辑失败或未编辑'));
			}
		} else {
			//部署渲染页面
			$id = input('id');
			$data = db('subject')->where('id',$id)->find();
			// dump($data);die;
			$this->assign('data',$data);
			//专业
			$major = Model('Major')->getList(1);
			$this->assign('major',$major);
			return $this->fetch();
		}
	}

	//科目添加
	public function add()
	{
		if (request()->isPost()) {
			//添加进单选题数据表中
			$data = input('post.data/a');
			$data['add_time'] = date("Y-m-d H:i:s");
			// dump($data);die;
			$result = db('subject')->insert($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '添加成功'));
			} else {
				return json(array('code' => 0,'msg' => '添加失败'));
			}
		} else {
			//渲染页面
			$major = Model('Major')->getList(1);
			$this->assign('major',$major);
			return $this->fetch();
		}
	}

	//科目删除
	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		$ids = implode(',', $ids);
		//删除,说明:软删除(不是真正删除数据,而是改变status的值为0)
		$result = Db::name('subject')->where("id in ($ids)")->setField('status',0);
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
    	$result = Db::name('subject')->where('id',$id)->setField('on_off',0);

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
    	$result = Db::name('subject')->where("id",$id)->setField('on_off',1);

    	if($result){
    		// $this->success("更新成功");
    		return json(array('code'=>1,'msg'=>"开启成功"));
    	}else{
    		// $this->error("更新失败");
    		return json(array('code'=>0,'msg'=>"开启失败"));
    	}
    }

  
}