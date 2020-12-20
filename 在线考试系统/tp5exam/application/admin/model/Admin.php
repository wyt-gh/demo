<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\Cookie;

class Admin extends Model{
	protected $pk = 'id';
	public function check_login($username,$password)
	{
		// dump($usename);die;
		$info = Db::name('admin')->where("username",$username)-> find();
		if(!$info){
			return json(['code'=>0,'msg'=>'该用户不存在']);
		}
		$rule = Db::name('admin_role')->field('role_id')->where("admin_id",$info['id'])->find();
		if($info['status'] == 0 && $rule['role_id'] != 1 && $info['on_off'] == 0){
			return json(['code'=>0, 'msg'=>'该用户被禁用']);

		}
		if($password !=$info['password']){
			return json(['code'=>0,'msg'=>'密码错误']);
		}
		Cookie::set('admin',$info,18000);//保存管理员名
		Cookie::set('username',$info['username'],18000);//保存管理员ID
		Cookie::set('id',$info['id'],18000);
		//保存最后登陆时间
		$time = date('Y-m-d H:i:s');
		$this->where('id',$info['id'])->setField('login_time',$time);//保存最后登陆IP
		$ip = request()->ip();
		$this->where('id',$info['id'])->setField('login_ip',$ip);
		return json(array('status'=>1,'msg'=>'登陆成功'));
	}

	public function getAllData($status) 
	{

		$start = input('get.start');
		$end = input('get.end');
		$where = " status = $status ";

		if ($start) {
			$start = strtotime($start);
			$where .= " and UNIX_TIMESTAMP(a.add_time)>=$start ";
		}
		if ($end) {
			$end = strtotime($end)+60*60*24;
			$where .= " and UNIX_TIMESTAMP(a.add_time)<=$end ";
			
		}

		$data = $this->alias('a')->join('em_admin_role b','a.id = b.admin_id')->where($where)->field("a.*,b.role_id")->order('id')->paginate(15);
		// echo Db::name('admin')->getLastSql();
		return $data;
	}

	//添加管理员模型
	public function addAdmin($data) {
		$info = $this->where("username",$data['username'])->find();
		//如果用户已经存在
		if ($info) {
			return false;
		}
		// dump($data);die;
		unset($data['role_id']);
		return $this->insertGetId($data);

	}

	//获取管理员数据模型
	public function getAdmin($id) 
	{
		//admin 与 admin_role 表连接
		$data = $this->alias('a')->join('em_admin_role b','a.id = b.admin_id')->where('a.id',$id)->field("a.id,a.username,a.password,b.role_id")->find();
		return $data;
	}
}