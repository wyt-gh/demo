<?php
namespace app\api\model;
use think\Model;
use think\Db;

class Book extends Model {
	public function mapBookList ($list) 
	{
		for ($i=0; $i<count($list); $i++) {
			// 图片
			$list[$i]['cover_img'] = 'HTTP://'.$_SERVER['REMOTE_ADDR'].'/'.$list[$i]['cover_img'];
			$list[$i]['cover_img'] = str_replace("\\", "/",$list[$i]['cover_img']);
			//作者
			$username = db('admin')->where('id',$list[$i]['admin_id'])->find();
			$list[$i]['username'] = $username['username'];
			// 标签
			$str = $list[$i]['title'];
			$title = db('title')->field('title_name')->where("id in ($str)")->select();
			$str = '';
			foreach ($title as $k => $val) {
				// dump($val);die;
				if ($k == count($title)-1) {
					$str .= $val['title_name'];
				} else {
					$str .= $val['title_name'] . ' ';
				}
			}
			$list[$i]['title'] = $str;
		}
		return $list;
	}

	//给封面添加完整地址
	public function coverimg ($data)
	{
		for ($i=0; $i<count($data); $i++) {
			// 图片
			$data[$i]['cover_img'] = 'HTTP://'.$_SERVER['REMOTE_ADDR'].'/'.$data[$i]['cover_img'];
			$data[$i]['cover_img'] = str_replace("\\", "/",$data[$i]['cover_img']);
		}
		return $data;
	}
}