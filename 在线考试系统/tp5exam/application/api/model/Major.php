<?php
namespace app\api\model;
use think\Model;
use think\Db;

class Major extends Model {
	public function id_to_name($id)
	{	
		$major_name = Db::name('major')->where('id',$id)->field('major_name')->find();
		return $major_name['major_name'];
	}
}