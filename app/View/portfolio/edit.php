<div class="container main-container clearfix">
    <div class="col-md-12">
        <h2>编辑一页书</h2>
        <p>请在此编辑一页书。带 * (星号)的为必填项目。</p>
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
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $this->onepager['title']; ?>" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="subtitle">副标题 *</label>
                    <input type="text" class="form-control" id="subtitle" name="subtitle" value="<?php echo $this->onepager['subtitle']; ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="category_id">类别 *</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <?php foreach ($this->categories as $category) { ?>
                            <option value="<?php echo $category['id']; ?>" <?php if ($this->onepager['category_id'] == $category['id']) { echo 'selected'; } ?>><?php echo $category['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="price">价格 *</label>
                    <select class="form-control" name="price" id="price" required>
                    <?php foreach (PRICE_RANGE as $op) { ?>
                        <option value="<?php echo $op; ?>"<?php if ($this->onepager['price'] == $op) { echo ' selected'; } ?>><?php echo ($op == 0)? '免费': $op . ' 个信用点'; ?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="version">版本号 *</label>
                    <input type="text" class="form-control" id="version" name="version" value="<?php echo $this->onepager['version']; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="description">详细介绍 *</label>
                    <textarea name="description" id="description" class="form-control" rows="10">
                    <?php echo $this->onepager['description']; ?>
                </textarea>
                </div>
                <div class="form-group col-md-12">
                    <label for="copyright">版权协议 *</label>
                    <input type="text" class="form-control" id="copyright" name="copyright" value="<?php echo $this->onepager['copyright']; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="attachment_file">附件 *</label>
                    <input type="file" name="attachment_file" id="attachment_file">
                    <div class="h-10"></div>
                    <label>已上传文件：</label>
                    <p><?php echo $this->onepager['attachment_filename']; ?></p>
                </div>
                <div class="form-group col-md-4">
                    <label for="attachment_image">预览图(可选)</label>
                    <input type="file" name="attachment_image" id="attachment_image">
                    <div class="h-10"></div>
                    <div class="preview">
                        <img src="<?php echo $this->onepager['preview_path']; ?>" width="200" alt="">
                    </div>
                    <?php if ($this->onepager['preview_path'] != ONEPAGER_DEFAULT_PREVIEW) { ?>
                        <a href="javascript:preview_delete(<?php echo $this->onepager['id']; ?>);" class="btn btn-xs btn-danger">移除预览图</a>
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
    var editor = CKEDITOR.replace('description', {
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
        url = '<?php echo $this->baseUrl(); ?>/portfolio/repre/id/' + id + '/';
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