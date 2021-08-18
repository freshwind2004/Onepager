<?php

use Mini\Base\Session;
use app\Model\User;

if (!empty($_COOKIE['uid']) && !empty($_COOKIE['idf'])) {
    $user = new User();
    // 检查用户是否有登录cookie
    $user->cookieLogin(base64_decode(str_replace('#', '=', $_COOKIE['uid'])), $_COOKIE['idf']);
}

if (Session::is_set('is_authenticated') && Session::is_set('username')) {
    $this->view->assign('is_authenticated', true);
    $this->view->assign('user_id', Session::get('user_id'));
    $this->view->assign('username', Session::get('username'));
} else {
    $this->view->assign('is_authenticated', false);
}
