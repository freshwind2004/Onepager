<div class="container main-container clearfix">
    <div class="col-md-12">
        <h2>添加教程</h2>
        <p>欢迎提交新的教程。带 * (星号)的为必填项目。</p>
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
                    <input type="text" class="form-control" id="title" name="title" placeholder="请输入教程标题" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="category_id">类别 *</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <?php foreach ($this->categories as $category) { ?>
                            <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="content">内容 *</label>
                    <textarea name="content" id="content" class="form-control" rows="10"></textarea>
                </div>
                <div class="form-group col-md-12">
                    <label for="attachment">预览图(可选)</label>
                    <input type="file" name="attachment_image" id="attachment_image">
                </div>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
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
</script>
<?php $this->endBlock(); ?>