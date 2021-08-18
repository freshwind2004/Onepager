<div class="container main-container clearfix">
    <div class="col-md-12">
        <h2>注册用户</h2>
        <?php if ($this->res_msg) { ?>
            <div class="alert alert-warning" role="alert">
                <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                <?php echo $this->res_msg; ?>
            </div>
        <?php } ?>
        <form action="" method="post">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="username">用户名</label>
                    <input class="form-control" id="username" name="username" placeholder="john1024" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@163.com">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="password">密码</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="password2">再次输入密码</label>
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="********" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="captcha">验证码</label>
                    <div class="captcha">
                        <input type="captcha" class="form-control" name="captcha" id="captcha" placeholder="请输入验证码" required>
                        <img class='captcha' src="<?php echo $this->baseUrl(); ?>/share/getcaptcha/" onclick="this.src='<?php echo $this->baseUrl(); ?>/share/getcaptcha/?t='+Math.random()" />
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</div>