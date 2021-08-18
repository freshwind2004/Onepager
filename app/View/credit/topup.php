<div class="container main-container clearfix">
    <div id="response" style="display: none;">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                <span id="response-text"></span>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <p>您即将进行充值操作，请核对下面的信息。</p>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="username">您的身份</label>
                <div class="input-group">
                    <span class="input-group-addon">用户名</span>
                    <input type="text" class="form-control" id="username" value="<?php echo $this->user['username']; ?>" disabled>
                </div>
            </div>
            <div class="col-md-6 form-group">
                <label for="email">收据发送方式</label>
                <div class="input-group">
                    <span class="input-group-addon">Email</span>
                    <input type="text" class="form-control" id="email" value="<?php echo $this->user['email']; ?>" disabled>
                </div>
            </div>
            <div class="col-md-6 form-group">
                <label for="amount">充值金额</label>
                <div class="input-group">
                    <span class="input-group-addon">人民币</span>
                    <input type="text" class="form-control" id="amount" value="10">
                    <span class="input-group-addon">.00</span>
                </div>
            </div>
            <div class="col-md-6 form-group">
                <label for="credit">等价于</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="credit" value="100" disabled>
                    <span class="input-group-addon">信用点</span>
                </div>
            </div>
        </div>
        <div class="h-30"></div>
        <p class="text-info"><sup>1</sup> 最小充值金额1元，请输入整数金额。<br>
        <sup>2</sup> 付款界面可能出现非整数（例如5.1元）以区分不同订单。该部分金额将会转换成积分存入您的账户。<br>
        <sup>3</sup> 虚拟商品不提供退款服务，请理性充值。</p>
        <button id="pay" class="btn btn-primary form-control">确认购买</button>
        <div class="h-100"></div>
    </div>
    <div class="col-md-3">
        <h4>
            <span class="text-muted">你的钱包</span>
        </h4>
        <ul class="list-group">
            <li class="list-group-item">
                <div>
                    <h5>信用点余额</h5>
                </div>
                <span class="text-success"><strong><?php echo $this->user['credit']; ?></strong></span>
            </li>
            <li class="list-group-item">
                汇率： 1元人民币 = <?php echo RATE; ?>信用点
            </li>
        </ul>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script>
    // 跳转到付款界面
    $('#pay').click(function() {
        var amount = $('#amount').val();
        if (!(/(^[1-9]\d*$)/.test(amount))) {
            if (amount < 1) {
                amount = 1;
            } else {
                amount = Math.round(amount);
            }
        $('#amount').val(amount);
        $('#credit').val(amount * <?php echo RATE; ?>);
        alert('已为您取整，请再次尝试提交'); 
        } else {
            window.location = '<?php echo $this->baseUrl(); ?>/credit/pay/amount/' + amount + '/';
        }
    });

    //jQuery实时监听input值变化 
    $('#amount').on('input propertychange', function() {
        var amount = $(this).val();
        $('#credit').val(amount * <?php echo RATE; ?>);
    });
</script>
<?php $this->endBlock(); ?>