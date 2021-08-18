<?php

namespace app\Controller;

use Mini\Base\{Action, Exception, Session};

/**
 * 管理操作
 */
class Manage extends Action
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
     * 管理首页
     */
    function indexAction()
    {
        header('location:' . $this->view->baseUrl() . '/user/');
        die();
    }

    /**
     * 一页书管理（管理员全局界面）
     */
    function onepagerAction()
    {
        if (Session::is_set('is_authenticated')) {
            $this->view->assign('username', Session::get('username'));
        } else {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = 25;

        $user = new \app\Model\User();
        $onepager = new \app\Model\Onepager();
        $category = new \app\Model\Category();

        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        if ($cur_user['is_staff']) {
            $managed_ops =  $onepager->getAllOnepagers($page, $per, true);
            $total_count = $onepager->getTotalCount(false, false, 'All');
        } else {
            $managed_ops =  $onepager->getOnepagersByUser(Session::get('user_id'), $page, $per);
            $total_count = $onepager->getTotalCount(Session::get('user_id'));
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
                $managed_ops[$key]['author_name'] = isset($author['nickname'])?$author['nickname']:$author['username'];
            } else {
                $managed_ops[$key]['author_name'] = '未知';
            }
        }

        $total_page =  intval(ceil($total_count / $per));
        if ($total_page == 0) {
            $total_page = 1;
        }
        $page_range = range(1, $total_page);

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
        $this->view->assign('managed_ops', $managed_ops);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);

        $this->view->title = '管理一页书';
        $this->view->display();
    }

    /**
     * 教程管理（管理员全局界面）
     */
    function tutorialAction()
    {
        if (Session::is_set('is_authenticated')) {
            $this->view->assign('username', Session::get('username'));
        } else {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = 25;

        $user = new \app\Model\User();
        $tutorial = new \app\Model\Tutorial();
        $category = new \app\Model\Category();

        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }

        $tutorials =  $tutorial->getAllTutorials($page, $per);
        $total_count = $tutorial->getTotalCount();

        // 读取全部类别
        $categories = $category->getAllCategory();
        // dump($categories);die();
        $category_list = [];
        foreach ($categories as $category) {
            $category_list[$category['id']] = $category['name'];
        }

        // 拓展$managed_ops使其拥有分类名和作者名
        foreach ($tutorials as $key => $item) {
            $tutorials[$key]['category_name'] = $category_list[$item['category_id']];
            $author = $user->getUser($item['author_id']);
            if ($author) {
                $tutorials[$key]['author_name'] = isset($author['nickname'])?$author['nickname']:$author['username'];
            } else {
                $tutorials[$key]['author_name'] = '未知';
            }
        }

        $total_page =  intval(ceil($total_count / $per));
        if ($total_page == 0) {
            $total_page = 1;
        }
        $page_range = range(1, $total_page);

        $this->view->assign('user', $cur_user);
        $this->view->assign('tutorials', $tutorials);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);

        $this->view->title = '教程管理';
        $this->view->display();
    }

    /**
     * 资源管理（管理员全局界面）
     */
    function resourceAction()
    {
        if (Session::is_set('is_authenticated')) {
            $this->view->assign('username', Session::get('username'));
        } else {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = 25;

        $user = new \app\Model\User();
        $resource = new \app\Model\Resource();

        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }

        $resources =  $resource->getAllResources($page, $per);
        $total_count = $resource->getTotalCount();

        // 拓展$resources使其拥有作者名
        foreach ($resources as $key => $item) {
            $author = $user->getUser($item['author_id']);
            if ($author) {
                $resources[$key]['author_name'] = isset($author['nickname'])?$author['nickname']:$author['username'];
            } else {
                $resources[$key]['author_name'] = '未知';
            }
        }

        $total_page =  intval(ceil($total_count / $per));
        if ($total_page == 0) {
            $total_page = 1;
        }
        $page_range = range(1, $total_page);

        $this->view->assign('user', $cur_user);
        $this->view->assign('resources', $resources);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);

        $this->view->title = '资源管理';
        $this->view->display();
    }

    /**
     * 分类管理操作
     */
    function categoryAction()
    {
        if (!Session::is_set('is_authenticated')) {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 检查用户是否有管理权限
        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }

        $category = new \app\Model\Category();

        $method = $this->_request->method();
        
        if ($method == 'POST') {
            $post = $this->params->_post;
            $csrf = $this->_request->checkCsrfToken();

            if (!$csrf) {
                Session::set('res_msg', '检测到CSRF错误，请重试.');
                header('location:' . $this->view->baseUrl() . '/manage/category/');
                die();
            }

            if (empty($post['name']) || empty($post['url'])) {
                Session::set('res_msg', '请填写分类名和URL');
                header('location:' . $this->view->baseUrl() . '/manage/category/');
                die();
            }

            $data = [
                'name' => strip_tags(trim($post['name'])),
                'url' => strtolower(strip_tags(trim($post['url']))),
                'description' => strip_tags(trim($post['description']))
            ];
            // dump($data);die();

            $existCategory = $category->getCategory($data['url']);

            if ($existCategory) {
                Session::set('res_msg', '分类URL重复，添加失败');
            } else {
                $res = $category->addCategory($data);
                if ($res) {
                    Session::set('res_msg', '分类添加成功！');
                } else {
                    Session::set('res_msg', '添加失败');
                }
            }
        }

        $categories = $category->getAllCategory(false, true);
        $total_count = $category->getTotalCount();

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $this->view->assign('res_msg', $res_msg);
        $this->view->assign('categories', $categories);
        $this->view->assign('total_count', $total_count);
        $this->view->title = '分类管理';
        $this->view->display();
    }

    /**
     * 删除分类操作
     */
    function delcateAction()
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

        $category = new \app\Model\Category();
        $cur_category = $category->getCategory(false, $id);
        if (!$cur_category) {
            echo 'Category no more exists.';
            die();
        }

        $res = $category->delCategory($id);
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

        pushJson($data);
    }
    
    /**
     * 查看已购项目
     */
    function boughtAction()
    {
        if (Session::is_set('is_authenticated')) {
            $this->view->assign('username', Session::get('username'));
        } else {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = 25;

        $user = new \app\Model\User();
        $transaction = new \app\Model\Transaction();
        $onepager = new \app\Model\Onepager();

        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        // 获得我的购买记录
        $transactions = $transaction->getMyTransactions(Session::get('user_id'), 'buyer', $page, $per);
        $bought_ops = [];
        foreach ($transactions as $key => $item) {
            $bought_ops[$key] = $onepager->getOnepager($item['onepager_id']);
            $bought_ops[$key]['bought_on'] = $transactions[$key]['created_date'];
        }

        $total_count = $transaction->getTotalCount(Session::get('user_id'));
        $total_page =  intval(ceil($total_count / $per));
        if ($total_page == 0) {
            $total_page = 1;
        }
        $page_range = range(1, $total_page);

        $this->view->assign('user', $cur_user);
        $this->view->assign('bought_ops', $bought_ops);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);

        $this->view->title = '已购项目';
        $this->view->display();
    }

    /**
     * 管理全站充值记录
     */
    function topupAction()
    {
        if (Session::is_set('is_authenticated')) {
            $this->view->assign('username', Session::get('username'));
        } else {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 检查用户是否有管理权限
        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }

        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = 25;

        $topup = new \app\Model\Topup();

        // 获得我的购买记录
        $topups = $topup->getTopupRecords($page, $per);
        foreach ($topups as $key => $item) {
            $related_user = $user->getUser($item['user_id']);
            $topups[$key]['user_name'] = isset($related_user['nickname'])?$related_user['nickname']:$related_user['username'];
        }

        $total_count = $topup->getTotalCount();
        $total_page =  intval(ceil($total_count / $per));
        if ($total_page == 0) {
            $total_page = 1;
        }
        $page_range = range(1, $total_page);

        $this->view->assign('user', $cur_user);
        $this->view->assign('topups', $topups);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);

        $this->view->title = '管理充值';
        $this->view->display();
    }

    /**
     * 删除充值记录操作（仅能删除未支付成功）
     */
    function deltopupAction()
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

        $topup = new \app\Model\Topup();
        $cur_topup = $topup->getTopupRecord($id);
        if (!$cur_topup) {
            echo 'Topup no more exists.';
            die();
        }

        if ($cur_topup['paid'] == 0) {
            $res = $topup->delTopupRecord($id);
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
                'msg' => '已支付订单禁止删除',
                'status' => 'failed'
            ];
        }

        pushJson($data);
    }

    /**
     * 管理匿名反馈
     */
    function feedbackAction()
    {
        if (Session::is_set('is_authenticated')) {
            $this->view->assign('username', Session::get('username'));
        } else {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 检查用户是否有管理权限
        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }

        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = 25;

        $feedback = new \app\Model\Feedback();

        // 获得我的购买记录
        $feedbacks = $feedback->getFeedbacks($page, $per);

        $total_count = $feedback->getTotalCount();
        $total_page =  intval(ceil($total_count / $per));
        if ($total_page == 0) {
            $total_page = 1;
        }
        $page_range = range(1, $total_page);

        $this->view->assign('user', $cur_user);
        $this->view->assign('feedbacks', $feedbacks);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);

        $this->view->title = '管理充值';
        $this->view->display();
    }

    /**
     * 删除反馈操作
     */
    function delfbAction()
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

        $feedback = new \app\Model\Feedback();
        $cur_feedback = $feedback->getFeedback($id);
        if (!$cur_feedback) {
            echo 'Feedback no more exists.';
            die();
        }

        $res = $feedback->delFeedback($id);
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

        pushJson($data);
    }

    /**
     * 反馈标记
     */
    function markfbAction()
    {
        $is_authenticated = Session::get('is_authenticated');
        if (!$is_authenticated) {
            echo 'Login required.';
            die();
        }

        $id = $this->params->getParam('id');

        $feedback = new \app\Model\Feedback();
        $user = new \app\Model\User();

        $cur_feedback = $feedback->getFeedback($id);
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        if (!$cur_feedback) {
            echo 'Feedback no more exists.';
            die();
        }

        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }
        
        $switch = ($cur_feedback['status'])?0:1;
        $res = $feedback->updateFeedbackStatus($cur_feedback['id'], $switch);

        if ($res) {
            $data = [
                'msg' => '反馈状态已更新！',
                'status' => $switch
            ];
        } else {
            $data = [
                'msg' => '操作失败',
                'status' => 'failed'
            ];
        }

        pushJson($data);
    }

    /**
     * 用户管理操作
     */
    function userAction()
    {
        if (!Session::is_set('is_authenticated')) {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 检查用户是否有管理权限
        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }

        $method = $this->_request->method();
        
        if ($method == 'POST') {
            $post = $this->params->_post;
            $csrf = $this->_request->checkCsrfToken();

            if (!$csrf) {
                Session::set('res_msg', '检测到CSRF错误，请重试.');
                header('location:' . $this->view->baseUrl() . '/manage/user/');
                die();
            }

            if (empty($post['username']) || empty($post['password'])) {
                Session::set('res_msg', '请填写用户名和密码');
                header('location:' . $this->view->baseUrl() . '/manage/user/');
                die();
            }

            if ($post['password'] == $post['password2']) {
                $data = [
                    'username' => strip_tags(trim($post['username'])),
                    'password' => strip_tags(trim($post['password']))
                ];
                // dump($data);die();
                
                $existUser = $user->getUser(false, $data['username']);
    
                if ($existUser) {
                    Session::set('res_msg', '用户名重复，添加失败');
                } else {
                    $res = $user->addUserByAdmin($data);
                    if ($res) {
                        Session::set('res_msg', '用户添加成功！');
                    } else {
                        Session::set('res_msg', '添加失败');
                    }
                }
            } else {
                Session::set('res_msg', '两次密码不匹配，添加失败');
            }
        }

        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = 25;

        $onepager = new \app\Model\Onepager();

        $users = $user->getAllUsers($page, $per);
        foreach ($users as $key => $item) {
            $users[$key]['count'] = $onepager->getTotalCount($item['id'], false, 'All');
        }
        $total_count = $user->getTotalCount();

        $total_page =  intval(ceil($total_count / $per));
        if ($total_page == 0) {
            $total_page = 1;
        }
        $page_range = range(1, $total_page);

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $this->view->assign('res_msg', $res_msg);
        $this->view->assign('users', $users);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);
        $this->view->title = '用户管理';
        $this->view->display();
    }


    /**
     * 刷新管理员统计数据
     */
    function refreshAction() {
        if (!Session::is_set('is_authenticated')) {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        // 检查用户是否有管理权限
        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }

        $cache = \Mini\Cache\Cache::factory('File');
        $cache->del('stats_' . date('Ymd'));

        header('location:' . $this->view->baseUrl() . '/user/');
        die();
    }
}
