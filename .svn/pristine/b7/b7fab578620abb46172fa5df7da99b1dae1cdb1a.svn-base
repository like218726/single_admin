<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:60:"E:\www\jitu\public/../application/admin\view\Banner\add.html";i:1555319982;s:51:"E:\www\jitu\application\admin\view\Public\base.html";i:1554800809;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <title><?php echo config('APP_NAME'); ?>管理后台</title>
    <link rel="stylesheet" href="/static/admin/plugins/layui/css/layui.css">
    <script type="text/javascript" src="/static/admin/plugins/laydate/laydate.js"></script>
    <script type="text/javascript" src="/static/admin/plugins/layui/layui.js"></script>
    <script src="/static/admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/admin/plugins/dataTable/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/static/admin/plugins/dataTable/dataTable.css">
    <link href="/static/admin/plugins/bootstrap/css/edit_bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/static/admin/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <script src="/static/admin/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
    <script src="/static/admin/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>	
    <block name="myCss"></block>
</head>
<body>
<div style="margin: 15px;">
    <block name="main"></block>
</div>
<block name="myScript"></block>
</body>
</html>
<style>
    .layui-form-label{
        width: 120px;
    }
    .layui-form-item .layui-input-inline{
        width: 300px;
    }
</style>
<link href="/static/admin/plugins/bootstrap/css/edit_bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/static/admin/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<script src="/static/admin/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/static/admin/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<block name="main">
    <fieldset class="layui-elem-field">
        <legend>Banner管理 - <?php echo !empty($detail['banner_id'])?'编辑':'新增'; ?></legend>
        <div class="layui-field-box">
            <form class="layui-form" action="">
                <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?>
                    <input type="hidden" name="banner_id" value="<?php echo $detail['banner_id']; ?>">
                <?php endif; ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> banner名称</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="title" maxlength="100" required value="<?php echo !empty($detail['title'])?$detail['title']:''; ?>" lay-verify="required" placeholder="请输入banner名称" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> banner图片</label>
                    <div class="layui-input-inline" style="width: 150px">
                        <input type="file" name="file" id="file" class="layui-upload-file">
                        <input type="hidden" id="pic" name="pic" value="<?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo $detail['pic']; endif; ?>">
                        <div id="show"><?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><img src="/<?php echo !empty($detail['pic'])?$detail['pic'] : ''; ?>" height="100"><?php endif; ?></div>
                    </div>
                    <div class="layui-input-inline" style="width: 400px">
                        <span style="color:red">
                            banner图片格式为jpg或png或gif。
                        </span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">添加链接</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="link" value="1" title="是" checked>
                        <input type="radio" name="link" value="2" title="否" <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo !empty($detail['url'])?'':'checked'; endif; ?>>
                        <input type="text" name="url" value="<?php echo !empty($detail['url'])?$detail['url']:''; ?>" placeholder="请输入banner点击跳转的链接~" <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo !empty($detail['url'])?'':'style="display:none"'; endif; ?> class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="admin-form">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </fieldset>
</block>
<block name="myScript">
    <script>
       layui.use('form',function(){
            var form = layui.form();
            form.on('radio',function (data) {
                if (data.value == 1){
                    $('input[name=url]').show();
                }else {
                    $('input[name=url]').hide();
                    $('input[name=url]').val('');
                }
            })
        })
    	//banner
        var host = window.location.host;
        var imageObj = $("#pic");
        var image_path = '<?php echo !empty($detail["pic"])?$detail["pic"] : ""; ?>';
        if (image_path != ''){
			$("#show").html("<img src='/<?php echo !empty($detail["pic"])?$detail["pic"] : ""; ?>' style='height:100px; margin-top:5px;' />");
        }
		$("#pic").val(image_path);
        layui.use('upload', function(){
            var options = {
                elem: '#file',
                url: '<?php echo url("upload/index"); ?>',
                ext: 'jpg|png|jpeg',
				before: function(input){
					console.log('文件上传中');
				},				
                success: function(res){ 
                    console.log(res); //上传成功返回值，必须为json格式
                    $("#show").html("<img src='http://"+host+"/"+res.image_path+"' style='height:100px; margin-top:5px;' />");
					$("#pic").val('/'+res.image_path);
                }
            };
            layui.upload(options);
        })		
    </script>
    <?php if(isset($detail['banner_id'])): ?>
        <script>
            layui.use('form',function(){
                var form = layui.form();
                form.on('submit(admin-form)', function(data){
                    var data = data.field;
                    delete(data['link']);
                    data.pic = $('#pic').val();
                    data.video = $('#video').val();
                    if(data.pic == ''){
                        parent.layer.msg('请上传图片', {
                            icon: 5,
                            shade: [0.6, '#393D49'],
                            time:1500
                        });
                        return false;
                    }
                    $.ajax({
                        type: "POST",
                        url: '/Banner/edit',
                        data: data,
                        success: function(msg){
                            if( msg.code == 1 ){
                                parent.location.reload();
                            }else{
                                parent.layer.msg(msg.msg, {
                                    icon: 5,
                                    shade: [0.6, '#393D49'],
                                    time:1500
                                });
                            }
                        }
                    });
                    return false;
                });

            });
        </script>
        <?php else: ?>
        <script>
            layui.use('form', function(){
                var form = layui.form();
                form.on('submit(admin-form)', function(data){
                    var data = data.field;
                    delete(data['link']);
                    data.pic = $('#pic').val();
                    data.video = $('#video').val();
                    if(data.pic == ''){
                        parent.layer.msg('请上传图片', {
                            icon: 5,
                            shade: [0.6, '#393D49'],
                            time:1500
                        });
                        return false;
                    }
                    $.ajax({
                        type: "POST",
                        url: '/Banner/add',
                        data: data,
                        success: function(msg){
                            if( msg.code == 1 ){
                                parent.location.reload();
                            }else{
                                parent.layer.msg(msg.msg, {
                                    icon: 5,
                                    shade: [0.6, '#393D49'],
                                    time:1500
                                });
                            }
                        }
                    });
                    return false;
                });

            });
        </script>
    <?php endif; ?>
</block>