<?php

namespace app\Controller;

use Mini\Base\{Action, Session, Exception};
use Mini\Captcha\Captcha;

/**
 * Page控制器
 * 显示静态页面
 */
class Page extends Action
{
    /**
     * 初始化
     */
    function _init()
    {
        include(APP_PATH . DS . 'Function' . DS . 'Islogged.inc.php');
        $this->view->_layout->setLayout('default');
    }

    /**
     * 默认动作
     */
    function indexAction()
    {
        header('location:' . $this->view->baseUrl() . '/about/');
        die();
    }

    /**
     * 关于页面
     */
    function aboutAction()
    {
        // 渲染并显示View
        $this->view->title = '关于';
        $this->view->display();
    }

    /**
     * 反馈页面
     */
    function feedbackAction()
    {
        $method = $this->_request->method();
        // dump($method);die();

        if ($method == 'POST') {
            $post = $this->params->_post;

            // 检测是否存在跨站攻击
            $csrf = $this->_request->checkCsrfToken();
            if (!$csrf) {
                Session::set('res_msg', 'CSRF校验未通过，请重试');
                header('location:' . $this->view->baseUrl() . '/page/feedback/');
                die();
            }

            $captcha = new Captcha();
            if (!isset($post['captcha']) || $captcha->check($post['captcha']) === false) {
                Session::set('res_msg', '验证码错误，请重试');
                header('location:' . $this->view->baseUrl() . '/page/feedback/');
                die();
            }

            // 检测是否存在注入
            foreach ($post as $key => $item) {
                if ($key != 'message') {
                    $safetyCheck = $this->params->checkInject($item);
                    if ($safetyCheck) {
                        throw new Exception('Client Injection detected.');
                    }
                }
            }

            $data = [
                'name' => strip_tags(trim($post['name'])),
                'contact' => strip_tags(trim($post['contact'])),
                'message' => trim($post['message'])
            ];

            $feedback = new \app\Model\Feedback();
            $res = $feedback->addFeedback($data);

            if ($res != false) {
                Session::set('res_msg', '提交成功！感谢您提交的反馈');
                header('location:' . $this->view->baseUrl() . '/page/feedback/');
            } else {
                echo 'Add failed.';
            }
            die();
        }

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $this->view->assign('res_msg', $res_msg);

        // 渲染并显示View
        $this->view->title = '提交反馈';
        $this->view->display();
    }
}
