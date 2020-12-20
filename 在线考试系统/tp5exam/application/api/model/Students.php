<?php
namespace app\api\model;
use think\Model;
use think\Db;

class Students extends Model {
	public function check_login($data) {
		$id_card = $data['id_card'];
		$password = $data['password'];

		$result = $this->where(['id_card'=>$id_card,'password'=>$password,'status'=>1])->find();
		if ($result) {
			$major = Db::name('major')->field('major_name')->where('id',$result['major_id'])->find();
			$result['major_name'] = $major['major_name'];
			$result['code'] = 1;
		} else {
			$result['code'] = 0;
		}

		return $result;
		// dump($result);die;
		
	}

	// public function operating()
	// {
	// 	$len = $this::count();
	// 	return $len;
	// }
}