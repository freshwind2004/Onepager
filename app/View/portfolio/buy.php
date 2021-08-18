<div class="container main-container clearfix">
    <div id="response" class="row" style="display: none;">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                <span id="response-text"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <p>你将要购买以下内容</p>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="title">一页书</label>
                    <input type="text" class="form-control" id="title" value="<?php echo $this->onepager['title']; ?>" disabled>
                </div>
                <div class="col-md-6 form-group">
                    <label for="author">提供方</label>
                    <input type="text" class="form-control" id="author" value="<?php echo $this->onepager['author_name']; ?>" disabled>
                </div>
                <div class="col-md-6 form-group">
                    <label for="subtitle">简介</label>
                    <input type="text" class="form-control" id="subtitle" value="<?php echo $this->onepager['subtitle']; ?>" disabled>
                </div>
                <div class="col-md-6 form-group">
                    <label for="price">价格</label>
                    <input type="text" class="form-control" id="price" value="<?php echo $this->onepager['price']; ?> 信用点" disabled>
                </div>
            </div>
            <div class="h-30"></div>
            <p class="text-info">* 虚拟商品不提供退款服务，请理性购买。</p>
            <a href="javascript:op_buy(<?php echo $this->onepager['id']; ?>);" class="btn btn-primary form-control">确认购买</a>

        </div>
        <div class="col-md-3">
            <h4>
                <span class="text-muted">你的钱包</span>
            </h4>
            <ul class="list-group">
                <li class="list-group-item">
                    <div>
                        <h6>信用点余额</h6>
                    </div>
                    <span class="text-success"><?php echo $this->user['credit']; ?></span>
                </li>
                <li class="list-group-item">
                    <span>你需要支付</span>
                    <strong><?php echo $this->onepager['price']; ?></strong>
                    <span>信用点</span>
                </li>
            </ul>
        </div>
    </div>

</div>
<?php $this->beginBlock('footer_jscode'); ?>
<script>
    function op_buy(id) {
        url = '<?php echo $this->baseUrl(); ?>/portfolio/checkout/id/' + id + '/';
        $.get(url, function(result) {
            var status = result['status'];
            var msg = result['msg'];
            $("#response-text").text(msg);
            $("#response").slideDown(200);
            if (status == 'success') {
                setTimeout(() => {
                    window.location = '<?php echo $this->baseUrl(); ?>/portfolio/view/id/' + id + '/';
                }, 1000);
            }
            console.log(result);
        });
    }
</script>
<?php $this->endBlock(); ?>