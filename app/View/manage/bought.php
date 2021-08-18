<div class="container main-container clearfix">

    <div class="col-md-9">
        <h3>我的购买记录</h3>
        <?php if (!empty($this->bought_ops)) { ?>
            <div class="card">
                <div class="card-body">
                    <?php $fisrt = ($this->page - 1) * $this->per + 1; ?>
                    <?php $last = $this->page * $this->per; ?>
                    <p><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>

                    <table class="table table-hover">
                        <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>标题</th>
                                <th>类别</th>
                                <th>价格</th>
                                <th>购买时间</th>
                                <th>链接</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($this->bought_ops as $op) { ?>
                                <tr id="op-<?php echo $op['id']; ?>" class="<?php echo ($op['id'] % 2 == 0) ? 'even' : 'odd'; ?>">
                                    <td data-label="#"><?php echo $i; ?></td>
                                    <td data-label="标题"><?php echo $op['title']; ?></td>
                                    <td data-label="类别"><?php echo $op['category_name']; ?></td>
                                    <td data-label="价格"><?php echo $op['price']; ?> 信用点</td>
                                    <td data-label="购买时间"><?php echo $op['bought_on']; ?></td>
                                    <td data-label="查看"><a href="<?php echo $this->baseUrl(); ?>/portfolio/view/id/<?php echo $op['id']; ?>/">查看</a></td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($this->page != 1) { ?>
                                <li>
                                    <a href="<?php echo $this->baseUrl(); ?>/manage/bought/page/<?php echo ($this->page - 1); ?>/" aria-label="Previous">
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
                                <li <?php if ($this->page == $page) echo 'class="active"'; ?>><a href="<?php echo $this->baseUrl(); ?>/manage/bought/page/<?php echo $page; ?>/"><?php echo $page; ?></a></li>
                            <?php } ?>
                            <?php if ($after_ellipsis) { ?>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <?php } ?>
                            <?php if ($this->page != $this->total_page) { ?>
                                <li>
                                    <a href="<?php echo $this->baseUrl(); ?>/manage/bought/page/<?php echo ($this->page + 1); ?>/" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php } else { ?>
            <p>您还没有已购买的一页书。</p>
        <?php } ?>
        <div class="h-100"></div>
    </div>

    <div class="col-md-3">
        <h4>您的余额</h4>
        <p class="clearfix">
            <span class="label label-info">信用值</span>
            <span class="credit pull-right"><strong><?php echo $this->user['credit']; ?></strong></span>
        </p>
        <div class="highlight">
            <p>本页显示您的积分消费记录。</p>
            <h5>快速链接</h5>
            <p>
                <a href="<?php echo $this->baseUrl(); ?>/credit/records/" class="btn btn-sm btn-default">查看积分收入</a>
                <a href="<?php echo $this->baseUrl(); ?>/credit/topup/" class="btn btn-sm btn-primary">充值</a>
            </p>
        </div>
        <a href="<?php echo $this->baseUrl(); ?>/user/" class="btn btn-default">返回用户面板</a>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<?php $this->endBlock(); ?>