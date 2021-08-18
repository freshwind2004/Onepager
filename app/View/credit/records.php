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

    <div class="col-md-9">
        <h3>我的积分记录</h3>
        <?php if (!empty($this->my_credit_records)) { ?>
            <div class="card">
                <div class="card-body">
                    <?php $fisrt = ($this->page - 1) * $this->per + 1; ?>
                    <?php $last = $this->page * $this->per; ?>
                    <p><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>

                    <table class="table table-hover">
                        <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>日期</th>
                                <th>类别</th>
                                <th>积分</th>
                                <th>说明</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->my_credit_records as $record) { ?>
                                <tr id="record-<?php echo $record['id']; ?>" class="<?php echo ($record['id'] % 2 == 0) ? 'even' : 'odd'; ?> <?php echo strtolower($record['type']); ?>">
                                    <td data-label="#"><?php echo $record['id']; ?></td>
                                    <td data-label="日期"><?php echo $record['created_date']; ?></td>
                                    <td data-label="类别">
                                        <?php switch ($record['type']) {
                                            case 'Topup':
                                                echo '<span class="label label-danger">充值</span>';
                                                break;
                                            case 'Onepager':
                                                echo '<span class="label label-primary">发帖</span>';
                                                break;
                                            case 'Comment':
                                                echo '<span class="label label-info">评论</span>';
                                                break;
                                            default:
                                                echo '<span class="label label-default">打卡</span>';
                                                break;
                                        } ?>
                                    </td>
                                    <td data-label="积分"><?php echo $record['credit']; ?> 个积分</td>
                                    <td data-label="说明"><?php echo $record['note']; ?>
                                        <?php if (isset($record['onepager_title'])) {
                                            echo '<br>' . $record['onepager_title'];
                                        } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($this->page != 1) { ?>
                                <li>
                                    <a href="<?php echo $this->baseUrl(); ?>/credit/records/page/<?php echo ($this->page - 1); ?>/" aria-label="Previous">
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
                                <li <?php if ($this->page == $page) echo 'class="active"'; ?>><a href="<?php echo $this->baseUrl(); ?>/credit/records/page/<?php echo $page; ?>/"><?php echo $page; ?></a></li>
                            <?php } ?>
                            <?php if ($after_ellipsis) { ?>
                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <?php } ?>
                            <?php if ($this->page != $this->total_page) { ?>
                                <li>
                                    <a href="<?php echo $this->baseUrl(); ?>/credit/records/page/<?php echo ($this->page + 1); ?>/" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php } else { ?>
            <p>您还没有积分记录。</p>
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
            <p>本页显示您获取积分的记录。</p>
            <h5>快速链接</h5>
            <p>
                <a href="<?php echo $this->baseUrl(); ?>/manage/bought/" class="btn btn-sm btn-default">查看已购项目</a>
                <a href="<?php echo $this->baseUrl(); ?>/credit/topup/" class="btn btn-sm btn-primary">充值</a>
            </p>
        </div>
        <a href="<?php echo $this->baseUrl(); ?>/user/" class="btn btn-default">返回用户面板</a>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<?php $this->endBlock(); ?>