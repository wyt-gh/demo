<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:89:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\book\addsection.html";i:1606697155;s:75:"D:\phpstudy\PHPTutorial\WWW\tp5exam\application\admin\view\Common\head.html";i:1602636435;}*/ ?>
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
                            	<input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                            	<div class="layui-form-item">
                            		<label class="layui-form-label">
				                    	<span class="x-red">*</span>章节数
				                    </label>
				                    <div class="layui-input-inline">
                            			<input type="number" class="layui-input" min="1" name="section_num" value="<?php echo $number+1; ?>">
                            		</div>
                            	</div>
                            	<div class="layui-form-item">
                            		<label class="layui-form-label">
				                    	<span class="x-red">*</span>章节名
				                    </label>
                            		<div class="layui-input-inline">
				           				<input type="text" name="section_name" required class="layui-input" placeholder="填写章节名称">
				           			</div>
                            	</div>

                            	<div id="div1"></div>
								<textarea id="text1" style="width:100%; height:200px;" name="section_content"></textarea>
                           

                            	<div class="layui-form-item">
                            		<label  class="layui-form-label">
				                    </label>
	                            	<div class="layui-show-xs-block">
					                    <button class="layui-btn" type="button" lay-submit="" lay-filter="addsection">增加</button>
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

			form.on('submit(addsection)', function(data){
	            //发异步，把数据提交给php
	            $.post("<?php echo url('Book/addsection'); ?>",{data:data.field}, function(data) {
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
    <script type="text/javascript" src="//unpkg.com/wangeditor/dist/wangEditor.min.js"></script>
	<script type="text/javascript">
	    const E = window.wangEditor
	    const editor = new E('#div1')
	    const $text1 = $('#text1')
	    editor.config.onchange = function (html) {
	        // 第二步，监控变化，同步更新到 textarea
	        $text1.val(html)
	    }
	    editor.create()

	    // 第一步，初始化 textarea 的值
	    $text1.val(editor.txt.html())
	</script>
</html>