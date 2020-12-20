<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;

function getRoleName($role_id) {
	// echo $role_id;die;
	$roleName = Db::name('role')->field('role_name')->where("id in ($role_id)")->select();
	$role_name = [];
	//将二维数组转换为一维数组
	foreach ($roleName as $key => $value) {
		$role_name[] = $value['role_name'];
	}
	$role_name = implode(',', $role_name). ',';
	// dump($role_name);die;
	return $role_name;
}

function sub_name($name) {
	$str = substr($name, 0, 10);
	$str .= '...';
	return $str;
}
