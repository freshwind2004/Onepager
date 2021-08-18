<div class="container main-container clearfix">

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="<?php echo $this->baseUrl(); ?>/">首页</a></li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/portfolio/">浏览一页书</a></li>
                    <li><a href="<?php echo $this->baseUrl(); ?>/portfolio/category/show/<?php echo $this->onepager['category_url']; ?>/"><?php echo $this->onepager['category_name']; ?></a></li>
                    <li class="active"><?php echo $this->onepager['title']; ?></li>
                </ol>

                <?php if ($this->res_msg) { ?>
                    <div class="alert alert-info" role="alert">
                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                        <span class="sr-only">Info:</span>
                        <?php echo $this->res_msg; ?>
                    </div>
                <?php } ?>

                <?php if ($this->transaction != false) { ?>
                    <div class="alert alert-success" role="alert">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        <span class="sr-only">Success:</span>
                        <span>已购买：您已于 <?php echo $this->transaction['created_date']; ?> 支付了 <?php echo $this->transaction['amount']; ?> 个信用点购买了此资源。</span>
                    </div>
                <?php } ?>

                <?php if ($this->display == 'show' && $this->onepager['reviewed'] == false) { ?>
                    <div class="alert alert-warning" role="alert">
                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                        <span class="sr-only">Info:</span>
                        <span>此内容尚未通过审核，仅供作者或管理员预览。请注意，访客无法查看此页内容。</span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php if ($this->display) { ?>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <img src="<?php echo $this->baseUrl(); ?><?php echo $this->onepager['preview_path']; ?>" alt="<?php echo $this->onepager['title']; ?>" class="fullwidth" />
                    <div class="h-30"></div>
                </div>

                <div class="col-md-12">
                    <h2 class="text-uppercase">
                        <?php echo $this->onepager['title']; ?>
                        <?php if ($this->transaction != false) { ?><span class="label label-success">已购买</span><?php } ?>
                    </h2>
                    <h5><?php echo $this->onepager['subtitle']; ?></h5>
                    <div class="h-30"></div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="content">
                        <?php echo $this->onepager['description']; ?>
                    </div>
                    <div class="h-30"></div>
                    <h5>下载信息 <small>Download Links</small></h5>
                    <?php if ($this->authorised || $this->onepager['price'] == 0 || ($this->transaction != false)) { ?>
                        <?php if ($this->transaction != false) { ?>
                            <p class="text-info">您已于 <?php echo $this->transaction['created_date']; ?> 支付了 <?php echo $this->transaction['amount']; ?> 个信用点购买了此资源的下载权限。 </p>
                        <?php } ?>
                        <?php if ($this->authorised) { ?>
                            <p class="text-info">您拥有此资源的管理权限 </p>
                        <?php } ?>
                        <div class="dl-buttons">
                            <ul>
                                <li>
                                    <span class="label">价格</span>
                                    <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                                    <?php if ($this->onepager['price'] == 0) {
                                        echo '免费';
                                    } else {
                                        echo $this->onepager['price'] . ' 信用点';
                                    } ?>
                                </li>
                                <li class="hidden-xs">
                                    <span class="label">权限</span>
                                    <span class="glyphicon glyphicon-link" aria-hidden="true"></span> 可供下载
                                </li>
                                <li class="download">
                                    <a href="<?php echo $this->baseUrl(); ?>/portfolio/download/hash/<?php echo $this->hash; ?>/" data-toggle="tooltip" data-placement="top" title="链接仅单次有效">
                                        <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> <?php echo $this->onepager['attachment_filename']; ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <p> </p>
                    <?php } else { ?>
                        <div class="well">
                            您需要购买此资源才能获取下载链接。请<a href="<?php echo $this->baseUrl(); ?>/credit/topup/">充值</a>或<a href="<?php echo $this->baseUrl(); ?>/user/">签到打卡</a>以获取信用点。当前资源需支付 <?php echo $this->onepager['price']; ?> 个信用点。
                        </div>
                        <div class="dl-buttons">
                            <ul>
                                <li>
                                    <span class="label">价格</span>
                                    <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                                    <?php if ($this->onepager['price'] == 0) {
                                        echo '免费';
                                    } else {
                                        echo $this->onepager['price'] . ' 信用点';
                                    } ?>
                                </li>
                                <li class="hidden-xs">
                                    <span class="label">权限</span>
                                    <span class="glyphicon glyphicon-link" aria-hidden="true"></span> 无法下载
                                </li>
                                <li class="download">
                                    <span class="label">支付积分</span>
                                    <a href="<?php echo $this->baseUrl(); ?>/portfolio/buy/id/<?php echo $this->onepager['id']; ?>/">
                                        <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                                        购买资源
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                    自 <?php echo $this->onepager['created_date']; ?> 创建后，累计下载 <?php echo $this->onepager['download_count']; ?> 次。

                    <div class="h-30"></div>

                    <p class="highlight"><span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> <?php echo $this->onepager['copyright']; ?></p>
                    <div class="h-30"></div>

                    <?php if ($this->authorised) { ?>
                        <p>
                            <a href="<?php echo $this->baseUrl(); ?>/portfolio/edit/id/<?php echo $this->onepager['id']; ?>/" class="btn btn-primary">编辑</a>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#delWarning" data-id="<?php echo $this->onepager['id']; ?>" data-title="<?php echo $this->onepager['title']; ?>">删除</button>
                        </p>
                        <!-- Modal -->
                        <div class="modal fade" id="delWarning" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="modalLabel">删除 <strong><span class="postTitle"></span></strong> ?</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            <span class="label label-danger">警告</span> 删除操作不可逆！<br>
                                            <span class="label label-danger">警告</span> 所有评论被移除，作者奖励积分将保留！<br>
                                            <span class="label label-danger">警告</span> 请谨慎操作！<br>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                        <button type="button" class="confirmDelete btn btn-danger" data-dismiss="modal">确认删除</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="h-30"></div>
                    <?php } ?>

                    <div class="adjacent">
                        <div class="adjacent-link">
                            <div class="link-item">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            </div>
                            <div class="link-item">
                                <?php if ($this->adj_onepagers['previous'] == false) {
                                    echo '<a href="#">没有了</a><br><span class="text-muted">没有更新的内容</span>';
                                } else {
                                    echo '<a href="' . $this->baseUrl() . '/portfolio/view/id/' . $this->adj_onepagers['previous']['id'] . '/">' . $this->adj_onepagers['previous']['title'] . '</a><br><span class="text-muted">' . $this->adj_onepagers['previous']['subtitle']  . '</span>';
                                } ?>
                            </div>
                        </div>
                        <div class="adjacent-link">
                            <div class="link-item">
                                <?php if ($this->adj_onepagers['next'] == false) {
                                    echo '<a href="#">没有了</a><br><span class="text-muted">没有更早的内容</span>';
                                } else {
                                    echo '<a href="' . $this->baseUrl() . '/portfolio/view/id/' . $this->adj_onepagers['next']['id'] . '/">' . $this->adj_onepagers['next']['title'] . '</a><br><span class="text-muted">' . $this->adj_onepagers['next']['subtitle']  . '</span>';
                                } ?>
                            </div>
                            <div class="link-item">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </div>
                        </div>

                    </div>
                    <div class="h-30"></div>
                </div>
                <div class="col-md-3">
                    <ul class="cat-ul">
                        <li><span class="label label-primary"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> 分类</span> <?php echo $this->onepager['category_name']; ?></li>
                        <li>
                            <span class="label label-info"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> 价格</span>
                            <?php if ($this->onepager['price'] == 0) {
                                echo '免费';
                            } else {
                                echo $this->onepager['price'] . ' 信用点';
                            } ?>
                        </li>
                        <li><span class="label label-default"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> 创建</span> <?php echo $this->onepager['created_date']; ?></li>
                        <li><span class="label label-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 更新</span> <?php echo $this->onepager['updated_date']; ?></li>
                        <li><span class="label label-default"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 作者</span> <?php echo $this->onepager['author_name']; ?></li>
                        <li><span class="label label-default"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span> 版本</span> v<?php echo $this->onepager['version']; ?></li>
                        <li><span class="label label-default"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> 下载</span> <?php echo $this->onepager['download_count']; ?> 次</li>
                    </ul>
                    <div class="h-30"></div>

                    <?php if (!empty($this->related_onepagers)) { ?>
                        <div class="related-items">
                            <?php foreach ($this->related_onepagers as $item) { ?>
                                <div class="item-wrapper">
                                    <div class="item">
                                        <h4><a target="_blank" href="<?php echo $this->baseUrl(); ?>/portfolio/view/id/<?php echo $this->onepager['id']; ?>/"><?php echo $item['title']; ?></a></h4>
                                        <p>
                                            <span class="text-muted"><?php echo $item['subtitle']; ?></span><br>
                                            <?php if ($item['price'] == 0) { ?>
                                                <span class="label label-success">免费</span>
                                            <?php } else { ?>
                                                <span class="label label-info"><?php echo $item['price']; ?> 个信用点</span>
                                            <?php } ?>
                                            <span class="label label-default"><?php echo substr($item['created_date'], 0, 10); ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="h-30"></div>
                    <?php } ?>
                </div>

            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <h3>评论</h3>
                    <ul class="comment-list">
                        <?php foreach ($this->comments as $item) { ?>
                            <li>
                                <div class="comment-body">
                                    <div class="comment-avatar">
                                        <canvas width="64" height="64" class="avatar" data-initial="<?php echo $item['author_name']; ?>"></canvas>
                                    </div>
                                    <div class="comment-content">
                                        <h6 class="comment-author"><?php echo $item['author_name']; ?></h6>
                                        <p><?php echo $item['content']; ?></p>
                                        <div class="comment-footer"><span class="date"><?php echo $item['created_date']; ?></span></div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="comment-form">
                        <h5>发表留言</h5>
                        <?php if ($this->is_authenticated) { ?>
                            <form action="<?php echo $this->baseUrl(); ?>/portfolio/comment/id/<?php echo $this->onepager['id']; ?>/" method="post">
                                <textarea id="content" name="content" class="form-control"></textarea>
                                <input type="submit" class="btn btn-box" value="发表">
                            </form>
                        <?php } else { ?>
                            <p>请 <a href="<?php echo $this->baseUrl(); ?>/user/login/?next=<?php echo $this->baseUrl(); ?>/portfolio/view/id/<?php echo $this->onepager['id']; ?>/">登陆</a> 后发布评论。</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    <?php } else { ?>
        <div class="container">
            <h1 class="text-danger">暂停访问</h1>
            <p>您期望访问的内容暂时无法查看。请稍后重试。</p>
            <div class="h-30"></div>

            <div class="row">
                <div class="col-md-6">
                    <p class="text-primary"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 无法查看的原因可能是</p>
                    <ul>
                        <li>内容正在审核，您可能需要等待1-3个工作日后才能查看；</li>
                        <li>内容不符合相关政策，已按照监管要求下架；</li>
                        <li>内容已被作者或管理员删除，您无法继续查看；</li>
                    </ul>

                    <p class="text-primary"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> 此资源已开放下载，为什么需要再次审核？</p>
                    <p>可能是作者大幅度更新了内容，使得本站需再次进行审核；<br>
                        或因政策发生变化，需要重新进行审核。</p>

                    <p class="text-primary"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> 到底是在审核还是被下架了？</p>
                    <p>一般审核需要1-3个工作日，如果超过3个工作日仍无法访问，则说明内容已下架。</p>

                    <p class="text-primary"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> 我购买过此资源，为什么不能再次下载？</p>
                    <p>您仍然可以使用您以前下载的拷贝，但是您无法重新下载。<br>
                        这是因为此内容正在审核，或已被作者自行移除，或违反相关政策被禁止提供。<br>
                        若因审核原因，您可以在审核完成后恢复下载；其他原因则无法恢复下载。</p>
                </div>

                <div class="col-md-6">
                    <p class="text-primary"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> 此内容被下架后，我能否要求退款？</p>
                    <p>不能。根据用户协议，我们提供的是单次下载权限。<br>
                        保留您的长期下载权限是一种便利措施，而不是一项服务承诺。<br>
                        因政策变化或作者自行删除导致内容下架后，本站不再向您提供下载权限</p>

                    <p class="text-primary"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> 我购买后从未下载，能否要求退款？</p>
                    <p>不能。根据用户协议，我们提供的是单次下载权限。<br>
                        一旦您被授予这项权限，交易便已经完成。您可以自由选择是否使用该下载权限。</p>
                    <p><small><span class="label label-default">例外</span> 如因下载内容本身存在重大错误、误导、夸大宣传而下架的，<br>
                            或因本站自身的技术原因导致用户无法下载而下架的，将酌情为用户退款。</small></p>
                </div>
            </div>
        </div>
    <?php } ?>

</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script src="<?php echo $this->baseUrl(); ?>/js/avatar.js"></script>
<script>
    $('#delWarning').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var postTitle = button.data('title');
        $('.postTitle').text(postTitle);
        $('.confirmDelete').click(function() {
            url = '<?php echo $this->baseUrl(); ?>/portfolio/del/id/' + id + '/';
            $.get(url, function(result) {
                var status = result['status'];
                if (status == 'success') {
                    alert('删除成功！');
                    window.location = '<?php echo $this->baseUrl(); ?>/portfolio/';
                }
                console.log(result);
            });
        })
    })
</script>
<?php $this->endBlock(); ?>