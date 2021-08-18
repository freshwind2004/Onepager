 <!-- main-container -->
 <div class="container main-container">
     <?php if ($this->res_msg) { ?>
         <div class="col-md-12">
             <div class="alert alert-info" role="alert">
                 <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                 <span class="sr-only">Info:</span>
                 <?php echo $this->res_msg; ?>
             </div>
         </div>
     <?php } ?>
     <div class="col-md-6">
         <h3>提交反馈</h3>
         <p class="text-muted">欢迎通过下面的表格提交反馈。如果您希望跟进处理情况，请留下联系方式。</p>
         <form action="" method="post">
             <div class="row">
                 <div class="col-md-6">
                     <div class="form-group">
                         <label for="message">名字</label>
                         <input type="text" class="form-control" name="name" placeholder="您的称呼" required>
                     </div>
                 </div>
                 <div class="col-md-6">
                     <div class="form-group">
                         <label for="message">联系方式</label>
                         <input type="text" class="form-control" name="contact" placeholder="电邮、QQ或手机号(可选)">
                     </div>
                 </div>
                 <div class="col-md-12">
                     <div class="form-group">
                         <label for="message">消息内容</label>
                         <textarea class="form-control" name="message" required></textarea>
                     </div>
                     <div class="form-group">
                         <label for="captcha">验证码</label>
                         <div class="captcha">
                             <input type="captcha" class="form-control" name="captcha" id="captcha" placeholder="请输入验证码" required>
                             <img class='captcha' src="<?php echo $this->baseUrl(); ?>/share/getcaptcha/" onclick="this.src='<?php echo $this->baseUrl(); ?>/share/getcaptcha/?t='+Math.random()" />
                         </div>
                     </div>
                     <input type="submit" class="btn btn-box" value="提交">
                 </div>
             </div>
         </form>
         <div class="h-30"></div>
     </div>

     <div class="col-md-6">
         <h5>感谢您的宝贵意见</h5>
         <div class="h-30"></div>
         <p>非常感谢您提供的建议和意见，<br>
             我们将会根据您的反馈调整我们的提供的服务。 </p>
         <p>如果你对我们的工作职位感兴趣，<br>
             或者有其他合作方案需要讨论，也可以直接与我们联系。 </p>
         <div class="contact-info">
             <p><span class="glyphicon glyphicon-phone" aria-hidden="true"></span> 027 5981 1413</p>
             <p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> info@onpager.work</p>
         </div>
     </div>


 </div>
 <!-- end main-container -->

 <?php $this->beginBlock('jumbotron'); ?>
 <!-- Top bar -->
 <div class="top-bar">
     <h1>提交反馈</h1>
     <p><a href="<?php echo $this->baseUrl(); ?>/">首页</a> / 反馈</p>
 </div>
 <!-- end Top bar -->
 <?php $this->endBlock(); ?>

 <?php $this->beginBlock('footer_jscode'); ?>
 <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
 <script>
     var editor = CKEDITOR.replace('message', {
         language: 'zh-cn',
         toolbar: [
             ['Undo', 'Redo'],
             ['RemoveFormat', 'Bold', 'Italic', 'Underline', 'Strike'],
             ['NumberedLisst', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustfyBlock'],
             ['Image', 'Table', 'Link', 'Unlink', 'Anchor', 'HorizontalRule'],
         ],
         extraPlugins: 'notification'
     });
     editor.on('required', function(evt) {
         editor.showNotification('内容不能为空.', 'warning');
         evt.cancel();
     });
 </script>
 <?php $this->endBlock(); ?>