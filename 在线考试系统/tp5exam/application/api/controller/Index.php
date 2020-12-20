<?php 
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Cache;
use think\file;

class Index extends Controller {
	public function _initialize()
	{
        header('Access-Control-Allow-Origin:*'); 
        header("Access-Control-Allow-Headers:content-type"); 
        header("Access-Control-Request-Method:GET,POST");
        if(Request::instance()->isOptions()){
        	die;	
        }
    }

    //登录判断
	public function login()
	{
		$data = input('post.data/a');
		// dump($data);die;
		$result = Model('Students')->check_login($data);
		
		return json_encode($result);
		
	}

	//修改密码
	public function change_pw() 
	{
		$data = input('post.data/a');
		// dump($data);die;
		$result = db('students')->where(['id_card'=>$data['id_card'],'password'=>$data['old_password']])->setField('password',$data['new_password']);
		// dump($result);die;
		if ($result) {
			return json(array('code'=>1,'msg'=>'修改成功'));
		} else {
			return json(array('code'=>0,'msg'=>'修改失败'));
		}
	}

	//返回所有 status=1, on_off=1 的科目
	public function subject() 
	{
		$major_id = input('post.major_id');
		// dump($major_id);die;
		$subject = db('subject')->field('subject_name')->where(['status'=>1,'on_off'=>1,'major_id'=>$major_id])->select();
		// dump($subject);die;
		return json_encode($subject);
	}

	//返回考试题目
	public function question()
	{
		$major_id = input('post.major_id');
		$subject_name = input('post.subject_name');

		$id = db('subject')->field('id')->where(['major_id'=>$major_id,'subject_name'=>$subject_name])->find();
		$id = $id['id'];
		$question = Model('Subject')->operating(10,10,10,1,$id,$major_id);
		return json_encode($question);
	}

	//批阅试卷
	public function read ()
	{
		//由于将数据和文件一起上传,所以需要对数据进行类型修改,比较麻烦
		$topic = json_decode(input('post.topic/a')[0]);	//题目
		$answer = json_decode(input('post.answer/a')[0]);	//答案
		$admin = json_decode(input('post.admin/a')[0]);	//学生信息
		$subject_name = input('post.subject_name');	//科目

		$subject = db('subject')->where(['subject_name'=>$subject_name,'major_id'=>$admin->major_id])->find();
		
		$report_card = [0,0,0,0];	//学生成绩单	 下标:0=>single,1=>selection,2=>judge,3=>operation
		$data = [
			'single'	=>	[],
			'selection'	=>	[],
			'judge'		=>	[],
			'operation'	=>	'',
			'id_card'	=>	$admin->id_card,
			'username'	=>	$admin->username,
			'class_name'=>	$admin->class_name,
			'major_id'	=>	$subject['major_id'],
			'subject_id'=>	$subject['id'],
			'add_time'	=>	date("Y-m-d H:i:s"),
			'not_read'	=>	0,
			'scroe'		=>	0,
			'single_scroe'		=>	0,
			'selection_scroe'	=>	0,
			'judge_scroe'		=>	0,
			'operation_scroe'	=>	'等待教师批阅',
			'pathinfo'	=>	'',	
		];
		// dump($data);die;
		//批阅试卷
		foreach ($topic as $k => $val) {	//4种题型
			for ($i = 0; $i < count($val); $i++) {	//每种题型的题目个数
				if ($k != 3) {
					$an = $answer[$k][$i];
					//单选
					if ($k == 0) {
						if ($val[$i]->right_key == $an) { $report_card[$k] += 3; }
						$data['single'][] = 'single'.$val[$i]->id.'-'.$an;		
					}
					//多选
					if ($k == 1) {
						if (!$an) {
							$data['selection'][] = 'selection'.$val[$i]->id.'-'.'未填';
						} else {
						//对多选题答案进行排序
							$index = new Index();
							$an = $index -> str_sort($an);
							$data['selection'][] = 'selection'.$val[$i]->id.'-'.$an;
							if ($val[$i]->right_key == $an) { $report_card[$k] += 3; }
						}						
					} 
					//判断
					if ($k == 2) {
						if ($val[$i]->right_key == $an) { $report_card[$k] += 3; }
						$data['judge'][] = 'judge'.$val[$i]->id.'-'.$an;
					}
				}
				else {
					//操作题
					$file = request()->file('file');	//获取文件
					$id = $admin->id;		//用户id

					if ($file) {
						$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'operation' . DS . $id);
						if ($info) {
							$path = 'public/uploads/operation/'.$id;
							$name = $info->getSaveName();
							$path = $path. DS .$name; 
							//将路径添加进数据中
							$data['pathinfo'] = $path;
						}
					}
					//添加操作题题目进$data
					$data['operation'] = 'operation'.$val[$i]->id.'-'.$topic[$k][$i]->operation_name;
				}
			}		
		}
		$data['single'] = implode(',', $data['single']);
		$data['selection'] = implode(',', $data['selection']);
		$data['judge'] = implode(',', $data['judge']);
		$data['single_scroe'] = $report_card[0];
		$data['selection_scroe'] = $report_card[1];
		$data['judge_scroe'] = $report_card[2];
		$data['scroe'] = $report_card[0] + $report_card[1] + $report_card[2];
		$result = db('topic')->insert($data);

