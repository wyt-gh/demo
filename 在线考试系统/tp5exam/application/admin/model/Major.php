<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Major extends Model {
	//页面列表
	public function getList($status)
	{	
		//初始列表
		$data = $this->where('status',$status)->paginate(15);
		return $data;
	}
	//通过id 获取 name
	public function id_to_name($id)
	{	
		$major_name = Db::name('major')->where('id',$id)->field('major_name')->find();
		return $major_name['major_name'];
	}
}