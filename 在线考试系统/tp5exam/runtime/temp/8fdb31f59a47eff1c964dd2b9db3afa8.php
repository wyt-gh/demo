<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:88:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\students\index.html";i:1606662464;s:75:"D:\phpstudy\PHPTutorial\WWW\tp5exam\application\admin\view\Common\head.html";i:1602636435;}*/ ?>
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
        <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">演示</a>
            <a>
              <cite>导航元素</cite></a>
          </span>
          <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form class="layui-form layui-col-space5">
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start" value="<?php echo \think\Request::instance()->get('start'); ?>">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end" value="<?php echo \think\Request::instance()->get('end'); ?>">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input" placeholder="请输入学生姓名" name="username" value="<?php echo \think\Request::instance()->get('username'); ?>">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input" placeholder="请输入身份证号" name="id_card" value="<?php echo \think\Request::instance()->get('id_card'); ?>">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input" placeholder="请输入电话号码" name="tel" value="<?php echo \think\Request::instance()->get('tel'); ?>">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input" placeholder="请输入班级" name="class_name" value="<?php echo \think\Request::instance()->get('class_name'); ?>">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select id="major" name="major_id"  lay-filter="major">
                                      <option value="">请选择一个专业</option>
                                      <?php if(is_array($major) || $major instanceof \think\Collection || $major instanceof \think\Paginator): $i = 0; $__LIST__ = $major;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?>
                                      <option value="<?php echo $m['id']; ?>" <?php if(\think\Request::instance()->get('major_id') == $m['id']): ?>selected<?php endif; ?>><?php echo $m['major_name']; ?></option>
                                      <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input" placeholder="请输入班主任" name="class_teacher" value="<?php echo \think\Request::instance()->get('class_teacher'); ?>">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                                </div>
                            </form>
                        </div>
                        <div class="layui-card-header">
                            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                            <button class="layui-btn" onclick="xadmin.open('添加用户','<?php echo url('Students/add'); ?>',800,600)"><i class="layui-icon"></i>添加</button>
                            <a href="<?php echo url('students/phpexcelout'); ?>"> <button class="layui-btn">phpexcel批量导出</button></a>
                            <div class="layui-inline">
                              <form action="<?php echo url('students/phpexcelimport'); ?>" enctype="multipart/form-data" method="post">
                                批量导入表格:<input type="file" name="file"/>
                                <input class="layui-btn layui-btn-primary" type="submit" value="phpexcel导入" />    
                              </form> 
                            </div>
                        </div>

                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th>
                                    <input type="checkbox" name="" lay-filter="checkall" lay-skin="primary">
                                  </th>
                                  <th>ID</th>
                                  <th>学生姓名</th>
                                  <th>性别</th>
                                  <th>身份证</th>
                                  <th>密码</th>
                                  <th>手机号码</th>
                                  <th>班级</th>
                                  <th>专业</th>
                                  <th>班主任</th>
                                  <th>地址</th>
                                  <th>添加时间</th>
                                  <th>状态</th>
                                  <td>操作</td>
                              </thead>
                              <tbody>
                                  <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                	<tr>
	                                    <td>
	                                       <input type="checkbox" name="id"  lay-skin="primary" value="<?php echo $vo['id']; ?>">
	                                    </td>
                                      <td><?php echo $vo['id']; ?></td>
                                      <td><?php echo $vo['username']; ?></td>
                                      <td><?php echo $vo['sex']; ?></td>
                                      <td><?php echo $vo['id_card']; ?></td>
                                      <td><?php echo $vo['password']; ?></td>
                                      <td><?php echo $vo['tel']; ?></td>
                                      <td><?php echo $vo['class_name']; ?></td>
                                      <td><?php echo $vo['major_name']; ?></td>
                                      <td><?php echo $vo['class_teacher']; ?></td>
	                                    <td><?php echo $vo['address']; ?></td>
                                      <td><?php echo $vo['add_time']; ?></td>
                                      <td class="td-status">
                                      <a onclick="member_stop(this,'<?php echo $vo['id']; ?>')" href="javascript:;"  title="<?php if($vo['status'] == '1'): ?>正常<?php else: ?>停用<?php endif; ?>">
                                        <span class="layui-btn <?php if($vo['status'] == '1'): ?>layui-btn-normal<?php else: ?>layui-btn-disabled<?php endif; ?> layui-btn-mini">
                                          <?php if($vo['status'] == '1'): ?>正常<?php else: ?>停用<?php endif; ?>
                                        </span>
                                        </a> 
                                      </td>
	                                    <td class="td-manage">
		                                    
		                                    <a title="编辑"  onclick="xadmin.open('编辑','<?php echo url('Students/edit',['id'=>$vo['id']]); ?>',800,600)" href="javascript:;">
		                                       <i class="layui-icon">&#xe642;</i>
		                                    </a>
		                                    <a title="删除" onclick="member_del(this,'<?php echo $vo['id']; ?>')" href="javascript:;">
		                                       <i class="layui-icon">&#xe640;</i>
		                                    </a>
	                                  	</td>
	                                
                               		</tr>
                                  <?php endforeach; endif; else: echo "" ;endif; ?>

                              </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                <div>
                                  <?php echo $list->render(); ?>
                                </div>
                            </div>
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

        form.on('checkbox(checkall)', function(data) {
          if (data.elem.checked) {
            $('tbody input').prop('checked',true);
          } else {
            $('tbody input').prop('checked',false);
          }
          form.render('checkbox');
        })
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });

       /*用户-停用*/
      function member_stop(obj,id){
        if($(obj).attr('title')=='正常'){
          //发异步把用户状态更改为停用
          $.post("<?php echo url('Students/off'); ?>",{id:id},function(data) {
            if (data.code == 1) {
              $(obj).attr('title','停用');
              $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-normal').addClass('layui-btn-disabled').html('停用');
            } else {
              layer.msg(data.msg, {icon:2, time:1000});
            }
          });

        }else if ($(obj).attr('title') == '停用'){
          //发异步把用户状态更改为启用
          $.post("<?php echo url('Students/on'); ?>",{id:id}, function(data) {
            if (data.code == 1) {
              $(obj).attr('title','正常')
              $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').addClass('layui-btn-normal').html('正常');
            } else {
              layer.msg(data.msg,{icon: 2,time:1000});
            }
          });
        } 
            
      }

      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              $.post("<?php echo url('Students/del'); ?>",{ids:id},function(data) {
                console.log(data)
                if (data.code == 1) {
                  //删除成功
                  layer.msg(data.msg, {icon: 1});
                  $(obj).parents("tr").remove();
                } else {
                  //删除失败
                  layer.msg(data.msg, {icon: 2});
                }
              });
          });
      
      }



      function delAll (argument) {

        // var data = tableCheck.getData();
        var ids = [];//保存选中的id值,需要删除的id
        $('tbody input').each(function(index, el) {
          if ($(this).prop('checked')){
            ids.push($(this).val())
          }
          
        });

        layer.confirm('确认要删除吗？'+ ids.toString(),function(index){
          $.post("<?php echo url('Students/del'); ?>",{ids:ids},function(data) {
            if (data.code == 1) {
              //删除成功
              layer.msg(data.msg, {icon: 1});
              $(".layui-form-checked").not('.header').parents('tr').remove();
            } else {
              //删除失败
              layer.msg(data.msg, {icon: 2});
            }
          });  
        });
      }


    </script>

</html>