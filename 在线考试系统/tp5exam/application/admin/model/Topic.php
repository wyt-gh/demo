<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Topic extends Model {
	//页面列表
	public function getTopic($status)
	{
		$start = input('get.start');
		$end = input('get.end');
		$where = " status = $status ";
		$username = input('get.username');
		$class_name = input('get.class_name');
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
		if ($username) {
			$where .= " and username = '$username' ";
		}
		if ($class_name) {
			$where .= " and class_name = '$class_name' ";
		}
		if ($major_id) {
			$where .= " and major_id = $major_id ";
		}
		if ($subject_id) {
			$where .= " and subject_id = $subject_id ";
		}

		//初始列表
		$data = $this->where($where)->paginate(15,false,['query'=>array('start'=>$start, 'end'=>$end, 
		'major_id'=>$major_id, 'subject_id'=>$subject_id, 'username'=>$username, 'class_name'=>$class_name)]);
		return $data;
	}

	//
	public function topic_info ($status)
	{
		$start = input('get.start');
		$end = input('get.end');
		$class_name = input('get.class_name');
		$major_id = input('get.major_id');
		$subject_id = input('get.subject_id');

		$where = " status = $status ";

		if ($start) {
			$start = strtotime($start);
			$where .= " and UNIX_TIMESTAMP(add_time)>=$start ";
		}
		if ($end) {
			$end = strtotime($end)+60*60*24;
			$where .= " and UNIX_TIMESTAMP(add_time)<=$end ";	
		}
		if ($class_name) {
			$where .= " and class_name = '$class_name' ";
		}
		if ($major_id) {
			$where .= " and major_id = $major_id ";
		}
		if ($subject_id) {
			$where .= " and subject_id = $subject_id ";
		}

		$count = Topic::where($where)->count();	//总人数
		$avg_scroe = Topic::where($where)->avg('scroe');	//平均分

		$excellent_count = 0;	//优秀人数 > 85
		$pass_count = 0;	//挂科人数 < 60
		$nopass_count = 0;	//不及格
		$data = $this->where($where)->select();
		foreach ($data as $k => $val) {
			if ($val['scroe'] >= 60) {	
				//及格
				$pass_count++;
				if ($val['scroe'] >= 85) {
					//成绩优秀
					$excellent_count++;
				}
			} else {
				//不及格
				$nopass_count++;
			}
			
		}
		$pass_rate = intval(($pass_count / $count) * 100);	//及格率
		$excellent_rate = intval(($excellent_count / $count) * 100);	//优秀率
		$info['avg'] = $avg_scroe;
		$info['pass_rate'] = $pass_rate;
		$info['excellent_rate'] = $excellent_rate;
		$info['nopass_count'] = $nopass_count;

		return $info;
	}
}