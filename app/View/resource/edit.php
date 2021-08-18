<div class="container main-container clearfix">
    <div class="col-md-12">
        <h2>编辑资源</h2>
        <p>请在此编辑资源。带 * (星号)的为必填项目。</p>
        <?php if ($this->res_msg) { ?>
            <div class="alert alert-info" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                <span class="sr-only">Info:</span>
                <?php echo $this->res_msg; ?>
            </div>
        <?php } ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="title">标题 * </label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $this->resource['title']; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="content">内容 *</label>
                    <textarea name="content" id="content" class="form-control" rows="10">
                    <?php echo $this->resource['content']; ?>
                </textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="attachment_image">预览图(可选)</label>
                    <input type="file" name="attachment_image" id="attachment_image">
                    <div class="h-10"></div>
                    <div class="preview">
                        <img src="<?php echo $this->resource['preview_path']; ?>" width="200" alt="">
                    </div>
                    <?php if ($this->resource['preview_path'] != TUTORIAL_DEFAULT_PREVIEW) { ?>
                        <a href="javascript:preview_delete(<?php echo $this->resource['id']; ?>);" class="btn btn-xs btn-danger">移除预览图</a>
                        <p id="response-text" class="text-info"></p>
                    <?php } ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">修改</button>
        </form>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script>
    var editor = CKEDITOR.replace('content', {
        language: 'zh-cn',
        toolbar: [
            ['Source', 'Paste', 'PasteText'],
            ['Undo', 'Redo'],
            ['RemoveFormat', 'Bold', 'Italic', 'Underline', 'Strike'],
            ['NumberedLisst', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustfyBlock'],
            ['Image', 'Table', 'Link', 'Unlink', 'Anchor', 'HorizontalRule'],
            ['SpecialChar'],
            ['Styles', 'Format', 'Font', 'FontSize'],
            ['Maximize']
        ],
        extraPlugins: 'notification'
    });
    editor.on('required', function(evt) {
        editor.showNotification('内容不能为空.', 'warning');
        evt.cancel();
    });

    function preview_delete(id) {
        url = '<?php echo $this->baseUrl(); ?>/resource/repre/id/' + id + '/';
        $.get(url, function(result) {
            var status = result['status'];
            var msg = result['msg'];
            $("#response-text").text(msg);
            if (status == 'success') {
                $('.preview').hide(400);
            }
            console.log(result);
        });
        $('html,body').animate({scrollTop:0},'slow');
    }
</script>
<?php $this->endBlock(); ?>