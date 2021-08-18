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
        <?php if ($this->user['is_staff']) { ?>
            <?php
            function compute_rate($yes, $tod)
            {
                if ($yes != 'Unknown' && $tod != 0) {
                    $chg_rate = round(($tod - $yes) / $tod, 3) * 100;
                    $chg_style = ($chg_rate >= 0) ? 'red' : 'green';
                    echo '<i class="' . $chg_style . '">(' . $chg_rate . '%)</i>';
                }
            }
            ?>
            <h3>站点统计 <small>更新于 <?php echo $this->today_stats['time_record']; ?> <a href="<?php echo $this->baseUrl(); ?>/manage/refresh/"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span></a></small></h3>
            <div class="tile clearfix">
                <div class="col-md-3 col-sm-4 col-xs-6 stats">
                    <span class="count-top"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 注册用户</span>
                    <div class="count">
                        <?php echo $this->today_stats['total_user']; ?>
                    </div>
                    <span class="count-bottom">截止昨日 <?php echo $this->yesterday_stats['total_user']; ?>
                        <?php compute_rate($this->yesterday_stats['total_user'], $this->today_stats['total_user']) ?>
                    </span>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 stats">
                    <span class="count-top"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> 活跃用户</span>
                    <div class="count"><?php echo $this->today_stats['active_user']; ?></div>
                    <span class="count-bottom">截止昨日 <?php echo $this->yesterday_stats['active_user']; ?>
                        <?php compute_rate($this->yesterday_stats['active_user'], $this->today_stats['active_user']) ?>
                    </span>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 stats">
                    <span class="count-top"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> 一页书总数</span>
                    <div class="count"><?php echo $this->today_stats['total_onepager']; ?></div>
                    <span class="count-bottom">截止昨日 <?php echo $this->yesterday_stats['total_onepager']; ?>
                        <?php compute_rate($this->yesterday_stats['total_onepager'], $this->today_stats['total_onepager']) ?>
                    </span>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 stats">
                    <span class="count-top"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> 付费一页书</span>
                    <div class="count"><?php echo $this->today_stats['paid_onepager']; ?></div>
                    <span class="count-bottom">截止昨日 <?php echo $this->yesterday_stats['paid_onepager']; ?>
                        <?php compute_rate($this->yesterday_stats['paid_onepager'], $this->today_stats['paid_onepager']) ?>
                    </span>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 stats">
                    <span class="count-top"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> 累计下载</span>
                    <div class="count"><?php echo $this->today_stats['download_summary']; ?></div>
                    <span class="count-bottom">截止昨日 <?php echo $this->yesterday_stats['download_summary']; ?>
                        <?php compute_rate($this->yesterday_stats['download_summary'], $this->today_stats['download_summary']) ?>
                    </span>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 stats">
                    <span class="count-top"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 评论数</span>
                    <div class="count"><?php echo $this->today_stats['total_comment']; ?></div>
                    <span class="count-bottom">截止昨日 <?php echo $this->yesterday_stats['total_comment']; ?>
                        <?php compute_rate($this->yesterday_stats['total_comment'], $this->today_stats['total_comment']) ?>
                    </span>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 stats">
                    <span class="count-top"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> 充值金额(元)</span>
                    <div class="count red"><?php echo $this->today_stats['topup_summary']; ?></div>
                    <span class="count-bottom">截止昨日 <?php echo $this->yesterday_stats['topup_summary']; ?>
                        <?php compute_rate($this->yesterday_stats['topup_summary'], $this->today_stats['topup_summary']) ?>
                    </span>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 stats">
                    <span class="count-top"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> 可用积分</span>
                    <div class="count"><?php echo $this->today_stats['total_credit']; ?></div>
                    <span class="count-bottom">截止昨日 <?php echo $this->yesterday_stats['total_credit']; ?>
                        <?php compute_rate($this->yesterday_stats['total_credit'], $this->today_stats['total_credit']) ?>
                    </span>
                </div>
            </div>
            <div class="h-30"></div>
        <?php } ?>
        <h3>快捷菜单</h3>
        <div class="manage-links clearfix">
            <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/portfolio/add/"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 一页书</a></div>
            <?php if ($this->user['is_staff']) { ?>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/manage/onepager/">管理一页书</a></div>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/manage/category/">管理分类</a></div>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/manage/topup/">管理充值</a></div>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/manage/feedback/">管理反馈</a></div>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/manage/user/">管理用户</a></div>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/tutorial/add/"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 教程</a></div>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/manage/tutorial/">管理教程</a></div>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/resource/add/"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 资源</a></div>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/manage/resource/">管理资源</a></div>
            <?php } else { ?>
                <div class="col-md-3 col-sm-4 col-xs-6"><a href="<?php echo $this->baseUrl(); ?>/manage/bought/">已购项目</a></div>
            <?php } ?>
        </div>
        <div class="h-30"></div>

        <?php if (!empty($this->op_award)) { ?>
            <div class="well">
                <?php echo $this->op_award['created_date']; ?> 管理员审核通过了 <strong><?php echo $this->op_award['onepager_title']; ?></strong> 。系统已奖励你 <?php echo $this->op_award['credit']; ?> 个积分。<small>(更多请查看<a href="<?php echo $this->baseUrl(); ?>/credit/records/">积分记录</a>)</small>
            </div>
        <?php } ?>

        <?php if (!empty($this->managed_ops)) { ?>
            <h3>一页书管理</h3>
            <div class="card">
                <div class="card-body">
                    <p>这里仅显示最近创建的一页书。如需查看全部记录，请<a href="<?php echo $this->baseUrl(); ?>/manage/onepager/">点击这里</a>。</p>
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
                                    <td data-label="审核" id="review-status-<?php echo $op['id']; ?>">
                                        <?php if ($op['reviewed'] == 0) {
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
                    <p><small>显示最近10项 / 总计 <?php echo $this->op_count; ?> 项</small></p>
                </div>
            </div>
            <div class="h-30"></div>
        <?php } ?>

        <h3>最近购买</h3>
        <?php if (!empty($this->bought_ops)) { ?>
            <div class="card">
                <div class="card-body">
                    <p>这里仅显示最近购买的内容。如需查看全部记录，请<a href="<?php echo $this->baseUrl(); ?>/manage/bought/">点击这里</a>。</p>
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
                    <p><small>显示最近10项 / 总计 <?php echo $this->bought_count; ?> 项</small></p>
                </div>
            </div>
        <?php } else { ?>
            <p>您还没有已购买的一页书。</p>
        <?php } ?>
        <div class="h-100"></div>
    </div>

    <div class="col-md-3">

        <h5>Hello <?php echo $this->username; ?></h5>
        <?php if ($this->user['is_staff']) { ?>
            <p class="text-info">尊敬的管理员你好~</p>
        <?php } ?>
        <p>上次登录时间 <?php echo $this->user['last_login']; ?></p>
        <a href="<?php echo $this->baseUrl(); ?>/user/profile/" class="btn btn-primary">修改资料</a>
        <a href="<?php echo $this->baseUrl(); ?>/user/logout/" class="btn btn-default">退出登录</a>

        <div class="callout">
            <h4>我的积分</h4>
            <p class="clearfix"><span class="label label-info">信用值</span> <span class="credit pull-right"><strong><?php echo $this->user['credit']; ?></strong></span></p>
            <p>
                <a href="<?php echo $this->baseUrl(); ?>/credit/topup/" class="btn btn-sm btn-primary">充值</a>
                <?php if ($this->checked) { ?>
                    <a href="#" class="check-btn btn btn-sm btn-default" disabled>已签到</a>
                <?php } else { ?>
                    <a href="javascript:check();" class="check-btn btn btn-sm btn-default">签到</a>
                <?php } ?>
                <a href="<?php echo $this->baseUrl(); ?>/credit/records/" class="btn btn-sm btn-default pull-right">积分记录</a>
            </p>
        </div>
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
        $('html,body').animate({scrollTop:0},'slow');
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
        $('html,body').animate({scrollTop:0},'slow');
    }

    function check() {
        url = '<?php echo $this->baseUrl(); ?>/credit/checkin/';
        $.get(url, function(result) {
            var status = result['status'];
            var msg = result['msg'];
            $("#response-text").text(msg);
            $("#response").slideDown(200);
            if (status == 'success') {
                var credit = result['credit'];
                $('.credit').html('<strong>' + credit + '</strong>');
                $('.check-btn').attr('disabled', 'disabled');
                $('.check-btn').text('已签到');
            }
            console.log(result);
        });
        $('html,body').animate({scrollTop:0},'slow');
    }
</script>
<?php $this->endBlock(); ?>