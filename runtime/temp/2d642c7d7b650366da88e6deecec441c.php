<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:64:"E:\www\jitu\public/../application/admin\view\ShopUser\index.html";i:1555382084;s:51:"E:\www\jitu\application\admin\view\Public\base.html";i:1554800809;}*/ ?>
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
	.mysearch { font-weight: normal;}
</style>
<block name="main">
    <fieldset class="layui-elem-field">
        <legend>员工信息</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="form-admin-add" action="">          	
                <div class="layui-inline">
                    <label class="mysearch">员工姓名：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="nickname" value="" placeholder="请填写员工姓名" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>				
                <div class="layui-inline">
                    <label class="mysearch">归属区域：</label>
                    <div class="layui-input-inline">
                    	<select name="district_id">
                    		<option value="">请选择</option>
							<?php if(is_array($district_info) || $district_info instanceof \think\Collection || $district_info instanceof \think\Paginator): if( count($district_info)==0 ) : echo "" ;else: foreach($district_info as $key=>$vo): ?>
								<option value="<?php echo $key; ?>"><?php echo $vo; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select>
                    </div>
                </div>	
                <div class="layui-inline">
                    <label class="mysearch">服务部：</label>
                    <div class="layui-input-inline">
                        <select name="shop_id">
                    		<option value="">请选择</option>
							<?php if(is_array($shop_info) || $shop_info instanceof \think\Collection || $shop_info instanceof \think\Paginator): if( count($shop_info)==0 ) : echo "" ;else: foreach($shop_info as $key=>$vo): ?>
								<option value="<?php echo $key; ?>"><?php echo $vo; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select>
                    </div>
                </div>									
                <div class="layui-inline">
                    <span class="layui-btn sub">查询</span>
					<span class="layui-btn resetform layui-btn-primary">重置</span>
                </div>
            </div>
            </form> 
			<span class="layui-btn layui-btn-normal api-add"><i class="layui-icon">&#xe608;</i> 新增</span>
            <table class="layui-table" id="list-admin" lay-even>
                <thead>
                <tr>
                    <th>序号</th>
                    <th>员工姓名</th>
                    <th>员工账号</th>
					<th>员工电话</th>
					<th>状态</th>
					<th>服务部</th>
					<th>归属区域</th>
					<th>最后登录时间</th>
                    <th>操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </fieldset>
</block>
<block name="myScript">
    <script>
  
      	$(".resetform").click(function(){
	    	$('#form-admin-add')[0].reset();
    	}) 
    	
		/**
		 * 
		 * @param {Object} obj
		 * 
		 */
        function clearSymbol(obj) {
            obj.value = obj.value.replace(/[%]/g,""); //清除"%"特殊字符
        }
		
        layui.use(['layer', 'form'], function() {
            $(document).on('click', '.confirm', function () {
                var ownObj = $(this);
                layer.confirm(ownObj.attr('data-info'), {
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

            $('.api-add').on('click', function () {
                layer.open({
                    type: 2,
                    area: ['80%', '80%'],
                    maxmin: true,
                    content: '<?php echo url("add"); ?>'
                });
            });

            $(document).on('click', '.edit', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
                    area: ['80%', '80%'],
                    maxmin: true,
                    content: ownObj.attr('data-url')+'?id='+ownObj.attr('data-id')
                });
            });

            var myFun = function (query) {
                query = query || '';
                return $('#list-admin').DataTable({
                    dom: 'rt<"bottom"ifpl><"clear">',
                    ordering: false,
                    autoWidth: false,
                    searching:false,
                    serverSide: true,
                    ajax: {
                        url:'<?php echo url("ajaxGetIndex"); ?>' + query,
                        type: 'GET',
                        dataSrc: function ( json ) {
                            if( json.code == 0 ){
                                parent.layer.msg(json.msg, {
                                    icon: 5,
                                    shade: [0.6, '#393D49'],
                                    time:1500
                                });
                            }else{
                                return json.data;
                            }
                        }
                    },
                    columnDefs:[
                        {
                            "targets":-1,
                            "render":function(data, type, row){
                                var returnStr = '';
								returnStr += '<span class="layui-btn layui-btn-small edit layui-btn-normal" ' +
                                    'data-id="' + row.id +'" data-url="<?php echo url('edit'); ?>"><i class="layui-icon">&#xe642;</i></span>'
                                returnStr += '<span class="layui-btn layui-btn-small layui-btn-danger confirm" ' +
                                    'data-id="' + row.id +'" data-info="你确定更改该状态吗？" data-url="<?php echo url('status'); ?>"><i class="layui-icon">&#xe644;</i></span>';
                                return returnStr;
                            }
                        }
                    ],
                    iDisplayLength : 20,
                    aLengthMenu : [20, 30, 50, 100],
                    columns: [
                        {"data": "id"},
                        {"data": "nickname"},
                        {"data": "username"},
                        {"data": "mobile"},
						{"data": "status"},
						{"data": "shop_name"},
                        {"data": "district_name"},
						{"data": "last_login"},
                        {"data": null }
                    ]
                });
            };
            var myTable = myFun();
            $('.sub').on("click", function(){
                myTable.destroy();
                myTable = myFun('?'+ $('#form-admin-add').serialize());
            });
        });


        //重新加载当前页
        function pageReload(page){  //page必需为数字，字符串的数字不可用 例"1"
            var oTable = $('#list-admin').dataTable();
            //window.location.reload();   //在datatable配置中加上bStateSave:true，刷新dataTable后会仍然保留在当前页。
            oTable.fnPageChange(page); //跳转到指定页
            layer.closeAll(); //疯狂模式，关闭所有层
        }

    </script>
</block>