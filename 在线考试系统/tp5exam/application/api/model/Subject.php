<?php
namespace app\api\model;
use think\Model;
use think\Db;

class Subject extends Model {
	public function operating($sin_len,$sel_len,$jud_len,$ope_len,$subject_id,$major_id) //前四个参数分别是四种题型的个数,$subject_id是科目id
	{
		$data = [];	//最后返回的数据
		$tab_name = ['single','selection','judge','operation']; //四个题型对应的四个表
		$arr_len = [$sin_len,$sel_len,$jud_len,$ope_len];	//四个题型各自的长度
		//分别封装四种题型

		for ($x = 0; $x < count($arr_len); $x++) {
			$res = Db::name("$tab_name[$x]")->orderRaw("RAND()")->limit($arr_len[$x])->where(['major_id'=>$major_id,'subject_id'=>$subject_id])->select();
			$data[] = $res;
		}
		// dump($data);die;
		return $data;

	}

	public function id_to_name($id)
	{	
		$subject_name = Db::name('subject')->where('id',$id)->field('subject_name')->find();
		return $subject_name['subject_name'];
	}
}