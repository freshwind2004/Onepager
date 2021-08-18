<div class="container main-container clearfix">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="<?php echo $this->baseUrl(); ?>/">首页</a></li>
                <li><a href="<?php echo $this->baseUrl(); ?>/resource/">所有资源</a></li>
                <li class="active"><?php echo $this->resource['title']; ?></li>
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
            <img src="<?php echo $this->baseUrl(); ?><?php echo $this->resource['preview_path']; ?>" alt="<?php echo $this->resource['title']; ?>" class="fullwidth" />
            <div class="h-30"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1 class="text-uppercase">
                <?php echo $this->resource['title']; ?>
            </h1>
            <div class="h-30"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-2 content">
            <?php echo $this->resource['content']; ?>
            <div class="h-30"></div>
        </div>

        <div class="col-md-4">
            <ul class="cat-ul">
                <li><span class="glyphicon glyphicon-time" aria-hidden="true"></span> 创建 <?php echo $this->resource['created_date']; ?></li>
                <li><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 更新 <?php echo $this->resource['updated_date']; ?></li>
                <li><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 作者 <?php echo $this->resource['author_name']; ?></li>
            </ul>
            <div class="h-30"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php if ($this->authorised) { ?>
                <p>
                    <a href="<?php echo $this->baseUrl(); ?>/resource/edit/id/<?php echo $this->resource['id']; ?>/" class="btn btn-primary">编辑</a>
                    <a href="javascript:tu_delete(<?php echo $this->resource['id']; ?>);" class="btn btn-danger">删除</a>
                </p>
            <?php } ?>

        </div>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script>
    function tu_delete(id) {
        url = '<?php echo $this->baseUrl(); ?>/resource/del/id/' + id + '/';
        $.get(url, function(result) {
            var status = result['status'];
            if (status == 'success') {
                window.location = '<?php echo $this->baseUrl(); ?>/resource/';
            }
            console.log(result);
        });
    }
</script>
<?php $this->endBlock(); ?>