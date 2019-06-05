<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:61:"E:\www\jitu\public/../application/admin\view\Index\index.html";i:1556010418;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo config('APP_NAME'); ?>管理系统</title>
    <script src="/static/admin/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/static/admin/css/global.css" media="all">
    <link rel="stylesheet" href="/static/admin/plugins/layui/css/layui.css">
    <style>
        .layui-nav-child .layui-nav-item{padding-left: 25px;}
        .layui-nav-child li:hover{background: #009688;}
        .layui-nav-child a:hover{color:#fff;}
        #top-admin .layui-nav-more{border-color: #fff transparent transparent}
        #top-admin .layui-nav-mored{border-color: transparent transparent #fff}
    </style>
</head>

<body>
<!-- 布局容器 -->
<div class="layui-layout layui-layout-admin">
    <!-- 头部 -->
    <div class="layui-header header-demo">
        <div class="layui-main" id="admin-navbar-side" lay-filter="side">
            <!-- logo -->
            <a href="" style="color: #fff; font-size: 18px; line-height: 60px;"><strong>JT</strong> | 吉图管理系统</a>
            <!-- 水平导航 -->
            <ul class="layui-nav" lay-filter="top-nav" style="position: absolute; top: 0; right: 0; background: none;">
                <!--<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['fid'] == 0): ?>
                <li class="layui-nav-item">
                    <a href="<?php if($vo['url'] == ''): ?>javascript:;<?php else: ?><?php echo $vo['url']; endif; ?>"><?php echo $vo['name']; ?></a>
                    <?php if($vo['url'] == ''): ?>
                    <ul class="layui-nav-child">
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;if($item['fid'] == $vo['id']): ?><li><a href="<?php echo $item['url']; ?>"><?php echo $item['name']; ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>-->

                <li class="layui-nav-item" id="top-admin">
                    <a href="javascript:;" style="color: #fff;">
                        <i class="layui-icon">&#xe612;</i>
                        <?php echo $username; if($nickname): ?>[<?php echo $nickname; ?>]<?php endif; ?>
                    </a>
                    <dl class="layui-nav-child">
                        <dd class="api-add">
                            <a href="javascript:;">
                                个人信息
                            </a>
                        </dd>
                        <dd>
                            <a href="/Login/logOut">
                                退出登录
                            </a>
                        </dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

    <!-- 侧边栏 -->
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <ul class="layui-nav layui-nav-tree" lay-filter="left-nav" style="border-radius: 0;">
            </ul>
        </div>
    </div>

    <!-- 主体 -->
    <div class="layui-body">
        <!-- 顶部切换卡 -->
        <div class="layui-tab layui-tab-brief" lay-filter="top-tab" lay-allowClose="true" style="margin: 0;">
            <!--<ul class="layui-tab-title"></ul>-->
            <div class="layui-tab-content">
                <div class="layui-tab-item">
                    <iframe src="/index/welcome" style="height: 807px; border: 0;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- 底部 -->
    <div class="layui-footer" style="text-align: center; line-height: 44px;">
        <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href=""><?php echo config('COMPANY_NAME'); ?></a>.</strong> All rights reserved.
    </div>
</div>

<script type="text/javascript" src="/static/admin/plugins/layui/layui.js"></script>
<script type="text/javascript">
    layui.config({
        base: '/static/admin/js/'
    });

    layui.use(['cms'], function() {
        var cms = layui.cms('left-nav', 'top-tab', 'top-nav');
        cms.addNav(JSON.parse('<?php echo json_encode($list); ?>'), 0, 'id', 'fid', 'name', 'url');
        cms.bind(60 + 41 + 20 + 44); //头部高度 + 顶部切换卡标题高度 + 顶部切换卡内容padding + 底部高度
        cms.clickLI(0);
    });

    layui.use(['layer'], function() {
        $('.api-add').on('click', function () {
            layer.open({
                type: 2,
                area: ['80%', '80%'],
				title: '个人信息',
                maxmin: true,
                content: '/Login/changeUser'
            });
        });
        var updateTime = '<?php echo $userInfo["updateTime"]; ?>';
        if( updateTime == 0 ){
            layer.open({
                title: '初次登陆请重置密码！',
                type: 2,
                area: ['80%', '80%'],
                maxmin: true,
                closeBtn:0,
                content: '/Login/changeUser'
            });
        }else{
            var nickname = '<?php echo $userInfo["nickname"]; ?>';
            if( !nickname ){
                layer.open({
                    title: '初次登陆请补充真实姓名！',
                    type: 2,
                    area: ['80%', '80%'],
                    maxmin: true,
                    closeBtn:0,
                    content: '/Login/changeUser'
                });
            }
        }
    });
</script>
</body>
</html>