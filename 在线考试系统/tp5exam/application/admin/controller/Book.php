<?php
namespace app\admin\controller;
use think\Db;
use think\Cookie;

class Book extends Common {
	public function index()
	{
		$list = Model('Book')->getList(1);
		//加工列表
		for ($i=0; $i<count($list); $i++) {
			// 专业
			$m_s = Model('Book')->get_maj_sub($list[$i]['major_id'],$list[$i]['subject_id']);
			$list[$i]['major_name'] = $m_s['major_name'];
			$list[$i]['subject_name'] = $m_s['subject_name'];
			// 图片
			$list[$i]['cover_img'] = 'HTTP://'.$_SERVER['REMOTE_ADDR'].'/'.$list[$i]['cover_img'];
			$list[$i]['cover_img'] = str_replace("\\", "/",$list[$i]['cover_img']);
			//作者
			$username = db('admin')->where('id',$list[$i]['admin_id'])->find();
			$list[$i]['username'] = $username['username'];
			// 标签
			$str = $list[$i]['title'];
			$title = db('title')->field('title_name')->where("id in ($str)")->select();
			$str = '';
			foreach ($title as $k => $val) {
				// dump($val);die;
				if ($k == count($title)-1) {
					$str .= $val['title_name'];
				} else {
					$str .= $val['title_name'] . '、';
				}
			}
			$list[$i]['title'] = $str;
		}
		$this->assign('list',$list);
		$major = db('major')->where('status',1)->select();
		$this->assign('major',$major);
		return $this->fetch();
	}

	//二级联动 返回数据
	public function major()
	{	
		$major_id = input('major_id');
		$data = Db::name('subject')->where('major_id',$major_id)->select();
		return json($data);
	}

	//单选题编辑
	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			unset($data['file']);	//删除不需要的字段
			$data['title'] = implode(',',$data['title']);	//修改title格式
			$data['admin_id'] = Cookie::get('id');
			$data['add_time'] = date("Y-m-d H:i:s");	//添加时间
			$result = db('book')->where('id',$data['id'])->update($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0,'msg' => '编辑失败'));
			}
		} else {
			//部署渲染页面
			$id = input('id');
			$book = db('book')->where('id',$id)->find();
			// $book['cover_img'] = 'HTTP://'.$_SERVER['REMOTE_ADDR'].'/'.$book['cover_img'];
			// $book['cover_img'] = str_replace("\\", "/",$book['cover_img']);
			$this->assign('book',$book);
			$major = db('major')->where('status',1)->select();
			$this->assign('major',$major);
			$subject = db('subject')->where('status',1)->select();
			$this->assign('subject',$subject);
			$title = db('title')->select();
			$this->assign('title',$title);

			return $this->fetch();
		}
	}

	//上传图片
	public function uploadimg ()
	{
		$file = request()->file('file');
		//用户id
		$id = Cookie::get('id');
		// dump($id);die;
		if ($file) {
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'book' . DS . $id . DS . 'image');
			if ($info) {
				$path = 'uploads' . DS . 'book' . DS . $id . DS . 'image' . DS;
				$name = $info->getSaveName();
				$path = $path . $name;

				return json(array('code'=>1,'path'=>$path,'msg'=>'上传图片成功'));
			} else {
				return json(array('code'=>0,'path'=>'','msg'=>'上传图片失败'));
			}
		} else {
			return json(array('code'=>0,'path'=>'','msg'=>'没获取图片'));
		}
	}

	//添加
	public function add()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			unset($data['file']);	//删除不需要的字段
			$data['title'] = implode(',',$data['title']);	//修改title格式
			$data['admin_id'] = Cookie::get('id');
			$data['add_time'] = date("Y-m-d H:i:s");	//添加时间
			// dump($data);die;
			$result = db('Book')->insert($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '添加成功'));
			} else {
				return json(array('code' => 0,'msg' => '添加失败'));
			}
		} else {
			//渲染页面
			$major = db('major')->where('status',1)->select();
			$this->assign('major',$major);
			$subject = db('subject')->where('status',1)->select();
			$this->assign('subject',$subject);
			$title = db('title')->select();
			$this->assign('title',$title);
			return $this->fetch();
		}
	}

	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		$ids = implode(',', $ids);
		$result = Db::name('Book')->where("id in ($ids)")->setField('status',0);
		if ($result) {
			$this->success("删除成功");
		} else {
			$this->error("删除失败");
		}
	}

	//修改on_off
    public function off(){
    	//获取id
    	$id = input("post.id");
    	$result = Db::name('Book')->where('id',$id)->setField('status',0);

    	if($result){
    		return json(array('code'=>1,'msg'=>"停用成功"));
    	}else{
    		return json(array('code'=>0,'msg'=>"停用失败"));
    	}
    }
    public function on(){
    	//获取id
    	$id = input("post.id");
    	$result = Db::name('Book')->where("id",$id)->setField('status',1);

    	if($result){
    		return json(array('code'=>1,'msg'=>"开启成功"));
    	}else{
    		return json(array('code'=>0,'msg'=>"开启失败"));
    	}
    }

    //添加章节
    public function addsection ()
    {
    	if (request()->isPost()) {
    		$data = input('post.data/a');

    		$exist = db('book_section')->where(['book_id'=>$data['book_id'],'section_num'=>$data['section_num']])->find();
    		if ($exist) {
    			return json(array('code'=>0,'msg'=>'该章节已存在!'));
    		}
    		$data['add_time'] = date("Y-m-d H:i:s");
    		$add_section = db('book_section')->insert($data);
    		if ($add_section) {
				return json(array('code' => 1,'msg' => '添加成功'));
			} else {
				return json(array('code' => 0,'msg' => '添加失败'));
			}
    	} else {
    		$book_id = input('id');
	    	$this->assign('book_id',$book_id);
	    	$number = db('book_section')->where('book_id',$book_id)->select();
	    	$this->assign('number',count($number));
	    	return $this->fetch();
    	}
    }

    //修改章节
    public function editsection ()
    {
    	if (request()->isPost()) {
    		$data = input('post.data/a');
    		// dump($data);die;
    		$upd = db('book_section')->where(['book_id'=>$data['book_id'],'id'=>$data['id']])->update($data);
    		if ($upd) {
				return json(array('code' => 1,'msg' => '修改成功'));
			} else {
				return json(array('code' => 0,'msg' => '未有任何修改'));
			}
    	} else {
    		$book_id = input('id');
    		$section = db('book_section')->where('book_id',$book_id)->select();
    		$this->assign('book_id',$book_id);
    		$this->assign('section',$section);
    		// dump($section);die;
    		return $this->fetch();
    	}
    }

    public function select ()
    {
    	$id = input('id');
    	$section = db('book_section')->where('id',$id)->find();
    	// dump($section);die;
    	return json($section);
    }

}