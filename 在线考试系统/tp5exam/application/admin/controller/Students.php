<?php
namespace app\admin\controller;
use think\Db;
use think\Loader;

class Students extends Common {
	public function index()
	{
		$list = Model('Students')->getList(1);

		// dump($list);die;
		$this->assign('list',$list);

		$major = db('major')->where('status',1)->select();
		$this->assign('major',$major);
		return $this->fetch();
	}

	//编辑
	public function edit()
	{
		if (request()->isPost()) {
			$data = input('post.data/a');
			$result = db('students')->where('id',$data['id'])->update($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '编辑成功'));
			} else {
				return json(array('code' => 0,'msg' => '编辑失败'));
			}
		} else {
			//部署渲染页面
			$id = input('id');
			$data = db('students')->where('id',$id)->find();
			// dump($data);die;
			$this->assign('data',$data);

			$major = db('major')->where('status',1)->select();
			$this->assign('major',$major);
			return $this->fetch();
		}
	}

	//添加
	public function add()
	{
		if (request()->isPost()) {
			//添加进单选题数据表中
			$data = input('post.data/a');
			$data['add_time'] = date("Y-m-d H:i:s");
			$result = db('students')->insert($data);

			if ($result) {
				return json(array('code' => 1,'msg' => '添加成功'));
			} else {
				return json(array('code' => 0,'msg' => '添加失败'));
			}
		} else {
			//渲染页面
			$major = db('major')->where('status',1)->select();
			$this->assign('major',$major);
			return $this->fetch();
		}
	}

	//删除
	public function del()
	{
		//获取ids的值,它是一个数组
		$ids =  input('post.ids/a');
		$ids = implode(',', $ids);
		//删除,说明:软删除(不是真正删除数据,而是改变status的值为0)
		$result = Db::name('students')->where("id in ($ids)")->setField('status',0);
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
    	$result = Db::name('students')->where('id',$id)->setField('status',0);

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
    	$result = Db::name('students')->where("id",$id)->setField('status',1);

    	if($result){
    		// $this->success("更新成功");
    		return json(array('code'=>1,'msg'=>"开启成功"));
    	}else{
    		// $this->error("更新失败");
    		return json(array('code'=>0,'msg'=>"开启失败"));
    	}
    }

    //phpexcel导出
    public function phpexcelout ()
    {
    	vendor("PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel  = new \PHPExcel();
        for ($i = 0; $i < 2; $i++) {
        	if($i > 0)
		    {    
		        #创建新的内置表
		        $objPHPExcel->createSheet();
		    }
        	if ($i == 0) {
        		$objPHPExcel->setActiveSheetIndex($i);
			    #获取当前活动的sheet
			    $objSheet = $objPHPExcel->getActiveSheet();
			    #给当前活动的sheet起个名字
			    $objSheet->setTitle('学生信息表');

			    $data = Model('Students')->getList(1);
			    // dump($data);die;
			    $objSheet->setCellValue('A1','用户名')->setCellValue('B1','身份证')->setCellValue('C1','性别')->setCellValue('D1','密码')->setCellValue('E1','班级')->setCellValue('F1','老师')->setCellValue('G1','电话')->setCellValue('H1','专业')->setCellValue('I1','地址');
			    
			    $j = 2;
			    foreach ($data as $key => $v) {
			        $objSheet->setCellValue('A'.$j,$v['username'])
			        		->setCellValue('B'.$j,$v['id_card'])
			        		->setCellValue('C'.$j,$v['sex'])
			        		->setCellValue('D'.$j,$v['password'])
			        		->setCellValue('E'.$j,$v['class_name'])
			        		->setCellValue('F'.$j,$v['class_teacher'])
			        		->setCellValue('G'.$j,$v['tel'])
			        		->setCellValue('H'.$j,$v['major_id'])
			        		->setCellValue('I'.$j,$v['address']);
			        $j++;
			    }
        	} else {
        		$objPHPExcel->setActiveSheetIndex($i);
			    #获取当前活动的sheet
			    $objSheet = $objPHPExcel->getActiveSheet();
			    #给当前活动的sheet起个名字
			    $objSheet->setTitle('专业解析表');
			    $objSheet->setCellValue('A1','专业id')->setCellValue('B1','专业名称');
			    $data = db('major')->where('status',1)->select();
			    $j = 2;
			    foreach ($data as $k => $v) {
			    	$objSheet->setCellValue('A'.$j,$v['id'])->setCellValue('B'.$j,$v['major_name']);
			    	$j++;
			    }
        	}
        }
        
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="学生信息表.xls"');  
        //日期为文件名后缀
        header('Cache-Control: max-age=0');
	    #生成excel文件
		$objWrite = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$objWrite->save('php://output');
    }

    //
    public function phpexcelimport ()
    {
    	vendor("PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");

    	$file = request()->file('file');

    	if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'students' . DS);
	        if($info){
	            // 成功上传后 获取上传信息
	        	$path = 'public' . DS . 'uploads' . DS . 'students' . DS;
	            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
	            $name =  $info->getSaveName();
	            $path = ROOT_PATH . $path. DS .$name;
	        }else{
	            // 上传失败获取错误信息
	            // echo $file->getError();
	            $this->error('导入失败');
	        }
	    } else {
	    	$this->error('未获取到文件');
	    }
	    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));//判断导入表格后缀格式
		
		if($extension == 'xlsx') {
			$objReader =\PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel =$objReader->load($path, $encode = 'utf-8');
		}else if($extension == 'xls'){
			$objReader =\PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel =$objReader->load($path, $encode = 'utf-8');
		} else {
			return $this->error('文件格式错误!');
		}

    	#获取Excel文件中sheet的总数
    	$sheetCount = $objPHPExcel->getSheetCount();
		for ($i=0; $i < $sheetCount; $i++) { 
		    $data[] = $objPHPExcel->getSheet($i)->toArray();
		}
		// dump($data);
		$data = $data[0];	//获取到的数据
		$name = [];		//字段名
		$data2 = [];	//最后插入的数据
		foreach ($data as $key => $val) {
			if ($key == 0) {	//第一个不要
				continue;
			}
			$data2[] = [
				'username'	=>	$val[0],
				'id_card'	=>	$val[1],
				'sex'		=>	$val[2],
				'password'	=>	$val[3],
				'class_name'	=>	$val[4],
				'class_teacher'	=>	$val[5],
				'tel'		=>	$val[6],
				'major_id'	=>	$val[7],
				'address'	=>	$val[8],
			];
		}
		$insert = db('students')->insertAll($data2);
		if ($insert) {
			$this->success('导入成功!');
		} else {
			$this->error('导入失败!');
		}
    }
}
