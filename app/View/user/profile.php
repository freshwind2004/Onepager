<div class="container main-container clearfix">
    <div class="col-md-12">
        <?php if ($this->res_msg) { ?>
            <div class="alert alert-info" role="alert">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                <?php echo $this->res_msg; ?>
            </div>
        <?php } ?>

        <h2>Profile</h2>
        <form action="<?php echo $this->baseUrl(); ?>/user/update" method="post">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="username">用户名</label>
                    <input class="form-control" value="<?php echo $this->user['username']; ?>" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@163.com" value="<?php echo $this->user['email']; ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="mobile">移动电话</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="13..." value="<?php echo $this->user['mobile']; ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="nickname">昵称</label>
                    <input type="text" class="form-control" id="nickname" name="nickname" placeholder="亚当" value="<?php echo $this->user['nickname']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="sex">性别</label>
                    <select class="form-control" name="sex" id="sex">
                        <?php $sex = $this->user['sex']; ?>
                        <option value="male" <?php if ($sex == 'male') {
                                                    echo ' selected';
                                                } ?>>男</option>
                        <option value="female" <?php if ($sex == 'female') {
                                                    echo ' selected';
                                                } ?>>女</option>
                        <option value="secret" <?php if ($sex == 'secret') {
                                                    echo ' selected';
                                                } ?>>保密</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>

        <h2>密码</h2>
        <form action="<?php echo $this->baseUrl(); ?>/user/changepwd" method="post">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="oldpassword">原密码</label>
                    <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="********" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="password">新密码</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="password2">再次输入新密码</label>
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="********" required>
                </div>
            </div>

            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</div>