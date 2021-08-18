<div class="container main-container clearfix">

    <div id="response" class="col-md-12" style="display: none;">
        <div class="alert alert-info" role="alert">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
            <span id="response-text"></span>
        </div>
    </div>

    <div class="col-md-9">
        <h3>管理反馈</h3>
        <?php if (!empty($this->feedbacks)) { ?>
            <div class="card">
                <div class="card-body">
                    <?php $fisrt = ($this->page - 1) * $this->per + 1; ?>
                    <?php $last = $this->page * $this->per; ?>
                    <p class="visible-xs"><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>
                    <table class="table table-hover">
                        <tbody>
                            <?php foreach ($this->feedbacks as $item) { ?>
                                <tr class="fb-<?php echo $item['id']; ?> odd">
                                    <td data-label="#"><strong class="hidden-xs">#</strong> <?php echo $item['id']; ?></td>
                                    <td data-label="提交时间"><strong class="hidden-xs">提交时间</strong> <?php echo $item['created_date']; ?></td>
                                    <td data-label="称呼"><strong class="hidden-xs">称呼</strong> <?php echo $item['name']; ?></td>
                                    <td data-label="联系方式"><strong class="hidden-xs">联系方式</strong> <?php echo empty($item['contact']) ? 'n/a' : $item['contact']; ?></td>
                                    <td data-label="状态"><strong class="hidden-xs">状态</strong> <span id="status-<?php echo $item['id']; ?>"><?php echo ($item['status']) ? '<span class="label label-success">已处理</span>' : '<span class="label label-danger">未处理</span>'; ?></span></td>
                                    <td data-label="管理"><strong class="hidden-xs">管理</strong> <a href="javascript:fb_mark(<?php echo $item['id']; ?>);">标记</a> | <a href="javascript:fb_delete(<?php echo $item['id']; ?>);">删除</a></td>
                                </tr>
                                <tr class="fb-<?php echo $item['id']; ?> even">
                                    <td style="text-align: left;"><strong>内容</strong></td>
                                    <td colspan="5" style="text-align: left;"><?php echo $item['message']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <p><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($this->page != 1) { ?>
                                <li>
                                    <a href="<?php echo $this->baseUrl(); ?>/manage/tutorial/page/<?php echo ($this->page - 1); ?>/" aria-label="Previous">
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
                                <li <?php if ($this->page == $page) echo 'class="active"'; ?>><a href="<?php echo $this->baseUrl(); ?>/manage/tutorial/page/<?php echo $page; ?>/"><?php echo $page; ?></a></li>
                            <?php } ?>
                            <?php if ($after_ellipsis) { ?>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <?php } ?>
                            <?php if ($this->page != $this->total_page) { ?>
                                <li>
                                    <a href="<?php echo $this->baseUrl(); ?>/manage/tutorial/page/<?php echo ($this->page + 1); ?>/" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php } else { ?>
            <p>还没有反馈记录。</p>
        <?php } ?>
        <div class="h-100"></div>
    </div>

    <div class="col-md-3">
        <a href="<?php echo $this->baseUrl(); ?>/user/" class="btn btn-default">返回用户面板</a>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script>
    function fb_delete(id) {
        url = '<?php echo $this->baseUrl(); ?>/manage/delfb/id/' + id + '/';
        row_class = '.fb-' + id
        $.get(url, function(result) {
            var status = result['status'];
            var msg = result['msg'];
            $("#response-text").text(msg);
            $("#response").slideDown(200);
            if (status == 'success') {
                $(row_class).hide(400);
            }
            console.log(result);
        });
        $('html,body').animate({
            scrollTop: 0
        }, 'slow');
    }

    function fb_mark(id) {
        url = '<?php echo $this->baseUrl(); ?>/manage/markfb/id/' + id + '/';
        status_id = '#status-' + id
        $.get(url, function(result) {
            var status = result['status'];
            var msg = result['msg'];
            $("#response-text").text(msg);
            $("#response").slideDown(200);
            if (status == 1) {
                $(status_id).html('<span class="label label-success">已处理</span>');
            } else if (status == 0) {
                $(status_id).html('<span class="label label-danger">未处理</span>');
            }
            console.log(result);
        });
        $('html,body').animate({
            scrollTop: 0
        }, 'slow');
    }
</script>
<?php $this->endBlock(); ?>