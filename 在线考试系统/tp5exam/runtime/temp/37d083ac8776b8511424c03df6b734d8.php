<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:82:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\book\add.html";i:1606466560;s:75:"D:\phpstudy\PHPTutorial\WWW\tp5exam\application\admin\view\Common\head.html";i:1602636435;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <link rel="stylesheet" href="/static/admin/css/font.css">
<link rel="stylesheet" href="/static/admin/css/xadmin.css">
<script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form class="layui-form layui-col-space5">
                            	<input type="hidden" id="cover_img" name="cover_img">
                            	<div class="layui-form-item">
                            		<label class="layui-form-label">
				                    	<span class="x-red">*</span>书名
				                    </label>
                            		<div class="layui-input-inline">
				           				<input type="text" name="book_name" required class="layui-input" placeholder="书籍名称">
				           			</div>
                            	</div>
                            	<div class="layui-form-item">
                            		<label  class="layui-form-label">
				                    	<span class="x-red">*</span>简介
				                    </label>
                            		<div class="layui-input-inline">
                            			<textarea name="synopsis" required placeholder="书籍简介" class="layui-textarea"></textarea>
                            		</div>
                            	</div>
                            	<div class="layui-form-item">
                            		<label  class="layui-form-label">
                            			<span class="x-red">*</span>选择封面
                            		</label>
	                            	<button type="button" class="layui-btn" id="test1">
									  <i class="layui-icon">&#xe67c;</i>上传图片
									</button>
								</div>
								<div class="layui-form-item">
				                    <label class="layui-form-label"><span class="x-red">*</span>是否付费</label>
				                    <div class="layui-input-block">
				      					<input type="radio" name="vip" value="1" title="是" >
                                    	<input type="radio" name="vip" value="0" title="否" checked>
				                    </div>
				                </div>
								<div class="layui-form-item">
				                    <label class="layui-form-label"><span class="x-red">*</span>添加标签</label>
				                    <div class="layui-input-block">
				                    <?php if(is_array($title) || $title instanceof \think\Collection || $title instanceof \think\Paginator): $i = 0; $__LIST__ = $title;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?>
				                        <input type="checkbox" name="title[]" lay-skin="primary" title="<?php echo $t['title_name']; ?>" value="<?php echo $t['id']; ?>">
				                    <?php endforeach; endif; else: echo "" ;endif; ?>
				                    </div>
				                </div>
				           		<div class="layui-form-item">
				           			<label  class="layui-form-label">
				                    	<span class="x-red">*</span>选择专业
				                    </label>
				           			<div class="layui-input-inline">
		                                <select name="major_id" id="major" lay-filter="major" class="layui-input" required >
		                                	<option>请选择专业</option>
		                                	<?php if(is_array($major) || $major instanceof \think\Collection || $major instanceof \think\Paginator): $i = 0; $__LIST__ = $major;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?>
		                                	<option value="<?php echo $m['id']; ?>" <?php if(\think\Request::instance()->get('major_id') == $m['id']): ?>selected<?php endif; ?>><?php echo $m['major_name']; ?></option>
		                                	<?php endforeach; endif; else: echo "" ;endif; ?>
		                                </select>
	                            	</div>
                            	</div>
                            	<div class="layui-form-item">
                            		<label  class="layui-form-label">
				                    	<span class="x-red">*</span>选择科目
				                    </label>
                            		<div class="layui-input-inline"> 
		                                <select id="subject" name="subject_id" class="layui-input-inline" required>
		                                	<option value="0">请先选择专业</option>
		                                </select>
	                            	</div>
                            	</div>

                            	<div class="layui-form-item">
                            		<label  class="layui-form-label">
				                    </label>
	                            	<div class="layui-show-xs-block">
					                    <button class="layui-btn" type="button" lay-submit="" lay-filter="add">增加</button>
					                </div>
				            	</div>
                            </form>
                        </div>
                        

                    </div>
                </div>
            </div>
        </div> 
    </body>
    <script>
		layui.use(['laydate','form'], function(){
			var laydate = layui.laydate;
			var form = layui.form;

			form.on('select(major)', function(data) {
	          var major_id = $('#major').val();
	          $.post("<?php echo url('Book/major'); ?>",{major_id:major_id},function(data){
	            console.log(data)
	            //清空subject里面的内容
	            $('#subject').empty();
	            $.each(data,function(key,val) {
	              $('#subject').append("<option value="+val.id + ">"+val.subject_name+"</option>");
	             
	            })
	            form.render('select');
	          });
	        });

			layui.use('upload', function(){
			  var upload = layui.upload;
			  
			  //执行实例
			  var uploadInst = upload.render({
			    elem: '#test1' //绑定元素
			    ,url: "<?php echo url('Book/uploadimg'); ?>" //上传接口
  				,multiple: false
			    ,done: function(res){
			      //上传完毕回调
			      if (res.code) {
			      	layer.msg(res.msg,{icon: 1,time:1000});
			      	$('#cover_img').val(res.path);
			      } else {
			      	layer.msg(res.msg,{icon: 2,time:1000});
			      }
			    }
			    ,error: function(){
			      //请求异常回调
			    }
			  });
			});

			form.on('submit(add)', function(data){
	            //发异步，把数据提交给php
	            $.post("<?php echo url('Book/add'); ?>",{data:filterData(data.field)}, function(data) {
					if (data.code == 1) {
						layer.alert(data.msg, {
						    icon: 6
						},
						function(data) {
						    //关闭当前frame
						    xadmin.close();
						    // 可以对父窗口进行刷新 
						    xadmin.father_reload();
						});  
					} else {
						layer.alert(data.msg, {
						    icon: 5
						},
						function() {
						    //关闭当前frame
						    xadmin.close();
						    // 可以对父窗口进行刷新 
						    xadmin.father_reload();
						});                     
					}                      
				})
				return false;
			});

	        //form提交数组函数
	        filterData = function (data) {
	            var roleListIds = {};
	            var temp = {};
	            for (var key in data) {
	                if (key.indexOf("[") === -1) {
	                    roleListIds[key] = data[key];
	                } else {
	                    temp[key] = data[key];
	                }
	            }

	            var temp_arr = [];
	            for (var key in temp) {
	                var _arr = key.split("[");
	                var field = _arr[0];
	                if (temp_arr.indexOf(field) === -1) {
	                    temp_arr.push(field);
	                }
	            }

	            for (var i in temp_arr) {
	                var _f = temp_arr[i];
	                var _temp = [];
	                for (var k in temp) {
	                    if (k.indexOf(_f) !== -1) {
	                        _temp.push(temp[k])
	                    }
	                }
	                roleListIds[_f] = _temp;
	            }
	            return roleListIds;
	        };

			//执行一个laydate实例
			laydate.render({
			  elem: '#start' //指定元素
			});

			//执行一个laydate实例
			laydate.render({
			  elem: '#end' //指定元素
			});
		});

 

    </script>

</html>