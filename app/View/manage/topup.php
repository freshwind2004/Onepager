<div class="container main-container clearfix">

    <div id="response" class="col-md-12" style="display: none;">
        <div class="alert alert-info" role="alert">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
            <span id="response-text"></span>
        </div>
    </div>

    <div class="col-md-9">
        <h3>管理充值</h3>
        <?php if (!empty($this->topups)) { ?>
            <div class="card">
                <div class="card-body">
                    <?php $fisrt = ($this->page - 1) * $this->per + 1; ?>
                    <?php $last = $this->page * $this->per; ?>
                    <p class="visible-xs"><small>显示 <?php echo $fisrt; ?> - <?php echo ($last < $this->total_count) ? $last : $this->total_count; ?> 项 / 总计 <?php echo $this->total_count; ?> 项</small></p>
                    <table class="table table-hover">
                        <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>用户</th>
                                <th>支付金额</th>
                                <th>定价</th>
                                <th>下单时间</th>
                                <th>支付时间</th>
                                <th>状态</th>
                                <th>退款</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->topups as $item) { ?>
                                <tr id="topup-<?php echo $item['id']; ?>" class="<?php echo ($item['id'] % 2 == 0) ? 'even' : 'odd'; ?>">
                                    <td data-label="#"><?php echo $item['id']; ?></td>
                                    <td data-label="用户"><?php echo $item['user_name']; ?></td>
                                    <td data-label="金额"><?php echo $item['money']; ?></td>
                                    <td data-label="定价"><?php echo $item['price']; ?></td>
                                    <td data-label="下单时间"><?php echo $item['created_date']; ?></td>
                                    <td data-label="支付时间"><?php echo (empty($item['paid_date'])) ? 'n/a' : $item['paid_date']; ?></td>
                                    <td data-label="状态"><?php echo ($item['paid']) ? '<span class="label label-success">已支付</span>' : '<span class="label label-default">未支付</span>'; ?></td>
                                    <td data-label="退款"><?php echo ($item['refund']) ? '<span class="label label-danger">已退款</span>' : '<span class="label label-default">正常</span>'; ?></td>
                                    <td data-label="管理"><button type="button" class="btn-link" data-toggle="modal" data-target="#showDetail" data-id="<?php echo $item['id']; ?>" data-oid="<?php echo $item['order_id']; ?>" data-aid="<?php echo (empty($item['alipay_order_id'])) ? 'n/a' : $item['alipay_order_id']; ?>" data-user="<?php echo $item['user_name']; ?>" data-odate="<?php echo $item['created_date']; ?>" data-pdate="<?php echo (empty($item['paid_date'])) ? 'n/a' : $item['paid_date']; ?>" data-credit="<?php echo $item['credit']; ?>" data-paid="<?php echo $item['paid']; ?>" data-pamo="<?php echo $item['money']; ?>" data-tamo="<?php echo $item['price']; ?>">查看</button></td>
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
            <!-- Modal -->
            <div class="modal fade" id="showDetail" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalLabel">查看 <strong><span class="userName"></span></strong> 的充值记录</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                <span class="text-warning">提示：您无法删除已支付的订单。</span><br>
                                <strong>支付状态</strong> <span class="paid"></span><br>
                                <strong>用户</strong> <span class="userName"></span><br>
                                <strong>订单编号</strong> <span class="orderId"></span><br>
                                <strong>支付宝编号</strong> <span class="alipayOrderId"></span><br>
                                <strong>下单时间</strong> <span class="orderDate"></span><br>
                                <strong>支付时间</strong> <span class="paidDate"></span><br>
                                <strong>实付/计划</strong> <span class="text-success">人民币 <strong><span class="paidAmount"></span></strong> 元</span> / 人民币 <span class="topupAmount"></span> 元<br>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="confirmDelete btn btn-danger" data-dismiss="modal">删除记录</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <p>还没有充值记录。</p>
        <?php } ?>
        <div class="h-100"></div>
    </div>

    <div class="col-md-3">
        <a href="<?php echo $this->baseUrl(); ?>/user/" class="btn btn-default">返回用户面板</a>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script>
    $('#showDetail').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('user');
        var order_id = button.data('oid');
        var alipay_order_id = button.data('aid');
        var paid = button.data('paid');
        var orderDate = button.data('odate');
        var paidDate = button.data('pdate');
        var paidAmount = button.data('pamo');
        var topupAmount = button.data('tamo');
        if (paid == 1) {
            paid = '<span class="label label-success">已支付</span>';
            $('.confirmDelete').hide();
        } else {
            paid = '<span class="label label-default">未支付</span>';
            $('.confirmDelete').show();
        }
        $('.userName').text(name);
        $('.orderId').text(order_id);
        $('.alipayOrderId').text(alipay_order_id);
        $('.paid').html(paid);
        $('.orderDate').text(orderDate);
        $('.paidDate').text(paidDate);
        $('.paidAmount').text(paidAmount);
        $('.topupAmount').text(topupAmount);
        $('.confirmDelete').click(function() {
            url = '<?php echo $this->baseUrl(); ?>/manage/deltopup/id/' + id + '/';
            row_id = '#topup-' + id
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
        })
    });
</script>
<?php $this->endBlock(); ?>