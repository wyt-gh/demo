<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:84:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\rule\index.html";i:1603107947;s:75:"D:\phpstudy\PHPTutorial\WWW\tp5exam\application\admin\view\Common\head.html";i:1602636435;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
 <!--        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" /> -->
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
                                    <input class="layui-input"  placeholder="权限名称"  name="rule_name" required>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  placeholder="模块" name="module_name" required>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  placeholder="控制器" name="controller_name" required>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  placeholder="方法" name="action_name" required>
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <select name="parent_id" name="sel_op" required>
                                      <option value="0">顶级权限</option>
                                      <?php if(is_array($parent_rule) || $parent_rule instanceof \think\Collection || $parent_rule instanceof \think\Paginator): $i = 0; $__LIST__ = $parent_rule;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                        <!-- 顶级权限 -->
                                        <option value="<?php echo $vo['id']; ?>">|-<?php echo $vo['rule_name']; ?></option>
                                        <?php if(is_array($vo['second_rule']) || $vo['second_rule'] instanceof \think\Collection || $vo['second_rule'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['second_rule'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                          <!-- 二级权限 -->
                                          <option value="<?php echo $v['id']; ?>">|----<?php echo $v['rule_name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                                  是否显示菜单
                                    <input type="radio" name="is_show" value="1" title="是" >
                                    <input type="radio" name="is_show" value="0" title="否" checked>                                  
                                <div class="layui-inline layui-show-xs-block">
                                    <button class="layui-btn"   lay-filter="add"  lay-submit 
                                    ><i class="layui-icon" ></i>增加</button>
                                </div>
                            </form>
                        </div>
                        <div class="layui-card-header">
                            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th>
                                    <input type="checkbox" name="" lay-filter="checkall"  lay-skin="primary">
                                  </th>
                                  <th>ID</th>
                                  <th>权限规则</th>
                                  <th>权限名称</th>
                                  <th>所属分类</th>
                                  <th>操作</th>
                              </thead>
                              <tbody>
                              	<?php if(is_array($rule_list) || $rule_list instanceof \think\Collection || $rule_list instanceof \think\Paginator): $i = 0; $__LIST__ = $rule_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	                                <tr>
	                                  <td>
	                                   <input type="checkbox" name="id"  lay-skin="primary" value="<?php echo $vo['id']; ?>">
	                                  </td>
	                                  <td><?php echo $vo['id']; ?></td>
	                                  <td><?php echo $vo['module_name']; ?>/<?php echo $vo['controller_name']; ?>/<?php echo $vo['action_name']; ?></td>
	                                  <td><?php echo $vo['rule_name']; ?></td>
	                                  <td><?php echo $vo['parent_name']; ?></td>
	                                  <td class="td-manage">
                                      <a title="编辑"  onclick="xadmin.open('编辑','<?php echo url('Rule/edit',["id"=>$vo['id']]); ?>',600,400)" href="javascript:;">
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
                                  <?php echo $page; ?>
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

        form.on('submit(add)',function(data) {
            // console.log(data);
            //发异步，把数据提交给php
            $.post('<?php echo url('Rule/add'); ?>', {data:data.field}, function(data){
              if (data.code == 1) {
                layer.alert(data.msg, {
                  time: 2000,
                  icon: 6
                },
                function() {
                    // 关闭当前frame
                    // xadmin.close();
                    // 可以对父窗口进行刷新 
                    xadmin.father_reload();
                });
              } else {
                layer.alert(data.msg, {
                    icon: 5
                },
                function() {
                    // 关闭当前frame
                    // xadmin.close();
                    // 可以对父窗口进行刷新 
                    xadmin.father_reload();
                });
              }
            })  
            return false;
        });

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
              $.post("<?php echo url('Rule/del'); ?>",{ids:id},function(data) {
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

        var ids = [];//保存选中的id值,需要删除的id
        $('tbody input').each(function(index, el) {
          if ($(this).prop('checked')){
            ids.push($(this).val())
          }
          
        });

        layer.confirm('确认要删除吗？'+ ids.toString(),function(index){
          $.post("<?php echo url('Rule/del'); ?>",{ids:ids},function(data) {
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
<!--     <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script> -->
</html>