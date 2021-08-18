<?php

namespace app\Controller;

use Mini\Base\{Action, Session};

include(APP_PATH . DS . 'Function' . DS . 'Alipay.inc.php');

/**
 * 积分控制器
 */
class Credit extends Action
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
     * 打卡积分操作
     */
    function checkinAction()
    {
        if (!Session::is_set('is_authenticated')) {
            echo 'Login required.';
            die();
        }

        $credit = new \app\Model\Credit();
        $user = new \app\Model\User();

        $lastCheckin = $credit->getLatestCreditRecord(Session::get('user_id'), 'Checkin');
        // dump($lastCheckin);die();
        $credit_amount = mt_rand(5, 10);
        if (substr($lastCheckin['created_date'], 0, 10) == date('Y-m-d')) {
            $json = [
                'msg' => '今天已打卡，明天再来吧',
                'status' => 'failed'
            ];
        } else {
            $data = [
                'user_id' => Session::get('user_id'),
                'type' => 'Checkin',
                'credit' => $credit_amount,
                'note' => '签到奖励 ' . $credit_amount . ' 个积分'
            ];
            // dump($data);die();
            $res = $credit->addCredit($data);

            if ($res) {
                $json = [
                    'msg' => '打卡成功，今天奖励了 ' . $credit_amount . ' 个积分',
                    'credit' => $res,
                    'status' => 'success'
                ];
            } else {
                $json = [
                    'msg' => '打卡失败，请稍后重试',
                    'status' => 'failed'
                ];
            }
        }
        pushJson($json);
    }

    /**
     * 查看积分记录
     */
    function recordsAction()
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
        $credit = new \app\Model\Credit();
        $onepager = new \app\Model\Onepager();

        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        // 获得我的购买记录
        $my_credit_records = $credit->getCreditRecordsByUser(Session::get('user_id'), $page, $per);
        foreach ($my_credit_records as $key => $item) {
            if (!empty($item['onepager_id'])) {
                $cur_onepager = $onepager->getOnepager($item['onepager_id']);
                $my_credit_records[$key]['onepager_title'] = $cur_onepager['title'];
            }
        }

        $total_count = $credit->getTotalCount(Session::get('user_id'));
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
        $this->view->assign('user', $cur_user);
        $this->view->assign('my_credit_records', $my_credit_records);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);

        $this->view->title = '积分记录';
        $this->view->display();
    }

    /**
     * 充值
     */
    function topupAction()
    {
        if (Session::is_set('is_authenticated')) {
            $this->view->assign('username', Session::get('username'));
        } else {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));


        $this->view->assign('user', $cur_user);

        $this->view->title = '积分充值';
        $this->view->display();
    }

    /**
     * 支付
     */
    function payAction()
    {
        if (!Session::is_set('is_authenticated')) {
            echo 'Login required.';
            die();
        }

        $param_amount = $this->params->getParam('amount');
        $amount = isset($param_amount) ? intval($param_amount) : 10;
        $user_id = Session::get('user_id');
        $order_id = date('ymd') . substr(uniqid(), 7, 6) . str_pad(dechex($user_id), 6, "0", STR_PAD_LEFT);

        $topup = new \app\Model\Topup();
        $data = [
            'order_id' => $order_id, //订单号
            'alipay_order_id' => '', //流水号空，等待返回
            'user_id' => $user_id, //用户ID
            'money' => 0, //实际付款金额空，等待返回，可能有小数出现
            'price' => $amount, //订单的原价
            'credit' => 0, //相应的积分空，等待返回
            'params' => '', //备份返回params空，等待返回
        ];
        $res = $topup->addTopup($data);

        alipay_url($order_id, $user_id, $amount);
    }

    /**
     * 支付完成(异步)通知地址
     */
    function notifyAction()
    {
        // 补单通知(POST)内容示例
        // array(19) {
        //     ["id"] => string(8) "92507289"
        //     ["userID"] => string(6) "619405"
        //     ["pay_no"] => string(17) "codepay1610342873"
        //     ["pay_id"] => string(18) "2101118ef6f3000001"
        //     ["money"] => string(2) "10"
        //     ["price"] => string(2) "10"
        //     ["endTime"] => string(10) "1610343056"
        //     ["mode"] => string(1) "0"
        //     ["param"] => string(1) "1"
        //     ["pay_name"] => string(7) "codepay"
        //     ["pay_time"] => string(10) "1610342873"
        //     ["qr_user"] => string(1) "0"
        //     ["status"] => string(1) "0"
        //     ["tag"] => string(1) "0"
        //     ["trade_no"] => string(28) "1161034269616194053645619366"
        //     ["trueID"] => string(6) "619405"
        //     ["type"] => string(1) "1"
        //     ["codepay_server_time"] => string(10) "1610342873"
        //     ["sign"] => string(32) "4896360bc00a1624800f424d4f1872e1"
        //   }
        $method = $this->_request->method();
        if ($method != 'POST') {
            echo 'Ivalid request.';
            die();
        }

        $post = $this->params->_post;
        // dump($post);die();
        $return = codepay_return_verify($post);
        if ($return == 'success') {
            $topup = new \app\Model\Topup();
            $credit = new \app\Model\Credit();
            // 充值金额向上取整得到积分数量
            $topup_credit = intval(ceil($post['money'] * RATE));

            $existTopup = $topup->getTopupRecord(false, $post['pay_id']);
            if ($existTopup) {
                if (empty($existTopup['alipay_order_id'])) {
                    $topup_data = [
                        'alipay_order_id' => $post['pay_no'], //流水号
                        'paid' => 1, //支付状态标签
                        'money' => (float)$post['money'], //实际付款金额
                        'credit' => $topup_credit, //相应的积分
                        'paid_date' => date('Y-m-d H:i:s'), //支付时间
                        'params' => print_r($post, true) //备份返回params
                    ];
                    $topup_res = $topup->updateTopup($existTopup['id'], $topup_data);
                    if ($topup_res) {
                        $existCredit = $credit->getCreditRecord(false, $post['pay_id']);
                        if (!$existCredit) {
                            $credit_data = [
                                'user_id' => $post['param'],
                                'order_id' => $post['pay_id'],
                                'type' => 'Topup',
                                'credit' => $topup_credit,
                                'note' => '用户充值 RMB' . $post['money'] . ' 元，获得 ' . $topup_credit . ' 个积分'
                            ];
                            // dump($data);die();

                            $credit_res = $credit->addCredit($credit_data);
                            if ($credit_res) {
                                die('success');
                            }
                        }
                    }
                } else {
                    die('duplicate');
                }
            }
        }
        die('fail');
    }


    /**
     * 支付完成(同步)跳转通知地址
     */
    function returnAction()
    {
        // 通知(GET)内容示例
        // array(21) {
        //     ["id"] => string(12) "136972141499"
        //     ["userID"] => string(6) "619405"
        //     ["pay_no"] => string(28) "2021011022001462001427323069"
        //     ["pay_id"] => string(18) "2101105ffafe000001"
        //     ["price"] => string(4) "1.00"
        //     ["param"] => string(1) "1"
        //     ["status"] => string(1) "0"
        //     ["trade_no"] => string(28) "1161028454716194056456748397"
        //     ["endTime"] => string(10) "1610284907"
        //     ["trueID"] => string(6) "619405"
        //     ["mode"] => string(1) "0"
        //     ["notify_count"] => string(1) "0"
        //     ["qr_user"] => string(1) "0"
        //     ["ok"] => string(1) "1"
        //     ["type"] => string(1) "1"
        //     ["money"] => string(4) "1.01"
        //     ["pay_time"] => string(10) "1610284585"
        //     ["tag"] => string(1) "0"
        //     ["target"] => string(3) "get"
        //     ["codepay_server_time"] => string(10) "1610284585"
        //     ["sign"] => string(32) "0084da76439e16801c34ae4a719bee0e"
        //   }
        $params = $this->params->getParams();
        // dump($params);die();
        $return = codepay_return_verify($params);
        // dump($return);die();
        $topup = new \app\Model\Topup();
        $credit = new \app\Model\Credit();
        $existTopup = $topup->getTopupRecord(false, $params['pay_id']);
        $existCredit = $credit->getCreditRecord(false, $params['pay_id']);

        $topup_credit = intval(ceil($params['money'] * RATE));
        if ($return == 'success') {
            if ($existCredit && $existTopup) {
                Session::set('res_msg', '充值成功！您充值了RMB ' . $params['money'] . ' 元，获得了 ' . $topup_credit . ' 个积分');
            } else {
                Session::set('res_msg', '充值成功！请等待系统更新您的积分信息');
            }
        } else {
            $info = isset($params['pay_id']) ? '订单' . $params['pay_id'] . '未支付成功' : '未返回订单号，用户ID ' . Session::get('user_id');
            Session::set('res_msg', '充值失败请重试：' .  $info);
        }
        header('location:' . $this->view->baseUrl() . '/credit/records/');
        die();
    }
}