		if ($result) {
			return json(array('code'=>1, 'msg'=>'批改成功'));
		} else {
			return json(array('code'=>0, 'msg'=>'自动批改出现错误'));
		}
	}

	public function is_exam ()
	{
		$major_id = input('post.major_id');
		$subject_name = input('post.subject_name');
		$id_card = input('post.id_card');
		$subject_id = db('subject')->field('id')->where(['major_id'=>$major_id,'subject_name'=>$subject_name])->find();
		$subject_id = $subject_id['id'];
		$result = db('topic')->where(['id_card'=>$id_card,'subject_id'=>$subject_id])->find();
		// dump($result);
		if ($result) {
			if ($result['not_read'] == 1) {
				return json(array('code'=>1,'msg'=>'该科目已经交卷,并且审批完成!','data'=>$result));
			} else {
				return json(array('code'=>1,'msg'=>'该科目已经交卷,正在审批中!','data'=>''));
			}	
		} else {
			return json(array('code'=>0,'msg'=>'还未进行考试!','data'=>''));
		}
	}

	public function str_sort ($str)
	{
		$new_str = '';
		if (strpos($str, 'A') > -1) { $new_str .= 'A'; } 
		if (strpos($str, 'B') > -1) { $new_str .= 'B'; } 
		if (strpos($str, 'C') > -1) { $new_str .= 'C'; } 
		if (strpos($str, 'D') > -1) { $new_str .= 'D'; } 
		return $new_str;
	}

	public function select_falseexam () 
	{
		$major_id = input('post.major_id');
		$subject_name = input('post.subject_name');
		$id_card = input('post.id_card');
		$result = []; //最后返回的数据
		// dump($id_card);
		// dump($subject_name);
		// dump($major_id);die;
		//找考卷
		$subject_id = db('subject')->where(['major_id'=>$major_id,'subject_name'=>$subject_name])->find();
		$subject_id = $subject_id['id'];
		$data = db('topic')->where(['id_card'=>$id_card,'subject_id'=>$subject_id])->find();
		// dump($data);die;
		$data['single'] = explode(',', $data['single']);
		$data['selection'] = explode(',', $data['selection']);
		$data['judge'] = explode(',', $data['judge']);
		$que = ['single','selection','judge']; //三种题型
		for ($x = 0; $x < count($que); $x++) {
			for ($i = 0; $i < count($data[$que[$x]]); $i++) {
				$data[$que[$x]][$i] = str_replace($que[$x],'',$data[$que[$x]][$i]);
				$da = explode('-', $data[$que[$x]][$i]);
				$id = $da[0];
				$answer = $da[1];
				$question = db($que[$x])->where('id',$id)->find();
				if ($question['right_key'] != $answer) {
					//如果是错题
					$arr2 = $da[1];
					if ($x == 1) {
						//如果是多选题
						if ($answer != '未填') {
							//如果不是未填
							$arr = [];
							for ($j = 0; $j < strlen($answer); $j++) {							
								$arr[] = substr($answer,$j,1);
							}
							$answer = $arr;
						} else {
							$answer = [];
						}					
					}
					$question['answer'] = $answer;
					$question['myanswer'] = $arr2;
					$result[$que[$x]][] = $question;
				}
				
			}
		}
		// dump($result);die;
		return json_encode($result);
	}

	public function sub_rankinglist () 
	{
		$major_id = input('post.major_id');
		$subject_name = input('post.subject_name');
		$subject_id = db('subject')->field('id')->where(['major_id'=>$major_id,'subject_name'=>$subject_name])->find();
		$subject_id = $subject_id['id'];
		$data = db('topic')->where('subject_id',$subject_id)->orderRaw('scroe desc')->select();
		// dump($data);die;
		return json_encode($data);
	}
}
