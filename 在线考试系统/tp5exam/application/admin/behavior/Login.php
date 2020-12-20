<?php
namespace app\admin\behavior;
use think\Cookie;

class Login {
	public function run(&$params) 
	{
		//用户登录检测
		if (!Cookie::get('admin')) {
			$url = url('Login/login');
			echo "<script>top.location.href='$url'</script>";
		}
	}
}

