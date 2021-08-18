<?php
namespace app\Controller;

use Mini\Base\{Action, Session};
use Mini\Captcha\Captcha;

/**
 * 这是一个控制器的案例
 */
class Share extends Action
{
    /**
     * 初始化
     */
    function _init()
    {
        // Session::start();
        // $this->view->title = 'Home';
        // $this->view->_layout->setLayout('default');
    }
    
    /**
     * 默认动作
     */
    function indexAction()
    { 
        echo 'Error access.';
        die();
    }

    /**
     * 验证码动作
     */
    function getcaptchaAction()
    {
        $captcha = new Captcha();
        $captcha->setImgSize(296, 64);
        $captcha->create();
    }
}
