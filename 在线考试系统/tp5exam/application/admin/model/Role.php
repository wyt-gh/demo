<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Role extends Model{
	public function getRole($status)
	{
		$start = input('get.start');
		$where = " status = $status ";
		if ($start) {
			$start = strtotime($start);
			$where .= " and UNIX_TIMESTAMP(a.add_time)>=$start ";
		}
		//拿到初步的数据表
		$data = $this->alias('a')->join('em_role_rule b','a.id = b.role_id')->field('a.id,a.role_name,a.content,a.status,b.rule_id')->where($where)->select();
		// dump($data);die;
		//将em_role_rule 表中rule_id 的值 改成 rule表中rule_name
		
		for($i = 0; $i < count($data); $i++) {
			$rule_id = $data[$i]['rule_id'];
			// dump($rule_id);die;
			$r_name = Db::name('rule')->field('rule_name')->where("id in ($rule_id)")->select();
			// 将$name赋值加工成下标为整形的数组
			$name = [];
			for($j = 0; $j < count($r_name); $j++) {
				$name[] = $r_name[$j]['rule_name'];
			}
			$name = implode(',', $name);
			$data[$i]['rule_name'] = $name;
			
		}
		return $data;
	}

	//角色表:角色名,描述,所有权限,权限id
	public function getruletable()
	{
		$rule = Db::name('rule')->field('id,rule_name,parent_id,controller_name')->select();
		return $rule;

	}

	//管理员信息
	public function getRoleInfo($id)
	{	
		//获取role表的 id,role_name,content和role_rule表的 rule_id
		$data = $this->alias('a')->join('em_role_rule b','a.id = b.role_id')->field('a.id,a.role_name,a.content,b.rule_id')->where('a.id',$id)->find();
		$id = explode(',', $data['rule_id']);
		// dump($id);die;
		foreach ($id as $key => $v) {
			$name = Db::name('rule')->field('id,rule_name')->where('id',$v)->find();
			$arr[] = $name;
		}
		
		//加工$data
		$data['rule'] = $arr;
		// unset($data['rule_id']);
		return $data;
	}
}