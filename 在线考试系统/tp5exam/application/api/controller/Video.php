<?php 
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\file;

class Video extends Controller {
	public function _initialize()
	{
        header('Access-Control-Allow-Origin:*'); 
        header("Access-Control-Allow-Headers:content-type"); 
        header("Access-Control-Request-Method:GET,POST");
        if(Request::instance()->isOptions()){
        	die;	
        }
    }

	public function videolist ()
	{
		$num = input('num/d');
		$number = input('number/d');
		// dump($num);die;
		$file = '../public/video/video_list.json';
		$files = file_get_contents($file);
		// return $files;
		
		$files = explode(',', $files);
		$files = array_slice($files, $num, $number);
		// dump($files);die;
		return json_encode($files);
		
	}

	public function insert ()
	{
		$file = '../public/video/video_list.json';
		$files = file_get_contents($file);
		dump($files);
	}
}
