<?php
namespace app\admin\controller;
use think\Controller;
use think\Cookie;
use think\Hook;

class Common extends Controller
{
	public function _initialize()
	{
		//初始化方法
		//1.验证用户是否登录
		//第一种方法
		// $result = $this->check_login();
		// if (!$result) {
		// 	$this->redirect('Login/login');
		// }
		Hook::add('app_init','app\\admin\\behavior\\Login');
		Hook::listen('app_init');
		//2.权限问题
		Hook::add('app_init','app\\admin\\behavior\\Rule');
		Hook::listen('app_init');
		
		
	}

	public function check_login()
	{
		if (!Cookie::has('id')){
			return false;
		} else {
			return true;
		}
	}
}