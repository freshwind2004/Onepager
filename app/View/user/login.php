<div class="container main-container clearfix">
    <div class="col-md-6">
        <h3>用户登录</h3>
        <div id="errbox" class="alert alert-danger" style="display:none;" role="alert">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            <span id="errdescription"></span>
        </div>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="用户名或邮箱" required>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="请输入密码" required>
            </div>
            <div class="form-group">
                <label for="captcha">验证码</label>
                <div class="captcha">
                    <input type="captcha" class="form-control" name="captcha" id="captcha" placeholder="请输入验证码" required>
                    <img class='captcha' src="<?php echo $this->baseUrl(); ?>/share/getcaptcha/" onclick="this.src='<?php echo $this->baseUrl(); ?>/share/getcaptcha/?t='+Math.random()" />
                </div>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember" id="remember">
                    <span>14天内记住我</span>
                </label>
            </div>
            <input type="submit" class="btn btn-default" value="登录">
        </form>
    </div>
</div>

<?php $this->beginBlock('footer_jscode'); ?>
<script>
    function displayErrorDescription() {
        var errcode = <?php echo $this->errcode; ?>;
        var errdescription = '';
        if ((errcode > 0) && (errcode < 5)) {
            if (errcode == 1) {
                errdescription = "用户名或密码错误";
            } else if (errcode == 2) {
                errdescription = "用户名包含有不被允许的字符，请检查后重新输入";
            } else if (errcode == 3) {
                errdescription = "验证码错误";
            } else if (errcode == 4) {
                errdescription = "检测到跨站访问，请检查您是否访问了错误的登录页";
            }
            $('#errdescription').text(errdescription);
            $('#errbox').slideDown(200);
        }
    }
    $(document).ready(function() {
        displayErrorDescription();
    });
</script>
<?php $this->endBlock(); ?>