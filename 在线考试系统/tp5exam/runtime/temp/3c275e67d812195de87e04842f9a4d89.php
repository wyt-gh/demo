<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:83:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\role\edit.html";i:1603334214;s:75:"D:\phpstudy\PHPTutorial\WWW\tp5exam\application\admin\view\Common\head.html";i:1602636435;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
<!--     <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" /> -->
    <link rel="stylesheet" href="/static/admin/css/font.css">
<link rel="stylesheet" href="/static/admin/css/xadmin.css">
<script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form action="" method="post" class="layui-form layui-form-pane">
                <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
                <div class="layui-form-item">
                    <label for="name" class="layui-form-label">
                        <span class="x-red">*</span>角色名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="role_name" required="" lay-verify="required"
                        autocomplete="off" class="layui-input" value="<?php echo $info['role_name']; ?>">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">
                        拥有权限
                    </label>
                    <table  class="layui-table layui-input-block">
                        <tbody>
                           <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <tr>
                            <?php if($vo['parent_id'] == '0'): ?>
                            <td>            
                                <input type="checkbox" name="like1[write]" lay-skin="primary" lay-filter="father" title="<?php echo $vo['rule_name']; ?>" value="<?php echo $vo['id']; ?>"> 
                            </td> 
                            <td>
                                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;if(in_array(($v['controller_name']), is_array($vo['controller_name'])?$vo['controller_name']:explode(',',$vo['controller_name']))): ?>
                                <input name="rule_id[]" lay-skin="primary" type="checkbox" title="<?php echo $v['rule_name']; ?>" value="<?php echo $v['id']; ?>" <?php if(in_array(($v['id']), is_array($info['rule_id'])?$info['rule_id']:explode(',',$info['rule_id']))): ?>checked<?php endif; ?>> 
                                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </td>
                            <?php endif; ?>
                            </tr>
                          <?php endforeach; endif; else: echo "" ;endif; ?>  
                        </tbody>
                    </table>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="desc" class="layui-form-label">
                        描述
                    </label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" id="desc" name="content" class="layui-textarea"><?php echo $info['content']; ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn" lay-submit="" lay-filter="edit">增加</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
          var form = layui.form
          ,layer = layui.layer;
        
          //自定义验证规则
          form.verify({
            nikename: function(value){
              if(value.length < 5){
                return '昵称至少得5个字符啊';
              }
            }
            ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,repass: function(value){
                if($('#L_pass').val()!=$('#L_repass').val()){
                    return '两次密码不一致';
                }
            }
          });

          //监听提交
        form.on('submit(edit)', function(data){
            console.log(data);
           
            //发异步，把数据提交给php
            $.post("<?php echo url('Role/edit'); ?>",{data:filterData(data.field)}, function(data) {
              if (data.code == 1) {
                layer.alert(data.msg, {
                    icon: 6
                },
                function(data) {
                    console.log(data);
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
        //全选
        form.on('checkbox(father)', function(data){

            if(data.elem.checked){
                $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                form.render(); 
            }else{
               $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                form.render();  
            }
        });
          
          
        });
    </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
  </body>

</html>