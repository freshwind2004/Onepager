<div class="container main-container clearfix">
    <div class="col-md-12">
        <h2>提交一页书</h2>
        <p>欢迎提交新的一页书。带 * (星号)的为必填项目。</p>
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
                    <label for="title">标题 *   </label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="请输入一页书标题" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="subtitle">副标题 *</label>
                    <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="一页书的一句话简介">
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
                <div class="form-group col-md-4">
                <label for="price">价格 *</label>
                    <select class="form-control" name="price" id="price" required>
                    <?php foreach (PRICE_RANGE as $op) { ?>
                        <option value="<?php echo $op; ?>"><?php echo ($op == 0)? '免费': $op . ' 个信用点'; ?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                <label for="version">版本号 *</label>
                    <input type="text" class="form-control" id="version" name="version" value="1.0" required>
                </div>                      
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                <label for="description">详细介绍 *</label>
                <textarea name="description" id="description" class="form-control" rows="10"></textarea>
                </div>
                <div class="form-group col-md-12">
                <label for="copyright">版权协议 *</label>
                    <input type="text" class="form-control" id="copyright" name="copyright" value="创作共用 署名-禁止演绎 4.0 国际 https://creativecommons.org/licenses/by/4.0/" required>
                </div>                      
            </div>
            <div class="row">
            <div class="form-group col-md-4">
                    <label for="attachment">附件 *</label>
                    <input type="file" name="attachment_file" id="attachment_file" required>
                </div>                     
                <div class="form-group col-md-4">
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
    var editor = CKEDITOR.replace( 'description', {
        language: 'zh-cn',
        toolbar: [
            ['Source', 'Paste', 'PasteText'],
            ['Undo','Redo'],
            ['RemoveFormat','Bold','Italic','Underline','Strike'],
            ['NumberedLisst','BulletedList','JustifyLeft','JustifyCenter','JustifyRight','JustfyBlock'],
            ['Image','Table','Link','Unlink','Anchor','HorizontalRule'],
            ['SpecialChar'],
            ['Styles','Format','Font','FontSize'],
            ['Maximize']
        ],
        extraPlugins: 'notification'
    });
    editor.on( 'required', function( evt ) {
        editor.showNotification( '内容不能为空.', 'warning' );
        evt.cancel();
    } ); 
</script>
<?php $this->endBlock(); ?>