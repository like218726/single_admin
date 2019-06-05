<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:62:"E:\www\jitu\public/../application/admin\view\Banner\index.html";i:1556012639;s:51:"E:\www\jitu\application\admin\view\Public\base.html";i:1554800809;}*/ ?>
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
<block name="main">
    <fieldset class="layui-elem-field">
        <legend>Banner管理</legend>
        <div class="layui-field-box">
        	<span class="layui-btn layui-btn-normal api-add" data-title="新增Banner"><i class="layui-icon">&#xe608;</i> 新增</span>
			<span class="layui-btn layui-btn-normal on">上架</span>
			<span class="layui-btn layui-btn-normal off">下架</span>			
            <table class="layui-table" lay-even>
                <thead>
                <tr>
                    <th style="text-align:center;">
						<input type="checkbox" onclick="$('input[name*=\'id\']').prop('checked', this.checked);">
					</th>
					<th>序号</th>
					<th>banner图片</th>
					<th>banner名称</th>
					<th>banner链接</th>
					<th>上传时间</th>
					<th>banner状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr>
                    	<td class="text-center"><input type="checkbox" name="id[]" value="<?php echo $vo['banner_id']; ?>"> </td>
                        <td><?php echo $vo['banner_id']; ?></td>
						<td><img src="/<?php echo $vo['pic']; ?>" alt="<?php echo $vo['title']; ?>" title="<?php echo $vo['title']; ?>" style="height:50px;"></td>
						<td><?php echo $vo['title']; ?></td>
						<td><?php echo $vo['url']; ?></td>
						<td><?php echo $vo['addtime']; ?></td>
						<td><?php echo $vo['status']; ?></td>
						<td>
							<span class="layui-btn layui-btn-small edit layui-btn-normal" data-title="编辑Banner" data-id="<?php echo $vo['banner_id']; ?>" data-url="<?php echo url('edit'); ?>"><i class="layui-icon">&#xe642;</i></span>
                            <span class="layui-btn layui-btn-small layui-btn-danger confirm" data-title="删除Banner" data-id="<?php echo $vo['banner_id']; ?>" data-info="你确定删除吗？" data-url="<?php echo url('del'); ?>"><i class="layui-icon">&#xe640;</i></span>
						</td>	
                    </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </fieldset>
</block>
<block name="myscript">
	<script>
		layui.use(['layer', 'form'], function() {
			$(document).on('click', '.api-add', function () {	
				var ownObj = $(this);
                layer.open({
                    type: 2,
					title: ownObj.attr('data-title'),
                    area: ['80%', '80%'],
                    maxmin: true,
                    content: '<?php echo url("add"); ?>'
                });
            });	
			
			$(".on").click(function(){
				layer.confirm('你确定要上架吗?',{title:'Banner上架'},function(){
					var checkObj = $("input[name='id[]']:checked");
					var ids=''
					var num = checkObj.length; 
					checkObj.each(function(){
						ids += $(this).val()+',';
					});	
					console.log(ids)
					console.log(num);
					if (num<=0) {
						layer.alert('操作不能为空!',{icon:2});	
						return false;
					}	
					$.post("<?php echo url('batchOn'); ?>",{'ids':ids},function(data){
						if (data.code == 1) {
							location.reload();
						} else {
	                        layer.msg(data.msg, {
	                            icon: 5,
	                            shade: [0.6, '#393D49'],
	                            time:1500
	                        });							
						}
					},'json');							
				})
			})
		
			$(".off").click(function(){
				layer.confirm('你确定要下架吗?',{title:'Banner下架'},function(){
					var checkObj = $("input[name='id[]']:checked");
					var ids=''
					var num = checkObj.length; 
					checkObj.each(function(){
						ids += $(this).val()+',';
					});	
					if (num<=0) {
						layer.alert('操作不能为空!',{icon:2});	
						return false;
					}	
					$.post("<?php echo url('batchClose'); ?>",{'ids':ids},function(data){
						if (data.code == 1) {
							location.reload();
						} else {
	                        layer.msg(data.msg, {
	                            icon: 5,
	                            shade: [0.6, '#393D49'],
	                            time:1500
	                        });							
						}
					},'json');					
				});
			})
			
            $(document).on('click', '.edit', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
					title: ownObj.attr('data-title'),
                    area: ['80%', '80%'],
                    maxmin: true,
                    content: ownObj.attr('data-url')+'?id='+ownObj.attr('data-id')
                });
            });	
			
            $(document).on('click', '.confirm', function () {
                var ownObj = $(this);
                layer.confirm(ownObj.attr('data-info'), {
					title: ownObj.attr('data-title'),
                    btn: ['确定','取消'] //按钮
                }, function(){
                    $.ajax({
                        type: "POST",
                        url: ownObj.attr('data-url'),
                        data: {id:ownObj.attr('data-id')},
                        success: function(msg){
                            if( msg.code == 1 ){
                                location.reload();
                            }else{
                                layer.msg(msg.msg, {
                                    icon: 5,
                                    shade: [0.6, '#393D49'],
                                    time:1500
                                });
                            }
                        }
                    });
                });
            });										
		})	
	</script>
</block>
