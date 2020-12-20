<?php
namespace app\admin\controller;
use think\Controller;
use think\captcha\Captcha;
use think\Request;
use think\Cookie;
use think\Cache;

class Login extends Controller
{
	public function login()
	{
		/*
			思想
			1.获取表单提交信息
			2.验证验证码是否正确
			3.与数据库交互(判断信息是否正确)
		*/
		if (Cookie::has('id')) {
			$this->redirect('Index/index');
		}
		if (Request::instance()->isPost()) {
			$info =request()->post();
			$username = $info['data']['username'];
			$password = $info['data']['password'];
			$code = $info['data']['captcha'];

			// 验证验证码
			if (!$this->check_verify($code)) {
				$this->error('验证码错误');
			}
			$result = model('Admin')->check_login($username,$password);
			return $result;
		} else {
			return $this->fetch();
		}
		
	}

	/**
	 验证码配置
	 */
	public function verify() 
	{
		$config = [
	        'fontSize' => 25,
	        // 验证码字体大小(px)
	        'useCurve' => true,
	        // 是否画混淆曲线
	        'useNoise' => true,
	        // 是否添加杂点
	        'imageH'   => 0,
	        // 验证码图片高度
	        'imageW'   => 0,
	        // 验证码图片宽度
	        'length'   => 2,
	        // 验证码位数
	        'fontttf'  => '',
	        // 验证码字体，不设置随机获取
	        'bg'       => [243, 251, 254],
	        // 背景颜色
	        'reset'    => true,
        	// 验证成功后是否重置
	    ];
		$captcha = new Captcha($config);
		return $captcha->entry();
	}

	// 检测输入的验证码是否正确，$code为用户输入的验证码字符串，$id多个验证码标识
	public function check_verify($code){
	    $captcha = new Captcha();
	    return $captcha->check($code);
	}

	public function logout () 
	{
		//读取Cookie_id
		$id = Cookie::get('id');
		Cache::rm('user_'.$id);
		Cookie::delete('admin');
		Cookie::delete('username');
		Cookie::delete('id');
		$this->redirect('Login/login');
	}
}