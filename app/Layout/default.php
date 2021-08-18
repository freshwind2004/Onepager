<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $this->title; ?> - 一页书/onepager.work</title>
    <link rel="icon" href="<?php echo $this->baseUrl(); ?>/img/favicon.png" type="image/x-icon">

    <!-- Bootstrap -->
    <link href="<?php echo $this->baseUrl(); ?>/css/bootstrap.min.css" rel="stylesheet">

    <!-- main css -->
    <link href="<?php echo $this->baseUrl(); ?>/css/style.css" rel="stylesheet">

    <!-- modernizr -->
    <script src="<?php echo $this->baseUrl(); ?>/js/modernizr.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <div class="container-fluid">
        <!-- box header -->
        <header class="box-header">
            <a href="<?php echo $this->baseUrl(); ?>/"><img class="box-logo" src="<?php echo $this->baseUrl(); ?>/img/logo.png" alt="Logo"></a>
            <!-- box-nav -->
            <a href="<?php echo $this->baseUrl(); ?>/portfolio/">浏览一页书</a>
            <a href="<?php echo $this->baseUrl(); ?>/tutorial/">教程</a>
            <a href="<?php echo $this->baseUrl(); ?>/resource/">资源</a>
            <?php if ($this->is_authenticated) { ?>
            <a class="user-nav-btn" href="<?php echo $this->baseUrl(); ?>/user/" title="转到用户面板"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
            <?php } ?>
            <a class="box-primary-nav-trigger" href="javascript:;">
                <span class="box-menu-icon"></span>
            </a>
            <!-- box-primary-nav-trigger -->
        </header>
        <!-- end box header -->


        <!-- nav -->
        <nav>
            <ul class="box-primary-nav">
                <li class="box-label">导航菜单</li>

                <li><span class="glyphicon glyphicon-home" aria-hidden="true"></span><a href="<?php echo $this->baseUrl(); ?>/">首页</a></li>
                <li><span class="glyphicon glyphicon-th" aria-hidden="true"></span><a href="<?php echo $this->baseUrl(); ?>/portfolio/">浏览一页书</a></li>

                <?php if ($this->is_authenticated) { ?>
                    <li><a href="<?php echo $this->baseUrl(); ?>/user/">用户面板(<?php echo $this->username; ?>)</a></li>
                <?php } else { ?>
                    <li><a href="<?php echo $this->baseUrl(); ?>/user/login/">登录</a> / <a href="<?php echo $this->baseUrl(); ?>/user/register">注册</a></li>
                <?php } ?>

                <li><a href="<?php echo $this->baseUrl(); ?>/tutorial">教程</a> / <a href="<?php echo $this->baseUrl(); ?>/resource/">资源</a></li>
                <li><a href="<?php echo $this->baseUrl(); ?>/page/about/">关于我们</a></li>

                <li class="box-label">快速链接</li>

                <li class="box-social"><a href="<?php echo $this->baseUrl(); ?>/" title="首页"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                <li class="box-social"><a href="<?php echo $this->baseUrl(); ?>/page/feedback/" title="反馈"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a></li>
                <li class="box-social"><a href="<?php echo $this->baseUrl(); ?>/page/about/" title="关于我们"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a></li>
            </ul>
        </nav>
        <!-- end nav -->
    </div>

    <?php $this->insertBlock('jumbotron'); ?>

    <!-- main-content -->
    <?php echo $this->_layout->content; ?>
    <!-- end main-content -->

    <!-- footer -->
    <footer>
        <div class="container">
            <p class="copyright">©2021 onepager.work™ the best printable one-pager collection. 「一页书」思维可视化工具.</p>
            <p>
                备案号：京ICP 20210101号。<br>
                本站部分静态图片来自<a href="https://unsplash.com/" target="_blank" rel="noopener noreferrer">unsplash</a>。本站内容除特别声明的部分之外，均遵循<a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener noreferrer">创作共用 署名-禁止演绎 4.0 国际协议 </a>。<br>
                这意味着您可以在不修改(演绎)内容本身的前提下免费为商业或个人目的使用这些内容。<br>
                本站发布原创内容和用户共享的内容，如共享的内容侵犯了您的著作权，请<a href="<?php echo $this->baseUrl(); ?>/page/feedback/" target="_blank" rel="noopener noreferrer">联系我们</a>。<br>
                本站可能会在不通知您的前提下修改版权授权协议，自新协议发布之日起您需要根据新的约定使用本站内容。<br>
                <!-- 运营方：武汉智果科技有限公司。<br> -->
            </p>
        </div>
    </footer>
    <!-- end footer -->

    <!-- back to top -->
    <a href="#0" class="cd-top"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></a>
    <!-- end back to top -->



    <!-- jQuery -->
    <script src="<?php echo $this->baseUrl(); ?>/js/jquery-2.1.1.js"></script>
    <!--  plugins -->
    <script src="<?php echo $this->baseUrl(); ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo $this->baseUrl(); ?>/js/menu.js"></script>
    <script src="<?php echo $this->baseUrl(); ?>/js/animated-headline.js"></script>
    <?php $this->insertBlock('footer_jscode'); ?>

    <!--  custom script -->
    <script src="<?php echo $this->baseUrl(); ?>/js/custom.js"></script>

    <!--  analytics  -->
    <script>

    </script>

</body>

</html>