<?php
namespace app\api\controller;
use think\Db;
use think\Controller;
use think\Request;

class Book extends Controller {
	public function _initialize()
	{
        header('Access-Control-Allow-Origin:*'); 
        header("Access-Control-Allow-Headers:content-type"); 
        header("Access-Control-Request-Method:GET,POST");
        if(Request::instance()->isOptions()){
        	die;	
        }
    }

    //查询书籍接口
	public function search() 
	{
		$book_name = input('post.book_name');
		$booklist = db('book')->where('book_name','like','%'.$book_name.'%')->where(['status'=>1,'on_off'=>1])->select();
		if (count($booklist) > 0) {
			$booklist = Model('Book')->mapBookList($booklist);
		}
		return json_encode($booklist);
	}

	//搜索查询补全
	public function complete ()
	{
		$book_name = input('post.book_name');
		$searchlist = db('book')->field('book_name')->where('book_name','like', $book_name.'%')->where(['status'=>1,'on_off'=>1])->limit(8)->select();

		return json_encode($searchlist);
	}

	//将搜索内容添加到book_search表中
	public function addhistory ()
	{
		$student_id = input('post.student_id');
		$search_content = input('post.search_content');

		$search = db('book_search')->where(['student_id'=>$student_id,'search_content'=>$search_content])->find();
		$search_num = (int)$search['search_num'] + 1;

		if ($search) {	//存在此搜索历史,则搜索次数+1
			$history = db('book_search')->where(['student_id'=>$student_id,'search_content'=>$search_content])->update([
				'search_num'	=>	$search_num,
				'add_time'		=>	date('Y-m-d H:i:s')
			]);		
		} else {	//不存在则添加
			$history = db('book_search')->insert([
				'student_id'	=>	$student_id,
				'search_content'=>	$search_content,
				'add_time'	=>	date("Y-m-d H:i:s")
			]);
		}	
		dump($history);
	}

	//搜索历史接口
	public function search_history ()
	{
		$student_id = input('post.student_id');
		$history = db('book_search')->field('search_content')->where('student_id',$student_id)->order('add_time desc')->limit(6)->select();
		return json_encode($history);
	}
	//清空历史记录
	public function clear_history ()
	{
		$student_id = input('post.student_id');
		$clear = db('book_search')->where('student_id',$student_id)->delete();

		if ($clear) {
			return json(array('code'=>1));
		} else {
			return json(array('code'=>0));
		}
	}
	//书籍详情接口
	public function detail ()
	{
		$book_id = input('post.book_id');
		$student_id = input('post.student_id');
		// dump($id);die;
		$bookDetail = db('book')->where('id',$book_id)->find();
		$n[] = $bookDetail;
		$bookDetail = Model('Book')->mapBookList($n);	//参数必须是数组
		$bookDetail = $bookDetail[0];

		return json_encode($bookDetail);
	}

	//是否加入书架 
	public function isbookrack ()
	{
		$book_id = input('post.book_id');
		$student_id = input('post.student_id');
		$inboorack = db('read_record')->where([
			'student_id'	=>	$student_id,
			'book_id'		=>	$book_id,
		])->find();

		if ($inboorack) {
			return json_encode($inboorack['bookrack']);
		} else {
			return 0;
		}
		
	}

	//添加书籍到书架
	public function addbookrack ()
	{
		$book_id = input('post.id');
		$student_id = input('post.student_id');
		$result = db('read_record')->where(['student_id'=>$student_id,'book_id'=>$book_id])->find();
		if ($result) {	//有进行过阅读,判断是否加入过书架
			if ($result['bookrack'] == 1 ) {	
				//已加入过
				$code = 0;	
			} else {
				$result = db('read_record')->where(['student_id'=>$student_id,'book_id'=>$book_id])->update([
					'bookrack'	=>	1,
					'add_time'	=>	date('Y-m-d h:i:s')
				]);
				$code = 1;
			}
		} else {	//该书籍没有阅读记录
			$result = db('read_record')->insert([
				'student_id'	=>	$student_id,
				'book_id'		=>	$book_id,
				'memo'			=>	1,
				'bookrack'		=>	1,
				'add_time'		=>	date('Y-m-d h:i:s')
			]);
			$code = 1;
		}
		if ($result) {
			if ($code) {
				return json(array('code'=>1,'msg'=>'加入书架成功'));
			} else {
				return json(array('code'=>0,'msg'=>'加入书架失败'));
			}
		} else {
			return json(array('code'=>0,'msg'=>'加入书架失败'));
		}
	}

	//移出书架
	public function del_bookbrak ()
	{
		$book_ids = input('post.ids/s');
		$student_id = input('post.student_id');

		$result = db('read_record')->where('student_id',$student_id)->where("book_id in ($book_ids)")->update([
			'bookrack'	=>	0,
			'add_time'	=>	date('Y-m-d h:i:s'),
		]);

		if ($result) {
			return json(array('code'=>1,'msg'=>'成功移出书架'));
		} else {
			return json(array('code'=>0,'msg'=>'移出书架失败'));
		}
	}

