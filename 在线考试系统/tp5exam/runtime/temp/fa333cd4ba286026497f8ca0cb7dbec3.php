<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:86:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\students\add.html";i:1605150606;s:75:"D:\phpstudy\PHPTutorial\WWW\tp5exam\application\admin\view\Common\head.html";i:1602636435;}*/ ?>
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
                            	<div class="layui-form-item">
	                            	<div class="layui-inline layui-show-xs-block">
	                                    学生姓名<input class="layui-input" name="username" required>
	                                </div>
                            	</div>
                            	<div class="layui-form-item">
	                                <div class="layui-inline ">
	                                    性别<select name="sex">
	                                    	<option value="男">男</option>
	                                    	<option value="女">女</option>
	                                    </select>
	                                </div>
                            	</div>
                                <div class="layui-form-item">
	                                <div class="layui-inline layui-show-xs-block">
	                                    身份证<input class="layui-input" name="id_card" required>
	                                </div>
	                            </div>
                                <div class="layui-form-item">
	                                <div class="layui-inline layui-show-xs-block">
	                                    密码<input class="layui-input" name="password" required>
	                                </div>
                                </div>
                                <div class="layui-form-item">
	                                <div class="layui-inline layui-show-xs-block">
	                                    手机<input class="layui-input" name="tel" required>
	                                </div>
                                </div>
                                <div class="layui-form-item">
	                                <div class="layui-inline layui-show-xs-block">
	                                    班级<input class="layui-input" name="class_name" required>
	                                </div>
                                </div>
                                <div class="layui-form-item">
				                    <div class="layui-inline layui-show-xs-block">
	                                    专业名称<select id="major" name="major_id"  lay-filter="major">
	                                      <?php if(is_array($major) || $major instanceof \think\Collection || $major instanceof \think\Paginator): $i = 0; $__LIST__ = $major;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?>
	                                      <option value="<?php echo $m['id']; ?>"><?php echo $m['major_name']; ?></option>
	                                      <?php endforeach; endif; else: echo "" ;endif; ?>
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="layui-form-item">
	                                <div class="layui-inline layui-show-xs-block">
	                                    班主任<input class="layui-input" name="class_teacher" required>
	                                </div>
	                            </div>
	                            <div class="layui-form-item">
	                                <div class="layui-inline layui-show-xs-block">
	                                    地址<input class="layui-input" name="address" required>
	                                </div>
	                            </div>
	                            <div class="layui-form-item">
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

			form.on('submit(add)', function(data){
	            //发异步，把数据提交给php
	            $.post("<?php echo url('Students/add'); ?>",{data:filterData(data.field)}, function(data) {
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