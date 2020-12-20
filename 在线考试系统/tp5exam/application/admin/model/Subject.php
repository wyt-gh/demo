<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Subject extends Model {
	//页面列表
	public function getList($status)
	{	
		//初始列表
		$data = $this->where('status',$status)->paginate(15);
		return $data;
	}

	//通过major_id获取 专业名
	public function getMajor_name($major_id)
	{
		$major_name = Db::name('major')->field('major_name')->where('id',$major_id)->find();
		return $major_name;
	}

	public function id_to_name($id)
	{	
		$subject_name = Db::name('subject')->where('id',$id)->field('subject_name')->find();
		return $subject_name['subject_name'];
	}
}