<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\scroe\edit.html";i:1605839849;s:75:"D:\phpstudy\PHPTutorial\WWW\tp5exam\application\admin\view\Common\head.html";i:1602636435;}*/ ?>
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
        <style type="text/css">
        	.pink {
        		color: pink;
        	}
        	.dim {
        		margin-right: 20px;
        	}
        	.layui-form-item {
        		font-size: 18px;
        	}
        </style>
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form class="layui-form layui-col-space5">
                            	<input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                            	<div style="font-size: 20px;">
                            		<div class="layui-btn-fluid ">
                            			<div class="layui-inline dim">姓名:<span class="pink"><?php echo $data['username']; ?></span></div> 
                            			<div class="layui-inline dim">班级:<span class="pink">
                            			<?php echo $data['class_name']; ?></span></div>
                            			<div class="layui-inline dim">专业:<span class="pink"><?php echo $data['major_name']; ?></span></div>
                            			<div class="layui-inline dim">科目:<span class="pink"><?php echo $data['subject_name']; ?></span></div> 
                            			<div class="layui-inline dim">考试时间:<span class="pink"><?php echo $data['add_time']; ?></span></div>
                            		</div>
                            		<div class="layui-btn-fluid ">
                            		<div class="layui-inline dim">单选题:<span class="pink"><?php echo $data['single_scroe']; ?></span></div>
                            		<div class="layui-inline dim">多选题:<span class="pink"><?php echo $data['selection_scroe']; ?></span></div>
                            		<div class="layui-inline dim">判断题:<span class="pink"><?php echo $data['judge_scroe']; ?></span></div>
                            		<div class="layui-inline dim">操作题:<span class="pink"><?php echo $data['operation_scroe']; ?></span></div>
                            		<div class="layui-inline dim">总分:<span class="pink"><?php echo $data['scroe']; ?></span></div>
                            		</div>
                            	</div>
                            	

                            	<div class="layui-form-item">
                            		单选题:<textarea name="single" disabled lay-verify="required" placeholder="请输入" class="layui-textarea"><?php echo $data['single']; ?></textarea>
                            	</div>
                            	<div class="layui-form-item">
                            		多选题:<textarea name="selection" disabled  lay-verify="required" placeholder="请输入" class="layui-textarea"><?php echo $data['selection']; ?></textarea>
                            	</div>
                            	<div class="layui-form-item">
                            		判断题:<textarea name="judge" disabled lay-verify="required" placeholder="请输入" class="layui-textarea"><?php echo $data['judge']; ?></textarea>
                            	</div>
                            	<div class="layui-form-item">
                            		操作题题目:<textarea name="operation" disabled lay-verify="required" placeholder="请输入" class="layui-textarea"><?php echo $data['operation']; ?></textarea>
                            	</div>
                            	<a href="<?php echo url('Scroe/download'); ?>?id=<?php echo $data['id']; ?>"><p class="pink">下载学生操作题答案</p></a>
                            	<div class="layui-form-item layui-inline ">
                            		操作题分数:<input class="layui-input" name="operation_scroe">
                            	</div>
                            	<div class="layui-show-xs-block">
				                    <button class="layui-btn" type="button" lay-submit="" lay-filter="edit">批改</button>
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

			form.on('submit(edit)', function(data){
	            //发异步，把数据提交给php
	            $.post("<?php echo url('Scroe/edit'); ?>",{data:filterData(data.field)}, function(data) {
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
	                    console.log(k);
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