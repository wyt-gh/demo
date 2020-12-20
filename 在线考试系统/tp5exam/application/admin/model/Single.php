<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Single extends Model {
	//通过$idm,$ids 获取 专业,科目名称
	public function get_maj_sub($idm,$ids)
	{
		$major = Db::name('major')->field('major_name')->where('id',$idm)->find();
		$subject = Db::name('subject')->field('subject_name')->where('id',$ids)->find();
		$result = [];
		$result = [
			'major_name'	=>	$major['major_name'],
			'subject_name'	=>	$subject['subject_name']
		];
		return $result;
	}

	//页面列表
	public function getList($status)
	{
		$start = input('get.start');
		$end = input('get.end');
		$where = " status = $status ";
		$id = input('get.id');
		$major_id = input('get.major_id');
		$subject_id = input('get.subject_id');


		if ($start) {
			$start = strtotime($start);
			$where .= " and UNIX_TIMESTAMP(add_time)>=$start ";
		}
		if ($end) {
			$end = strtotime($end)+60*60*24;
			$where .= " and UNIX_TIMESTAMP(add_time)<=$end ";	
		}
		if ($id) {
			$where .= " and id = $id ";
		}
		if ($major_id) {
			$where .= " and major_id = $major_id ";
		}
		if ($subject_id) {
			$where .= " and subject_id = $subject_id ";
		}
		//初始列表
		$data = $this->where($where)->paginate(15,false,['query'=>array('start'=>$start, 'end'=>$end, 
		'major_id'=>$major_id, 'subject_id'=>$subject_id, 'id'=>$id)]);
		return $data;
	}
}