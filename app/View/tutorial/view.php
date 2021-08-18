<div class="container main-container clearfix">

    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->baseUrl(); ?>/">首页</a></li>
            <li><a href="<?php echo $this->baseUrl(); ?>/tutorial/">所有教程</a></li>
            <li class="active"><?php echo $this->tutorial['title']; ?></li>
        </ol>

        <?php if ($this->res_msg) { ?>
            <div class="alert alert-info" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                <span class="sr-only">Info:</span>
                <?php echo $this->res_msg; ?>
            </div>
        <?php } ?>

    </div>

    <div class="col-md-12">
        <img src="<?php echo $this->baseUrl(); ?><?php echo $this->tutorial['preview_path']; ?>" alt="<?php echo $this->tutorial['title']; ?>" class="fullwidth" />
        <div class="h-30"></div>
    </div>

    <div class="col-md-8 col-md-offset-2">
        <h1 class="text-uppercase">
            <?php echo $this->tutorial['title']; ?>
        </h1>
        <p><span class="label label-primary"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span>分类</span> <span class="text-primary"><strong><?php echo $this->tutorial['category_name']; ?></strong></span> <span class="label label-primary"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>作者</span> <span class="text-primary"><strong><?php echo $this->tutorial['author_name']; ?></strong></span></p>
        <div class="h-30"></div>
    </div>

    <div class="col-md-8 col-md-offset-2 content">
        <?php echo $this->tutorial['content']; ?>
        <div class="h-30"></div>
    </div>

    <div class="col-md-8 col-md-offset-2 ">
        <p class="highlight">
            <span class="glyphicon glyphicon-time" aria-hidden="true"></span> 创建 <?php echo $this->tutorial['created_date']; ?>
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 更新 <?php echo $this->tutorial['updated_date']; ?>
        </p>
        <?php if ($this->authorised) { ?>
            <p>
                <a href="<?php echo $this->baseUrl(); ?>/tutorial/edit/id/<?php echo $this->tutorial['id']; ?>/" class="btn btn-primary">编辑</a>
                <a href="javascript:tutorial_delete(<?php echo $this->tutorial['id']; ?>);" class="btn btn-danger">删除</a>
            </p>
        <?php } ?>
    </div>

</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script>
    function tutorial_delete(id) {
        url = '<?php echo $this->baseUrl(); ?>/tutorial/del/id/' + id + '/';
        $.get(url, function(result) {
            var status = result['status'];
            if (status == 'success') {
                window.location = '<?php echo $this->baseUrl(); ?>/tutorial/';
            }
            console.log(result);
        });
    }
</script>
<?php $this->endBlock(); ?>