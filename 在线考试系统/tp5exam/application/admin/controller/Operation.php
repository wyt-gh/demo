<?php
namespace app\admin\controller;
use think\Db;

class Operation extends Common {
	public function index()
	{
		$list = Model('Operation')->getList(1);
		//加工列表
		for ($i=0; $i<count($list); $i++) {
			$m_s = Model('Operation')->get_maj_sub($list[$i]['major_id'],$list[$i]['subject_id']);
			// dump($m_s);die;
			$list[$i]['major_name'] = $m_s['major_name'];
			$list[$i]['subject_name'] = $m_s['subject_name'];
		}
		$this->assign('list',$list);

		$major = db('major')->where('status',1)->select();
		$this->assign('major',$major);

		// $subject = db('subject')->where('status',1)->select();
		// $this->assign('subject',$subject);
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
			// dump($data);die;
			$result = db('operation')->where('id',$data['id'])->update([
				'operation_name'	=>	$data['operation_name'],
				'major_id'	=>	$data['major_id'],
				'subject_id'	=>	$data['subject_id'],
			]);
			// dump($result);die;
			if ($result) {
				return json(array('code' => 1,'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0,'msg' => '编辑失败或未编辑'));
			}
		} else {
			//部署渲染页面
			$id = input('id');
			$data = db('operation')->where('id',$id)->find();
			// dump($data);die;
			$this->assign('data',$data);

			$major = db('major')->where('status',1)->select();
			$this->assign('major',$major);
			$subject = db('subject')->where('status',1)->select();
			$this->assign('subject',$subject);

			return $this->fetch();
		}
	}

	//单选题添加
	public function add()
	{
		if (request()->isPost()) {
			//添加进单选题数据表中
			$data = input('post.data/a');
			$data['add_time'] = date("Y-m-d H:i:s");
			$result = db('operation')->insert($data);

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

			return $this->fetch();
		}
	}

	//单选题删除
	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		$ids = implode(',', $ids);
		//删除,说明:软删除(不是真正删除数据,而是改变status的值为0)
		$result = Db::name('operation')->where("id in ($ids)")->setField('status',0);
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
    	// dump($id);die;
    	$result = Db::name('operation')->where('id',$id)->setField('status',0);

    	if($result){
    		// $this->success("更新成功");
    		return json(array('code'=>1,'msg'=>"停用成功"));
    	}else{
    		// $this->error("更新失败");
    		return json(array('code'=>0,'msg'=>"停用失败"));
    	}
    }
    public function on(){
    	//获取id
    	$id = input("post.id");
    	// dump($id);die;
    	$result = Db::name('operation')->where("id",$id)->setField('status',1);

    	if($result){
    		// $this->success("更新成功");
    		return json(array('code'=>1,'msg'=>"开启成功"));
    	}else{
    		// $this->error("更新失败");
    		return json(array('code'=>0,'msg'=>"开启失败"));
    	}
    }

    //文件上传
    public function upload(){
    	$id = input('get.id');
	    // 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('file');
	    // dump($file);die;
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'operation' . DS . $id);
	        if($info){
	            // 成功上传后 获取上传信息
	        	$path = 'public/uploads' . DS . 'operation' . DS . $id;
	            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
	            $name =  $info->getSaveName();
	            $path = $path. DS .$name;

	            $up_path = db('operation')->where('id',$id)->setField('pathinfo',$path);

	            if ($up_path) {
	            	$this->success('上传成功');
	            } else {
	            	$this->error('上传失败');
	            }
	        }else{
	            // 上传失败获取错误信息
	            // echo $file->getError();
	            $this->error('上传失败');
	        }
	    } else {
	    	$this->error('未找到文件');
	    }
	}

	//下载文件
	public function download()
	{
		$id = input('get.id');
		// dump($id);die;
	    $file_n = Db::name("operation")->where("id",$id)->find();
	    if(!$file_n){
	        return "暂无下载入口";
	    }
        $file = $file_n["pathinfo"];
        //str_replace为了严谨点嘛，不要也可以
        $file_lj = str_replace("\\","/",ROOT_PATH);
        $files = $file_lj.$file;
       	// echo $files;die;
        if(!file_exists($files)){
            return "文件不存在";
        }else {
            //输入文件标签
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($files));
            Header("Content-Disposition: attachment; filename=" . $file_n["pathinfo"]);

            $fp = fopen($files, 'r');
            $buffer = 1024;
            while(!feof($fp)){
            	$fdata = fread($fp ,$buffer);
            	echo $fdata;
            }
            // readfile($files);	//读取文件
            fclose($fp);
        }
	 
	}
}