	//获取学生书架书籍
	public function getbookrack ()
	{
		$student_id = input('post.student_id');
		$book_id =  db('read_record')->field('book_id')->where(['student_id'=>$student_id,'bookrack'=>1])->order('read_time desc')->select();
		$bookrack = [];
		if ($book_id) {
			//取出book_id,加工成字符串
			for ($i=0; $i < count($book_id); $i++) { 
				$bookrack = db('book')->field('cover_img,book_name,id')->where("id",$book_id[$i]['book_id'])->find();
				$bookrack['cover_img'] = 'HTTP://'.$_SERVER['REMOTE_ADDR'].'/'.$bookrack['cover_img'];
				$bookrack['cover_img'] = str_replace("\\", "/",$bookrack['cover_img']);
				$book_id[$i]['cover_img'] = $bookrack['cover_img'];
				$book_id[$i]['book_name'] = $bookrack['book_name'];
				$book_id[$i]['id'] = $bookrack['id'];
			}
		}
		
		return json_encode($book_id);
	}

	//从书架中移出
	public function del_bookrack () 
	{

	}

	//获取书签 
	public function memo ()
	{
		$student_id = input('post.student_id');
		$book_id = 	input('post.book_id');
		$memo = db('read_record')->field('memo')->where(['student_id'=>$student_id,'book_id'=>$book_id])->find();
		if ($memo) {
			return json_encode($memo);
		} else {
			return 1;
		}
	}

	//继续阅读之前的章节
    public function bookread ()
    {
    	$student_id = input('post.student_id');
    	$book_id = input('post.book_id');

    	if (input('post.num') == 'undefined' || input('post.num') == null) {	
			//没有的话就到阅读记录里面找
			$section_num = db('read_record')->where(['student_id'=>$student_id,'book_id'=>$book_id])->find();
			if ($section_num) {
				$section_num = $section_num['memo'];
			} else {
				$section_num = 1;
			}
    	} else {	
    		//有这个值直接返回对应的章节
			$section_num = input('post.num');
    	}
    	//获取章节
    	$data = db('book_section')->where(['book_id'=>$book_id,'section_num'=>$section_num])->find();
    	// dump($section_num);
    	// dump($data);die;
    	if ($data) {
    		//更新阅读记录
    		$read_record = db('read_record')->where(['student_id'=>$student_id,'book_id'=>$book_id])->select();
    		if ($read_record) {
    			//阅读过,有阅读记录
    			$update = db('read_record')->where(['student_id'=>$student_id,'book_id'=>$book_id])->update([
    				'memo'	=>	$data['section_num'],
    				'read_time'	=>	date('Y-m-d h:i:s')
    			]);
    		} else {
    			//第一次阅读添加阅读记录
    			$insert =  db('read_record')->where(['student_id'=>$student_id,'book_id'=>$book_id])->insert([
    				'student_id'	=>	$student_id,
    				'book_id'		=>	$book_id,
    				'read_time'		=>	date('Y-m-d h:i:s')
    			]);
    			//更新小说阅读量
    			$read = db('book')->field('read')->where('id',$book_id)->find()['read'] + 1;
    			db('book')->where('id',$book_id)->setField('read',$read);	
    		}
    		return json(array('code'=>1,'data'=>$data));
    	} else {
    		return json(array('code'=>0,'msg'=>'无章节','data'=>''));
    	}
    }

    //获取书籍目录
    public function catalog() 
    {
    	$book_id = input('post.book_id');
    	// dump($book_id);die;
    	$catalog = db('book_section')->field('id,section_name,section_num')->where('book_id',$book_id)->orderRaw('id asc')->select();
    	return json_encode($catalog);
    }

    //热门推荐接口
    public function hotbook ()
    {
    	$data = db('book')->where(['status'=>1,'on_off'=>1])->order(['read'=>'desc'])->limit(30)->select();
    	shuffle($data);
    	$data = array_slice($data, 0 , 8);
    	$data = model('book')->coverimg($data);
    	return json_encode($data);
    }
    //最新推荐接口
    public function newbook ()
    {
    	$data = db('book')->where(['status'=>1,'on_off'=>1])->order(['add_time'=>'desc'])->limit(30)->select();
    	shuffle($data);
    	$data = array_slice($data, 0 , 8);
    	$data = model('book')->coverimg($data);
    	return json_encode($data);
    }
    //免费推荐接口
    public function freebook ()
    {
    	$data = db('book')->where(['status'=>1,'on_off'=>1,'vip'=>0])->limit(30)->select();
    	shuffle($data);
    	$data = array_slice($data, 0 , 8);
    	$data = model('book')->coverimg($data);
    	return json_encode($data);
    }
    //完本推荐接口
    public function endbook ()
    {
    	$data = db('book')->where(['status'=>1,'on_off'=>1,'end'=>1])->limit(30)->select();
    	shuffle($data);
    	$data = array_slice($data, 0 , 8);
    	$data = model('book')->coverimg($data);
    	return json_encode($data);
    }
    //同类推荐
    public function samebook () 
    {
    	$subject_id = input('post.subject_id');
    	$book_id = input('post.book_id');
    	$data = db('book')->where(['status'=>1,'on_off'=>1,'subject_id'=>$subject_id])->where('id','NEQ', $book_id)->limit(30)->select();
    	shuffle($data);
    	$data = array_slice($data, 0 , 8);
    	$data = model('book')->coverimg($data);
    	return json_encode($data);
    }

    public function select_section() 
    {
    	$book_id = input('post.book_id');
    	//没有任何章节不允许阅读
    	$section_count = db('book_section')->where('book_id',$book_id)->select();
    	if (!count($section_count)) {
    		return json(array('code'=>0,'msg'=>'该书籍暂无任何章节!'));
    	} else {
    		return json(array('code'=>1));
    	}
    }
}