<?php
namespace app\admin\controller;
use think\Db;

class Major extends Common {
	public function index()
	{
		$list = Model('Major')->getList(1);
		$this->assign('list',$list);
		return $this->fetch();
	}


	//单选题编辑
	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			$result = db('major')->where('id',$data['id'])->update($data);
			
			if ($result) {
				return json(array('code' => 1,'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0,'msg' => '编辑失败或未编辑'));
			}
		} else {
			//部署渲染页面
			$id = input('id');
			$data = db('major')->where('id',$id)->find();
			// dump($data);die;
			$this->assign('data',$data);
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
			$result = db('major')->insert($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '添加成功'));
			} else {
				return json(array('code' => 0,'msg' => '添加失败'));
			}
		} else {
			//渲染页面
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
		$result = Db::name('major')->where("id in ($ids)")->setField('status',0);
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
    	$result = Db::name('major')->where('id',$id)->setField('on_off',0);

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
    	$result = Db::name('major')->where("id",$id)->setField('on_off',1);

    	if($result){
    		// $this->success("更新成功");
    		return json(array('code'=>1,'msg'=>"开启成功"));
    	}else{
    		// $this->error("更新失败");
    		return json(array('code'=>0,'msg'=>"开启失败"));
    	}
    }

  
}