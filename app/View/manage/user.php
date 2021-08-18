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
        <h3>用户管理</h3>
        <?php if (!empty($this->users)) { ?>
            <div class="card">
                <div class="card-body">
                    <p><small>总计 <?php echo $this->total_count; ?> 项</small></p>

                    <table class="table table-hover">
                        <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>用户名</th>
                                <th>昵称</th>
                                <th>信用点</th>
                                <th>权限</th>
                                <th>E-mail</th>
                                <th>电话</th>
                                <th>性别</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->users as $item) { ?>
                                <tr id="u-<?php echo $item['id']; ?>" class="<?php echo ($item['id'] % 2 == 0) ? 'even' : 'odd'; ?>">
                                    <td data-label="#"><?php echo $item['id']; ?></td>
                                    <td data-label="用户名"><?php echo $item['username']; ?></td>
                                    <td data-label="昵称"><?php echo empty($item['nickname']) ? 'n/a' : $item['nickname']; ?></td>
                                    <td data-label="信用点"><?php echo $item['credit']; ?></td>
                                    <td data-label="权限" id="t-<?php echo $item['id']; ?>"><?php echo ($item['is_staff']) ? '<span class="label label-primary">管理员</span>' : '<span class="label label-default">普通用户</span>'; ?></td>
                                    <td data-label="E-mail"><?php echo empty($item['email']) ? 'n/a' : $item['email']; ?></td>
                                    <td data-label="电话"><?php echo empty($item['mobile']) ? 'n/a' : $item['mobile']; ?></td>
                                    <td data-label="性别"><?php echo empty($item['sex']) ? 'n/a' : $item['sex']; ?></td>
                                    <td data-label="管理"><button type="button" class="btn-link" data-toggle="modal" data-target="#showDetail" data-id="<?php echo $item['id']; ?>" data-name="<?php echo $item['username']; ?>" data-credit="<?php echo $item['credit']; ?>" data-email="<?php echo empty($item['email']) ? 'n/a' : $item['email']; ?>" data-mobile="<?php echo empty($item['mobile']) ? 'n/a' : $item['mobile']; ?>" data-sex="<?php echo empty($item['sex']) ? 'n/a' : $item['sex']; ?>" data-join="<?php echo $item['joined_since']; ?>" data-last="<?php echo $item['last_login']; ?>" data-count="<?php echo $item['count']; ?>">详情</button></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <!-- Modal -->
                    <div class="modal fade" id="showDetail" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modalLabel"><strong><span class="userName"></span></strong> 账户管理</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <span class="fix-width label label-danger">警告</span> 删除用户会同时删除其发表的内容！请谨慎操作。<br>
                                        <span class="fix-width label label-info">统计</span> 用户 <strong><span class="userName"></span></strong> 共有 <span class="opCount"></span> 篇一页书。<br>
                                        <span class="fix-width label label-info">信用点</span> <span class="Credit"></span><br>
                                        <span class="fix-width label label-info">Email</span> <span class="Email"></span><br>
                                        <span class="fix-width label label-info">电话</span> <span class="Mobile"></span><br>
                                        <span class="fix-width label label-info">性别</span> <span class="Sex"></span><br>
                                        此用户注册于 <span class="joinedSince"></span>，最近登录在 <span class="lastLogin"></span>。
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                    <button type="button" id="promotePrivilege" class="btn btn-primary" data-dismiss="modal">调整权限</button>
                                    <button type="button" id="confirmDelete" class="btn btn-danger" data-dismiss="modal">删除</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <p>还没有用户。</p>
        <?php } ?>

        <h3>添加用户</h3>
        <form action="" method="post">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="username">用户名</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="请输入用户名" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="password">密码</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="******" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="password2">重复密码</label>
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="******" required>
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
    $('#showDetail').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var credit = button.data('credit');
        var email = button.data('email');
        var mobile = button.data('mobile');
        var sex = button.data('sex');
        var join = button.data('join');
        var last = button.data('last');
        var count = button.data('count');
        $('.userName').text(name);
        $('.opCount').text(count);
        $('.Credit').text(credit);
        $('.Email').text(email);
        $('.Mobile').text(mobile);
        $('.Sex').text(sex);
        $('.joinedSince').text(join);
        $('.lastLogin').text(last);
        $('#confirmDelete').click(function() {
            url = '<?php echo $this->baseUrl(); ?>/user/del/id/' + id + '/';
            row_id = '#u-' + id
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
            $(this).unbind();
        })
        $('#promotePrivilege').click(function() {
            url = '<?php echo $this->baseUrl(); ?>/user/promo/id/' + id + '/';
            label_id = '#t-' + id
            $.get(url, function(result) {
                var status = result['status'];
                var msg = result['msg'];
                $("#response-text").text(msg);
                $("#response").slideDown(200);
                if (status == 0) {
                    $(label_id).html('<span class="label label-default">普通用户</span>');
                } else if (status == 1) {
                    $(label_id).html('<span class="label label-primary">管理员</span>');
                }
                console.log(result);
            });
            $('html,body').animate({scrollTop:0},'slow');
            $(this).unbind();
        })
    })
</script>
<?php $this->endBlock(); ?>