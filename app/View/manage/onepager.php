<div class="container main-container clearfix">

    <div id="response" class="col-md-12" style="display: none;">
        <div class="alert alert-info" role="alert">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
            <span id="response-text"></span>
        </div>
    </div>

    <div class="col-md-9">
        <h3>管理所有一页书</h3>
        <?php if (!empty($this->managed_ops)) { ?>
            <div class="card">
                <div class="card-body">
                    <?php $fisrt = ($this->page - 1) * $this->per + 1; ?>
                    <?php $last = $this->page * $this->per; ?>
                    <p class="visible-xs"><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>
                    <table class="table table-hover">
                        <thead>
                            <tr role="row">
                                <th>ID</th>
                                <th>标题</th>
                                <th>作者</th>
                                <th>审核</th>
                                <th>价格</th>
                                <th>类别</th>
                                <th>下载量</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->managed_ops as $op) { ?>
                                <tr id="op-<?php echo $op['id']; ?>" class="<?php echo ($op['id'] % 2 == 0) ? 'even' : 'odd'; ?>">
                                    <td data-label="ID"><?php echo $op['id']; ?></td>
                                    <td data-label="标题"><?php echo $op['title']; ?></td>
                                    <td data-label="作者"><?php echo $op['author_name']; ?></td>
                                    <td data-label="审核" id="review-status-<?php echo $op['id']; ?>"><?php if ($op['reviewed'] == 0) {
                                                                                                        echo '<span class="label label-danger">待审核</span>';
                                                                                                    } else {
                                                                                                        echo '<span class="label label-success">已审核</span>';
                                                                                                    } ?></td>
                                    <td data-label="价格"><?php echo $op['price']; ?></td>
                                    <td data-label="类别"><?php echo $op['category_name']; ?></td>
                                    <td data-label="下载量"><?php echo $op['download_count']; ?></td>
                                    <td data-label="管理"><a href="<?php echo $this->baseUrl(); ?>/portfolio/view/id/<?php echo $op['id']; ?>/" target="_blank">查看</a> | <a href="<?php echo $this->baseUrl(); ?>/portfolio/edit/id/<?php echo $op['id']; ?>/" target="_blank">编辑</a> | <a href="javascript:op_delete(<?php echo $op['id']; ?>);">删除</a><?php if ($this->user['is_staff']) { ?> | <a href="javascript:op_review(<?php echo $op['id']; ?>);">审批</a><?php } ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <p><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($this->page != 1) { ?>
                                <li>
                                    <a href="<?php echo $this->baseUrl(); ?>/user/index/page/<?php echo ($this->page - 1); ?>/" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php
                            // 输出当前页面临近的8个页面
                            $before_ellipsis = $after_ellipsis = false;
                            if ($this->total_page > 10) {
                                if ($this->page < 6) {
                                    $page_range = range(1, 9);
                                    $after_ellipsis = true;
                                } elseif ($this->page > $this->total_page - 5) {
                                    $page_range = range($this->total_page - 8, $this->total_page);
                                    $before_ellipsis = true;
                                } else {
                                    $before_ellipsis = $after_ellipsis = true;
                                    $page_range = range($this->page - 4, $this->page + 4);
                                }
                            } else {
                                $page_range = $this->page_range;
                            }
                            ?>
                            <?php if ($before_ellipsis) { ?>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <?php } ?>
                            <?php foreach ($page_range as $page) { ?>
                                <li <?php if ($this->page == $page) echo 'class="active"'; ?>><a href="<?php echo $this->baseUrl(); ?>/user/index/page/<?php echo $page; ?>/"><?php echo $page; ?></a></li>
                            <?php } ?>
                            <?php if ($after_ellipsis) { ?>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <?php } ?>
                            <?php if ($this->page != $this->total_page) { ?>
                                <li>
                                    <a href="<?php echo $this->baseUrl(); ?>/user/index/page/<?php echo ($this->page + 1); ?>/" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php } else { ?>
            <p>您还没有可供管理的一页书。</p>
        <?php } ?>
        <div class="h-100"></div>
    </div>

    <div class="col-md-3">
        <a href="<?php echo $this->baseUrl(); ?>/user/" class="btn btn-default">返回用户面板</a>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script>
    function op_delete(id) {
        url = '<?php echo $this->baseUrl(); ?>/portfolio/del/id/' + id + '/';
        row_id = '#op-' + id
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
        $('html,body').animate({
            scrollTop: 0
        }, 'slow');
    }

    function op_review(id) {
        url = '<?php echo $this->baseUrl(); ?>/portfolio/review/id/' + id + '/';
        status_id = '#review-status-' + id
        $.get(url, function(result) {
            var status = result['status'];
            var msg = result['msg'];
            $("#response-text").text(msg);
            $("#response").slideDown(200);
            if (status == 'show') {
                $(status_id).html('<span class="label label-success">已审核</span>');
            } else if (status == 'hide') {
                $(status_id).html('<span class="label label-danger">待审核</span>');
            }
            console.log(result);
        });
        $('html,body').animate({
            scrollTop: 0
        }, 'slow');
    }
</script>
<?php $this->endBlock(); ?>