<?php
namespace app\admin\controller;
use think\Db;

class Count extends Common {
	public function index()
	{
		$list = Model('Topic')->getTopic(1);
		//加工列表
		for ($i=0; $i<count($list); $i++) {
			$list[$i]['major_name'] = Model('major')->id_to_name($list[$i]['major_id']);
			$list[$i]['subject_name'] = Model('subject')->id_to_name($list[$i]['subject_id']);
		}
		$info = Model('Topic')->topic_info(1);
		$major = db('major')->where('status',1)->select();

		$this->assign('info',$info);
		$this->assign('list',$list);
		$this->assign('major',$major);
		return $this->fetch();
	}




	//二级联动 返回数据
	public function major()
	{
		$major_id = input('major_id');
		$data = Db::name('subject')->where('major_id',$major_id)->select();
		return json($data);
	}

	//阅卷
	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			$scroe = db('topic')->field('scroe')->where('id', $data['id'])->find();
			$result = db('topic')->where('id', $data['id'])
			->update([
				'not_read'	=>	1,
				'operation_scroe'	=>	$data['operation_scroe'],
				'scroe'	=>	$scroe['scroe']+$data['operation_scroe'],
			]);

			if ($result) {
				return json(array('code' => 1,'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0,'msg' => '编辑失败'));
			}
		} else {
			//部署渲染页面
			$id = input('id');
			$subject_id = input('subject_id');
			$data = db('topic')->where(['id'=>$id, 'subject_id'=>$subject_id])->find();
			// dump($data);die;
			$data['major_name'] = Model('major')->id_to_name($data['major_id']);
			$data['subject_name'] = Model('subject')->id_to_name($data['subject_id']);
			$this->assign('data',$data);
			// dump($data);die;
			return $this->fetch();
		}
	}

	//删除
	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		$ids = implode(',', $ids);
		$result = Db::name('topic')->where("id in ($ids)")->setField('status',0);
		if ($result) {
			$this->success("删除成功");
		} else {
			$this->error("删除失败");
		}
	}

}