<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Students extends Model {
	//页面列表
	public function getList($status)
	{
		$where = " status = $status ";
		$start = input('get.start');
		$end = input('get.end');
		$id_card = input('get.id_card');
		$major_id = input('get.major_id');
		$username = input('get.username');

		$tel = input('get.tel');
		$class_name = input('get.class_name');
		$class_teacher = input('get.class_teacher');


		if ($start) {
			$start = strtotime($start);
			$where .= " and UNIX_TIMESTAMP(add_time)>=$start ";
		}
		if ($end) {
			$end = strtotime($end)+60*60*24;
			$where .= " and UNIX_TIMESTAMP(add_time)<=$end ";	
		}
		if ($id_card) {
			$where .= " and id_card = '$id_card' ";
		}
		if ($major_id) {
			$where .= " and major_id = '$major_id' ";
		}
		if ($username) {
			$where .= " and username = '$username' ";
		}
		if ($tel) {
			$where .= " and tel = '$tel' ";
		}
		if ($class_name) {
			$where .= " and class_name = '$class_name' ";
		}
		if ($class_teacher) {
			$where .= " and class_teacher = '$class_teacher' ";
		}
		// echo $where;die;
		//初始列表
		$data = $this->where($where)->paginate(15,false,['query'=>array('start'=>$start, 'end'=>$end, 
		'major_id'=>$major_id, 'id_card'=>$id_card, 'username'=>$username, 'tel'=>$tel, 'class_name'=>$class_name, 'class_teacher'=>$class_teacher)]);
		//给$data添加一个major_name字段
		for ($i = 0; $i <count($data); $i++) {
			$major_name = db('major')->field('major_name')->where('id', $data[$i]['major_id'])->find();
			$data[$i]['major_name'] = $major_name['major_name'];
		}
		return $data;
	}
}