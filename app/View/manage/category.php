<div class="container main-container clearfix">

    <?php if ($this->res_msg) { ?>
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                <span class="sr-only">info:</span>
                <?php echo $this->res_msg; ?>
            </div>
        </div>
    <?php } ?>
    <div id="response" class="col-md-12" style="display: none;">
        <div class="alert alert-info" role="alert">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
            <span id="response-text"></span>
        </div>
    </div>

    <div class="col-md-9">
        <h3>分类管理</h3>
        <?php if (!empty($this->categories)) { ?>
            <div class="card">
                <div class="card-body">
                    <p><small>总计 <?php echo $this->total_count; ?> 项</small></p>

                    <table class="table table-hover">
                        <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>名称</th>
                                <th>URL</th>
                                <th>描述</th>
                                <th>统计</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($this->categories as $item) { ?>
                                <tr id="cat-<?php echo $item['id']; ?>" class="<?php echo ($item['id'] % 2 == 0) ? 'even' : 'odd'; ?>">
                                    <td data-label="#"><?php echo $i; ?></td>
                                    <td data-label="名称"><?php echo $item['name']; ?></td>
                                    <td data-label="URL"><?php echo $item['url']; ?></td>
                                    <td data-label="描述"><?php echo $item['description']; ?></td>
                                    <td data-label="统计"><?php echo $item['count']; ?> 篇</td>
                                    <td data-label="管理"><button type="button" class="btn-link" data-toggle="modal" data-target="#delWarning" data-id="<?php echo $item['id']; ?>" data-name="<?php echo $item['name']; ?>" data-count="<?php echo $item['count']; ?>">删除</button></td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>

                    <!-- Modal -->
                    <div class="modal fade" id="delWarning" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modalLabel">删除 <strong><span class="categoryName"></span></strong> 分类?</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <span class="label label-danger">警告</span> 删除分类会导致分类下的内容被移除！请谨慎操作。<br>
                                        <span class="label label-warning">统计</span> 分类 <strong><span class="categoryName"></span></strong> 下共有 <span class="categoryCount"></span> 篇内容。
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                    <button type="button" class="confirmDelete btn btn-danger" data-dismiss="modal">确认删除</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        <?php } else { ?>
            <p>还没有分类。</p>
        <?php } ?>

        <h3>添加分类</h3>
        <form action="" method="post">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">名称</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="分类名称" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="url">URL(小写英文)</label>
                    <input type="text" class="form-control" id="url" name="url" placeholder="business" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="description">描述</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="商业相关">
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="提交">
        </form>
        <div class="h-100"></div>
    </div>

    <div class="col-md-3">
    <a href="<?php echo $this->baseUrl(); ?>/user/" class="btn btn-default">返回用户面板</a>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script>
    $('#delWarning').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var count = button.data('count');
        $('.categoryName').text(name);
        $('.categoryCount').text(count);
        $('.confirmDelete').click(function() {
            url = '<?php echo $this->baseUrl(); ?>/manage/delcate/id/' + id + '/';
            row_id = '#cat-' + id
            $.get(url, function(result) {
                var status = result['status'];
                var msg = result['msg'];
                $("#response-text").text(msg);
                $("#response").slideDown(200);
                if (status == 'success') {
                    $(row_id).hide(400);
                }
                console.log(result);
            });
            $('html,body').animate({scrollTop:0},'slow');
        })
    })
</script>
<?php $this->endBlock(); ?>