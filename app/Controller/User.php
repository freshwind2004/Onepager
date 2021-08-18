<?php

namespace app\Controller;

use Mini\Base\{Action, Exception, Session};
use Mini\Captcha\Captcha;

/**
 * 账户相关操作
 */
class User extends Action
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
     * 用户控制面板（后台首页）
     */
    function indexAction()
    {

        if (!Session::is_set('is_authenticated')) {
            header('location:' . $this->view->baseUrl() . '/user/login/');
            die();
        }

        // 获取URL参数
        $user = new \app\Model\User();
        $transaction = new \app\Model\Transaction();
        $onepager = new \app\Model\Onepager();
        $category = new \app\Model\Category();
        $credit = new \app\Model\Credit();

        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        if ($cur_user['is_staff']) {
            $managed_ops =  $onepager->getAllOnepagers(1, 10, true);
            $op_count = $onepager->getTotalCount(false, false, 'All');
            // 展示站点昨天和今天统计内容（使用文件缓存，每天首次触发时更新）
            include(APP_PATH . DS . 'Function' . DS . 'Stats.inc.php');
            $today_stats = today_stats();
            $yesterday_stats = yesterday_stats();
            $this->view->assign('today_stats', $today_stats);
            $this->view->assign('yesterday_stats', $yesterday_stats);
        } else {
            $managed_ops =  $onepager->getOnepagersByUser(Session::get('user_id'), 1, 10);
            $op_count = $onepager->getTotalCount(Session::get('user_id'));
        }

        // 获得我的购买记录
        $transactions = $transaction->getMyTransactions(Session::get('user_id'), 'buyer', 1, 10);
        $bought_count = $transaction->getTotalCount(Session::get('user_id'));
        $bought_ops = [];
        foreach ($transactions as $key => $item) {
            $bought_ops[$key] = $onepager->getOnepager($item['onepager_id']);
            $bought_ops[$key]['bought_on'] = $transactions[$key]['created_date'];
        }

        // 读取全部类别
        $categories = $category->getAllCategory();
        // dump($categories);die();
        $category_list = [];
        foreach ($categories as $category) {
            $category_list[$category['id']] = $category['name'];
        }

        // 拓展$managed_ops使其拥有分类名和作者名
        foreach ($managed_ops as $key => $item) {
            if (isset($item['category_id']) && ($item['category_id'] != 0)) {
                $managed_ops[$key]['category_name'] = $category_list[$item['category_id']];
            }
            $author = $user->getUser($item['author_id']);
            if ($author) {
                $managed_ops[$key]['author_name'] = isset($author['nickname']) ? $author['nickname'] : $author['username'];
            } else {
                $managed_ops[$key]['author_name'] = '未知';
            }
        }

        // 检查签到记录
        $lastCheckin = $credit->getLatestCreditRecord(Session::get('user_id'), 'Checkin');
        $checked = false;
        if (!empty($lastCheckin) && substr($lastCheckin['created_date'], 0, 10) == date('Y-m-d')) {
            $checked = true;
        }
        $op_award = $credit->getLatestCreditRecord(Session::get('user_id'), 'Onepager');
        if ($op_award) {
            $related_op = $onepager->getOnepager($op_award['onepager_id']);
            $op_award['onepager_title'] = $related_op['title'];
        }

        // 调试csrf
        // $clientCsrfToken = $this->_request->loadCsrfToken('cookie');
        // $serverCsrfToken = $this->_request->loadCsrfToken('session');
        // $checkCsrf = $this->_request->checkCsrfToken();
        // dump([$clientCsrfToken, $serverCsrfToken, $checkCsrf]);die();

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $this->view->assign('res_msg', $res_msg);

        $this->view->assign('user', $cur_user);
        $this->view->assign('bought_ops', $bought_ops);
        $this->view->assign('managed_ops', $managed_ops);
        $this->view->assign('op_count', $op_count);
        $this->view->assign('bought_count', $bought_count);
        $this->view->assign('checked', $checked);
        $this->view->assign('op_award', $op_award);
        $this->view->title = '用户面板';
        $this->view->display();
    }

    /**
     * 我的资料展示
     */
    function profileAction()
    {

        if (Session::is_set('is_authenticated')) {
            $this->view->assign('username', Session::get('username'));
        } else {
            header('location:' . $this->view->baseUrl() . '/user/login/');
            die();
        }

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        $this->view->assign('user', $cur_user);
        $this->view->assign('res_msg', $res_msg);

        $this->view->title = '修改资料';
        $this->view->display();
    }

    /**
     * 我的资料修改接口
     */
    function updateAction()
    {
        $post = $this->params->_post;
        //dump($post);die();

        // 检测是否存在跨站攻击
        $csrf = $this->_request->checkCsrfToken();
        if (!$csrf) {
            Session::set('res_msg', 'CSRF校验未通过，请重试');
            header('location:' . $this->view->baseUrl() . '/user/profile/');
            die();
        }

        // 检测是否存在注入
        foreach ($post as $item) {
            $safetyCheck = $this->params->checkInject($item);
            if ($safetyCheck) {
                throw new Exception('Client Injection detected.');
            }
        }

        $user = new \app\Model\User();

        $data = [
            'email' => trim($post['email']),
            'mobile' => trim($post['mobile']),
            'nickname' => trim($post['nickname']),
            'sex' => trim($post['sex'])
        ];

        $res = $user->updateUser(Session::get('user_id'), $data);
        //dump($res);die();

        // if ($res) {
        //     Session::set('res_msg', '资料更新成功！');
        // } else {
        //     Session::set('res_msg', '资料未更新');
        // }
        // 上面等价于下面的三元运算

        $res ? Session::set('res_msg', '资料更新成功！') : Session::set('res_msg', '资料未更新');

        header('location:' . $this->view->baseUrl() . '/user/profile/');

        die();
    }

    /**
     * 账户密码修改接口
     */
    function changepwdAction()
    {
        $post = $this->params->_post;
        //dump($post);die();

        $csrf = $this->_request->checkCsrfToken();
        if (!$csrf) {
            Session::set('res_msg', 'CSRF校验未通过，请重试');
            header('location:' . $this->view->baseUrl() . '/user/profile/');
            die();
        }

        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user) {
            Session::set('res_msg', '用户状态错误');
            header('location:' . $this->view->baseUrl() . '/user/profile/');
            die();
        }

        // 校验密码
        $failed = false;

        if (!isset($post['oldpassword']) || empty($post['oldpassword'])) {
            Session::set('res_msg', '请填写原密码后重试');
            $failed = true;
        }
        if (md5($post['oldpassword'] . $cur_user['salt']) != $cur_user['password']) {
            Session::set('res_msg', '原密码不正确，密码未更新');
            $failed = true;
        }
        if (!isset($post['password']) || empty($post['password'])) {
            Session::set('res_msg', '请填写新密码后重试');
            $failed = true;
        }
        if ($post['password'] != $post['password2']) {
            Session::set('res_msg', '两次输入的新密码不相符，密码未更新');
            $failed = true;
        }

        if ($failed) {
            header('location:' . $this->view->baseUrl() . '/user/profile/');
            die();
        }

        $data = [
            'password' => trim($post['password']),
        ];

        $res = $user->changepwd(Session::get('user_id'), $data);
        $res ? Session::set('res_msg', '密码更新成功！') : Session::set('res_msg', '密码未更新');

        header('location:' . $this->view->baseUrl() . '/user/profile/');

        die();
    }

    /**
     * 新增账号
     */
    function registerAction()
    {
        if ($this->_request->method() == 'POST') {
            /**
             * 新增账号数据保存接口
             */
            $post = $this->params->_post;
            //dump($post);die();

            $captcha = new Captcha();
            if (!isset($post['captcha']) || $captcha->check($post['captcha']) === false) {
                Session::set('res_msg', '验证码错误，请重试');
                header('location:' . $this->view->baseUrl() . '/user/register/');
                die();
            }

            // 校验基本资料
            $failed = false;

            if (!isset($post['username']) || empty($post['username'])) {
                Session::set('res_msg', '请填写用户名');
                $failed = true;
            }
            if ($this->params->checkInject($post['username'])) {
                Session::set('res_msg', '用户名包含有不被允许的字符');
                $failed = true;
            }
            if ($this->params->checkInject($post['email'])) {
                Session::set('res_msg', '电邮地址包含有不被允许的字符');
                $failed = true;
            }
            if (!isset($post['password']) || empty($post['password'])) {
                Session::set('res_msg', '请填写密码');
                $failed = true;
            }
            if ($post['password'] != $post['password2']) {
                Session::set('res_msg', '两次输入的密码不相符');
                $failed = true;
            }

            $user = new \app\Model\User();

            $data = [
                'username' => trim($post['username']),
                'password' => trim($post['password']),
                'email' => trim($post['email'])
            ];

            $existUser = $user->getUser(false, $data['username']);

            if (isset($existUser['username'])) {
                Session::set('res_msg', '用户名已被占用');
                $failed = true;
            }

            if ($failed) {
                header('location:' . $this->view->baseUrl() . '/user/register/');
                die();
            }

            $res = $user->addUser($data);

            if ($res) {
                Session::set('res_msg', '欢迎！您的账户已经注册成功');
                header('location:' . $this->view->baseUrl() . '/user/');
                die();
            } else {
                Session::set('res_msg', '出现未知错误');
            }

            header('location:' . $this->view->baseUrl() . '/user/register/');
            die();
        }
        // 结束POST逻辑开始GET逻辑

        if (Session::is_set('is_authenticated')) {
            header('location:' . $this->view->baseUrl() . '/user/');
            die();
        }

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $this->view->assign('res_msg', $res_msg);
        $this->view->_layout->setLayout('default');

        $this->view->title = '注册账号';
        $this->view->display();
    }

    /**
     * 调整用户权限 
     */
    function promoAction()
    {
        if (!Session::is_set('is_authenticated')) {
            echo 'Illegal request.';
            die();
        }

        $user = new \app\Model\User();
        $id = $this->params->getParam('id');
        $target_user = $user->getUser($id);
        if (!$target_user) {
            echo 'User no more exists.';
            die();
        }

        $switch = ($target_user['is_staff']) ? 0 : 1;

        // 检查用户是否有管理权限
        if (Session::get('user_id') == 1) {
            if ($target_user['id'] != 1) {
                $res = $user->updateUserPrivilege($target_user['id'], $switch);
                if ($res) {
                    $data = [
                        'msg' => '权限调整成功！',
                        'status' => $switch
                    ];
                } else {
                    $data = [
                        'msg' => '调整失败',
                        'status' => 'failed'
                    ];
                }
            } else {
                $data = [
                    'msg' => '骚瑞，你不能调整自己的权限',
                    'status' => 'failed'
                ];
            }
        } else {
            $data = [
                'msg' => '骚瑞，你不是超级管理员，不能调整权限',
                'status' => 'failed'
            ];
        }

        pushJson($data);
    }

    /**
     * 删除用户账号 
     */
    function delAction()
    {
        if (!Session::is_set('is_authenticated')) {
            echo 'Illegal request.';
            die();
        }

        // 检查用户是否有管理权限
        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }

        $id = $this->params->getParam('id');

        $target_user = $user->getUser($id);
        if (!$target_user) {
            echo 'User no more exists.';
            die();
        }

        if ($target_user['id'] != 1) {
            if ($cur_user['id'] != $id) {
                $res = $user->delUser($id);
                if ($res) {
                    $data = [
                        'msg' => '删除成功！',
                        'status' => 'success'
                    ];
                } else {
                    $data = [
                        'msg' => '删除失败',
                        'status' => 'failed'
                    ];
                }
            } else {
                $data = [
                    'msg' => '骚瑞，你不能删除自己',
                    'status' => 'failed'
                ];
            }
        } else {
            $data = [
                'msg' => '骚瑞，你不能删除超级管理员',
                'status' => 'failed'
            ];
        }

        pushJson($data);
    }

    /**
     * 登录页
     */
    function loginAction()
    {
        if (Session::is_set('is_authenticated')) {
            header('location:' . $this->view->baseUrl() . '/user/');
            die();
        }

        $method = $this->_request->method();

        if ($method == 'POST') {
            $post = $this->params->_post;
            // dump($post);die();
            $csrf = $this->_request->checkCsrfToken();

            if (!$csrf) {
                header('location:' . $this->view->baseUrl() . '/user/login/?errcode=4');
                die();
            }

            if (isset($post['username']) && isset($post['password'])) {
                if (empty($post['username']) || empty($post['password'])) {
                    header('location:' . $this->view->baseUrl() . '/user/login/?errcode=1');
                    die();
                }
                if ($this->params->checkInject($post['username'])) {
                    header('location:' . $this->view->baseUrl() . '/user/login/?errcode=2');
                    die();
                }
                $captcha = new Captcha();
                if (!isset($post['captcha']) || $captcha->check($post['captcha']) === false) {
                    header('location:' . $this->view->baseUrl() . '/user/login/?errcode=3');
                    die();
                }
            } else {
                header('location:' . $this->view->baseUrl() . '/user/login/?errcode=1');
                die();
            }

            $user = new \app\Model\User();
            $res = $user->login($post['username'], $post['password']);
            //dump($res);die();
            if ($res === false) {
                header('location:' . $this->view->baseUrl() . '/user/login/?errcode=1');
                die();
            }

            if (!empty($post['remember'])) {
                $cur_user = $user->getUser(false, $post['username']);
                setcookie('uid', str_replace('=', '#', base64_encode($post['username'])), time() + 3600 * 24 * 14, '/');
                setcookie('idf', md5($post['username'] . $cur_user['salt'] . COOKIES_SALT), time() + 3600 * 24 * 14, '/');
            }

            $next = $this->params->getParam('next');
            // dump($next);die();
            if (!empty($next)) {
                header('location:' . $this->view->baseUrl() . $next);
            } else {
                header('location:' . $this->view->baseUrl() . '/user/');
            }
            die();
        }
        // 结束POST逻辑开始GET逻辑

        $errcode = $this->params->getParam('errcode');
        if (empty($errcode)) {
            $errcode = 0;
        }
        $this->assign('errcode', $errcode);

        // $cache = \Mini\Cache\Cache::factory ('Memcached',
        //     [
        //         'host'      => 'localhost', //主机
        //         'port'      => 11211        //端口
        //     ]
        // );

        // //写入一个名为 test 的缓存，值为 abc，有效时间为 3600 秒
        // $cache->set('test', 'abc', 3600);

        // //读取名为 test 的缓存
        // $test = $cache->get('test');
        // dump($test);die();

        $this->view->title = '登录';
        $this->view->display();
    }

    /**
     * 登出操作
     */
    function logoutAction()
    {
        Session::set('is_authenticated', false);
        Session::set('username', null);
        Session::destroy();
        if (!empty($_COOKIE['uid']) || !empty($_COOKIE['idf'])) {
            setcookie('uid', null, time() - 86400, '/');
            setcookie('idf', null, time() - 86400, '/');
        }
        header('location:' . $this->view->baseUrl() . '/');
        die();
    }
}
