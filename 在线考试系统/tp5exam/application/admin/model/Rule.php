<?php
namespace app\admin\model;
use think\Model;
use think\Db;
/**
 * 
 */
class Rule extends Model
{
	///读取权限列表
	public function getRule()
	{
		//获取rule表数据
		$rule_list = $this->where('parent_id>0 AND is_show=0')->paginate(15);
		
		$data = $rule_list;

		foreach ($data as $key => $value) {
			$parent_id = $value['parent_id'];
			$parent_name = $this->field('rule_name')->where('id',$parent_id)->find();
			//添加一个属性 parent_name
			$rule_list[$key]['parent_name'] = $parent_name['rule_name'];
		}	
		// dump($rule_list);die;
		return $rule_list;
	}

	//获取上级权限菜单模型,只获取顶级、二级权限
	public function getTopRule()
	{
		//顶级菜单
		$rule = $this->field('id,rule_name')->where('action_name','#')->select(); 
		// dump($rule);die;
		//用for循环遍历$rule
		for ($i = 0; $i < count($rule); $i++) {
			//该顶级权限下的二级权限数组
			$second_rule = $this->field('id,rule_name')->where('parent_id',$rule[$i]['id'])->select();
			// dump($second_rule);die;
			//为$rule添加二级权限
			$rule[$i]['second_rule'] = $second_rule;
		}
		return $rule;

	}

	//修改rule表模型
	public function upRule($data)
	{
		$up = $this->where('id',$data['id'])->update($data);
		return $up;
	}


}