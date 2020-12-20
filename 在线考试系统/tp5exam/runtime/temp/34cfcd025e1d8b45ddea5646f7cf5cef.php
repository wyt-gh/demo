<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\role\index.html";i:1603161924;s:75:"D:\phpstudy\PHPTutorial\WWW\tp5exam\application\admin\view\Common\head.html";i:1602636435;}*/ ?>
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
        <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
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
                                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                                </div>
                            </form>
                        </div>
                        <div class="layui-card-header">
                            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                            <button class="layui-btn" onclick="xadmin.open('添加用户','<?php echo url("Role/add"); ?>',600,400)"><i class="layui-icon"></i>添加</button>

                        </div>

                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th>
                                    <input type="checkbox" name="" lay-filter="checkall" lay-skin="primary">
                                  </th>
                                  <th>ID</th>
                                  <th>角色名</th>
                                  <th>拥有权限规则</th>
                                  <th>描述</th>
                                  <th>操作</th>
                              </thead>
                              <tbody>
                              	<?php if(is_array($role_list) || $role_list instanceof \think\Collection || $role_list instanceof \think\Paginator): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                	<tr>
                                    <td>
                                       <input type="checkbox" name="id"  lay-skin="primary" value="<?php echo $vo['id']; ?>">
                                    </td>
                                    <td><?php echo $vo['id']; ?></td>
                                    <td><?php echo $vo['role_name']; ?></td>
                                    <td><?php echo $vo['rule_name']; ?></td>
                                    <td><?php echo $vo['content']; ?></td>
	                                    <td class="td-manage">              
		                                    <a title="编辑"  onclick="xadmin.open('编辑','<?php echo url('Role/edit',['id'=>$vo['id']]); ?>',600,400)" href="javascript:;">
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

    

      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              $.post("<?php echo url('Role/del'); ?>",{ids:id},function(data) {
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
          $.post("<?php echo url('Role/del'); ?>",{ids:ids},function(data) {
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