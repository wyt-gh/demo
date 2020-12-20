<?php
namespace app\admin\controller;
use think\Cookie;
use think\Db;
use think\Cache;

class Index extends Common
{

	public function index() 
	{
		//用户id
		$id = Cookie::get('id');
		//读取当前用户对应的文件信息
		$user_info = Cache::get('user_'.$id);
		$this->assign('menus',$user_info['menus']);
		return $this->fetch();
	}

	public function welcome() 
	{
		return $this->fetch();
	}

}