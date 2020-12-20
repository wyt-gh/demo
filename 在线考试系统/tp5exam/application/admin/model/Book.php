<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Cookie;
use think\Cache;

class Book extends Model {
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
		$id = input('get.id');
		$book_name = input('get.book_name');
		$major_id = input('get.major_id');
		$subject_id = input('get.subject_id');
		//用户信息
		$admin = Cookie::get('admin');
		//用户id
		$admin_id = Cookie::get('id');
		//读取当前用户对应的文件信息
		$admin_id = Cache::get('user_'.$admin_id)['id'];
		// dump($admin_id);die;
		$where = " status = $status  and admin_id = $admin_id ";


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
		if ($book_name) {
			$where .= " and book_name = '$book_name' ";
		}
		if ($major_id) {
			$where .= " and major_id = $major_id ";
		}
		if ($subject_id) {
			$where .= " and subject_id = $subject_id ";
		}
		//初始列表
		$data = $this->where($where)->paginate(15,false,['query'=>array('start'=>$start, 'end'=>$end, 'major_id'=>$major_id, 'subject_id'=>$subject_id, 'id'=>$id, 'book_name'=>$book_name)]);
		return $data;
	}
}