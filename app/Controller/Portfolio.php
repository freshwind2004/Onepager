<?php

namespace app\Controller;

use Mini\Base\{Action, Exception, Session, Upload};

include(APP_PATH . DS . 'Function' . DS . 'Extend.func.php');

/**
 * 这是一个控制器的案例
 */
class Portfolio extends Action
{
    /**
     * 初始化
     */
    function _init()
    {
        include(APP_PATH . DS . 'Function' . DS . 'Islogged.inc.php');
        $this->view->_layout->setLayout('default');
    }

    function indexAction()
    {

        // 实例化一个模型
        $onepager = new \app\Model\Onepager();
        $category = new \app\Model\Category();

        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $param_per = $this->params->getParam('per');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = isset($param_per) ? intval($param_per) : ONEPAGER_PAGE_SIZE;
        // dump($page, $per);die();

        // 调用模型中的方法
        $total_count = $onepager->getTotalCount();
        if ($total_count <= ONEPAGER_PAGE_SIZE) {
            $total_page = 1;
            $page = 1;
        } else {
            $total_page =  intval(ceil($total_count / $per));
            if ($page > $total_page) {
                $page = $total_page;
            }
        }
        $onepagers = $onepager->getAllOnepagers($page, $per);

        $categories = $category->getAllCategory();
        // dump($categories);die();
        $category_list = [];
        foreach ($categories as $category) {
            $category_list[$category['id']] = $category['url'];
        }
        // dump($category_list);die();

        foreach ($onepagers as $key => $item) {
            if (isset($item['category_id']) && ($item['category_id'] != 0)) {
                $onepagers[$key]['category_url'] = $category_list[$item['category_id']];
            }
        }
        // dump($onepagers);die();

        $page_range = range(1, $total_page);

        // 向View传值
        $this->view->assign('categories', $categories);
        $this->view->assign('onepagers', $onepagers);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);

        $this->view->title = '浏览一页书';

        // 渲染并显示View
        $this->view->display();
    }

    function categoryAction()
    {
        // 获取URL参数
        $param_category = $this->params->getParam('show');
        $param_page = $this->params->getParam('page');
        $param_per = $this->params->getParam('per');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = isset($param_per) ? intval($param_per) : ONEPAGER_PAGE_SIZE;
        // dump($param_category,$param_page);die();

        // 实例化一个模型
        $category = new \app\Model\Category();
        $onepager = new \app\Model\Onepager();

        $cur_category = $category->getCategory($param_category);
        $onepagers = $onepager->getOnepagersByCategory($cur_category['id'], $page, $per);
        $total_count = $onepager->getTotalCount(false, $cur_category['id']);
        // 计算总页数时需要向上取整，取整是浮点，以此再转换为整数
        $total_page =  intval(ceil($total_count / $per));
        if ($total_page == 0) {
            $total_page = 1;
        }
        // dump($total_page);die();
        // dump($cur_transaction);die();

        $page_range = range(1, $total_page);

        $this->view->assign('category', $cur_category);
        $this->view->assign('onepagers', $onepagers);
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);

        $this->view->title = $cur_category['name'] . '分类';

        // 渲染并显示View
        $this->view->display();
    }

    function viewAction()
    {
        $param_id = $this->params->getParam('id');
        $param_page = $this->params->getParam('page');
        $id = isset($param_id) ? intval($param_id) : 1;
        $page = isset($param_page) ? intval($param_page) : 1;

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        // 实例化一个模型
        $onepager = new \app\Model\Onepager();
        $comment = new \app\Model\Comment();
        $user = new \app\Model\User();
        $transaction = new \app\Model\Transaction();

        $cur_onepager = $onepager->getOnepager($id);
        if (!$cur_onepager) {
            header('location:' . $this->view->baseUrl() . '/portfolio/');
            die();
        }

        $adj_onepagers =  $onepager->getAdjacentOnepagers($id);
        $related_onepagers =  $onepager->getRelatedOnepagers($id, RECO_POST);

        $authorised = false;
        $is_authenticated = Session::get('is_authenticated');
        if ($is_authenticated) {
            $cur_user = $user->getUserOrBounce(Session::get('user_id'));
            if (Session::get('user_id') == $cur_onepager['author_id'] || $cur_user['is_staff']) {
                $authorised = true;
            }
        }

        // 判断审核状态，如未审核且未授权则不显示
        $display = true;
        if (!$cur_onepager['reviewed'] && !$authorised) {
            $display = false;
        }

        $cur_transaction = $transaction->getTransaction(false, Session::get('user_id'), $id);
        // dump($cur_transaction);die();

        $cur_comments =  $comment->getCommentsByOnepager($id, $page);

        $randomkey = rand(1000, 9999);
        Session::set('encryptkey', $randomkey);
        $hash = base64_encode($randomkey * $cur_onepager['id']);

        $this->view->assign('res_msg', $res_msg);
        $this->view->assign('onepager', $cur_onepager);
        $this->view->assign('adj_onepagers', $adj_onepagers);
        $this->view->assign('related_onepagers', $related_onepagers);
        $this->view->assign('comments', $cur_comments);
        $this->view->assign('hash', $hash);
        $this->view->assign('transaction', $cur_transaction);
        $this->view->assign('authorised', $authorised);
        $this->view->assign('display', $display);
        $this->view->assign('is_authenticated', $is_authenticated);

        $this->view->title = $cur_onepager['title'];
        $this->view->display();
    }

    function addAction()
    {
        $user = new \app\Model\User();
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        $method = $this->_request->method();
        // dump($method);die();
        if ($method == 'POST') {
            $post = $this->params->_post;
            // dump($post);die();

            // 检测是否存在跨站攻击
            $csrf = $this->_request->checkCsrfToken();
            if (!$csrf) {
                Session::set('res_msg', 'CSRF校验未通过，请重试');
                header('location:' . $this->view->baseUrl() . '/portfolio/add/');
                die();
            }

            // 检测是否存在注入
            foreach ($post as $key => $item) {
                if ($key != 'description') {
                    $safetyCheck = $this->params->checkInject($item);
                    if ($safetyCheck) {
                        throw new Exception('Client Injection detected.');
                    }
                }
            }

            $data = [
                'title' => strip_tags(trim($post['title'])),
                'subtitle' => strip_tags(trim($post['subtitle'])),
                'category_id' => trim($post['category_id']),
                'price' => strip_tags(trim($post['price'])),
                'version' => strip_tags(trim($post['version'])),
                'description' => trim($post['description']),
                'copyright' => strip_tags(trim($post['copyright']))
            ];

            $upload = new Upload([
                'allowType' => 'bmp,gif,jpg,jpeg,png,doc,docx,xls,xlsx,pdf,zip',
                'savePath' => 'portfolio' . DS . date('Y') . DS . date('M'),
                'maxSize' => 5242880
            ]);
            // $upload->allowType = 'bmp,gif,jpg,jpeg,png,pdf,zip';
            // $upload->savePath = 'portfolio/' . date('Y') . '/' . date('M');

            $attachment_file = $upload->save($_FILES['attachment_file']);
            $attachment_err = $upload->getErrorMsg();
            if (isset($attachment_err)) {
                throw new Exception($attachment_err);
            }

            $data['attachment_filename'] = $_FILES['attachment_file']['name'];
            $data['attachment_path'] =  '/uploads/portfolio/' . date('Y') . '/' . date('M') . '/' . $attachment_file['fileName'];

            if ($_FILES['attachment_image']['size'] != 0) {
                // 使用Extend的imageHandler压缩上传图片并保存
                $path = PUBLIC_PATH . DS . 'uploads' . DS . $upload->savePath . DS . 'preview';
                if (!file_exists($path) && !is_dir($path)) {
                    if (!mkdir($path, 0700, true)) {
                        return false;
                    }
                }
                $originalname = $_FILES['attachment_image']['name'];
                $fileextname = strtolower(getFileExtName($originalname));
                $filename = getRandomString() . '.' . $fileextname;
                $fullpath = $path . DS . $filename;
                $width = 900;
                $height = 600;
                $img = imageHandler($_FILES['attachment_image']['tmp_name'], $fullpath, $width, $height);
                // 原保存方法
                // $preview_file = $upload->save($corpped);
                // $preview_err = $upload->getErrorMsg();
                if ($img) {
                    $data['preview_path'] = '/uploads/portfolio/' . date('Y') . '/' . date('M') . '/preview/' . $filename;
                } else {
                    throw new Exception('Preview process error.');
                }
            } else {
                $data['preview_path'] = ONEPAGER_DEFAULT_PREVIEW;
            }

            $data['author_id'] = Session::get('user_id');
            // dump($data);die();

            //查询用户是否为管理员并设置为已审核 
            if ($cur_user['is_staff']) {
                $data['reviewed'] = 1;
            }

            $onepager = new \app\Model\Onepager();
            $res = $onepager->addOnepager($data);

            if ($res != false) {
                Session::set('res_msg', '添加成功！');
                header('location:' . $this->view->baseUrl() . '/portfolio/view/id/' . $res . '/');
            } else {
                echo 'Add failed.';
            }
            die();
        }
        // 结束POST逻辑开始GET逻辑

        $category = new \app\Model\Category();
        $categories = $category->getAllCategory();


        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $this->view->assign('res_msg', $res_msg);
        $this->view->assign('categories', $categories);

        $this->view->title = '提交一页书';
        $this->view->display();
    }

    function editAction()
    {
        $id = $this->params->getParam('id');

        $is_authenticated = Session::get('is_authenticated');
        if (!$is_authenticated) {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        $onepager = new \app\Model\Onepager();
        $user = new \app\Model\User();

        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        $cur_onepager = $onepager->getOnepager($id);

        $authorised = false;

        if ($cur_onepager) {
            if (Session::get('user_id') == $cur_onepager['author_id'] || $cur_user['is_staff']) {
                $authorised = true;
            }
        } else {
            echo 'Onepager no more exists.';
            die();
        }

        if ($authorised) {
            $method = $this->_request->method();
            // dump($method);die();
            if ($method == 'POST') {
                $post = $this->params->_post;
                // dump($post);die();

                // 检测是否存在跨站攻击
                $csrf = $this->_request->checkCsrfToken();
                if (!$csrf) {
                    Session::set('res_msg', 'CSRF校验未通过，请重试');
                    header('location:' . $this->view->baseUrl() . '/portfolio/edit/id/' . $id . '/');
                    die();
                }

                // 检测是否存在注入
                foreach ($post as $key => $item) {
                    if ($key != 'description') {
                        $safetyCheck = $this->params->checkInject($item);
                        if ($safetyCheck) {
                            throw new Exception('Client Injection detected.');
                        }
                    }
                }

                $data = [
                    'title' => strip_tags(trim($post['title'])),
                    'subtitle' => strip_tags(trim($post['subtitle'])),
                    'category_id' => trim($post['category_id']),
                    'price' => strip_tags(trim($post['price'])),
                    'version' => strip_tags(trim($post['version'])),
                    'description' => trim($post['description']),
                    'copyright' => strip_tags(trim($post['copyright']))
                ];

                $upload = new Upload([
                    'allowType' => 'bmp,gif,jpg,jpeg,png,doc,docx,xls,xlsx,pdf,zip',
                    'savePath' => 'portfolio' . DS . date('Y') . DS . date('M'),
                    'maxSize' => 5242880
                ]);

                if ($_FILES['attachment_file']['size'] != 0) {
                    // 先删除旧文件
                    $file_exist_path = PUBLIC_PATH . $cur_onepager['attachment_path'];
                    if (file_exists($file_exist_path)) {
                        unlink($file_exist_path);
                    }

                    // 开始保存新文件
                    $attachment_file = $upload->save($_FILES['attachment_file']);
                    $attachment_err = $upload->getErrorMsg();
                    if (isset($attachment_err)) {
                        throw new Exception($attachment_err);
                    }

                    $data['attachment_filename'] = $_FILES['attachment_file']['name'];
                    $data['attachment_path'] =  '/uploads/portfolio/' . date('Y') . '/' . date('M') . '/' . $attachment_file['fileName'];
                }


                if ($_FILES['attachment_image']['size'] != 0) {
                    // 先删除旧文件
                    $image_exist_path = PUBLIC_PATH . $cur_onepager['preview_path'];
                    if (($cur_onepager['preview_path'] != ONEPAGER_DEFAULT_PREVIEW) && file_exists($image_exist_path)) {
                        unlink($image_exist_path);
                    }

                    // 使用Extend的imageHandler压缩上传图片并保存
                    $path = PUBLIC_PATH . DS . 'uploads' . DS . $upload->savePath . DS . 'preview';
                    if (!file_exists($path) && !is_dir($path)) {
                        if (!mkdir($path, 0700, true)) {
                            return false;
                        }
                    }
                    $originalname = $_FILES['attachment_image']['name'];
                    $fileextname = strtolower(getFileExtName($originalname));
                    $filename = getRandomString() . '.' . $fileextname;
                    $fullpath = $path . DS . $filename;
                    $width = 900;
                    $height = 600;
                    $img = imageHandler($_FILES['attachment_image']['tmp_name'], $fullpath, $width, $height);
                    // 原保存方法
                    // $preview_file = $upload->save($corpped);
                    // $preview_err = $upload->getErrorMsg();
                    if ($img) {
                        $data['preview_path'] = '/uploads/portfolio/' . date('Y') . '/' . date('M') . '/preview/' . $filename;
                    } else {
                        throw new Exception('Preview process error.');
                    }
                }

                $data['updated_date'] = date('Y-m-d H:i:s');

                $res = $onepager->editOnepager($id, $data);

                if ($res != false) {
                    Session::set('res_msg', '修改成功！');
                    header('location:' . $this->view->baseUrl() . '/portfolio/view/id/' . $id . '/');
                } else {
                    echo 'Edit failed.';
                }
                die();
            }
            // 结束POST逻辑开始GET逻辑
            $category = new \app\Model\Category();
            $categories = $category->getAllCategory();

            if (Session::is_set('res_msg')) {
                $res_msg = Session::get('res_msg');
                Session::set('res_msg', null);
            } else {
                $res_msg = 0;
            }

            $this->view->assign('res_msg', $res_msg);
            $this->view->assign('onepager', $cur_onepager);
            $this->view->assign('categories', $categories);
            $this->view->title = '编辑一页书';
            $this->view->display();
        } else {
            echo 'Not authorised.';
            die();
        }
    }

    // RemovePreview 移除预览图
    function repreAction()
    {
        $id = $this->params->getParam('id');

        $onepager = new \app\Model\Onepager();
        $user = new \app\Model\User();

        $cur_onepager = $onepager->getOnepager($id);
        if (!$cur_onepager) {
            echo 'Onepager no more exists.';
            die();
        }

        $authorised = false;
        if (Session::get('is_authenticated')) {
            $cur_user = $user->getUserOrBounce(Session::get('user_id'));
            if (Session::get('user_id') == $cur_onepager['author_id'] || $cur_user['is_staff']) {
                $authorised = true;
            }
        }

        if ($authorised) {
            $res = $onepager->removePreview($id);
            if ($res) {
                $data = [
                    'msg' => '删除成功！',
                    'status' => 'success'
                ];
            } else {
                $data = [
                    'msg' => '文件删除，数据库更新失败',
                    'status' => 'success'
                ];
            }
        } else {
            $data = [
                'msg' => '没有权限',
                'status' => 'failed'
            ];
        }
        pushJson($data);
    }

    function delAction()
    {
        $id = $this->params->getParam('id');

        $onepager = new \app\Model\Onepager();
        $user = new \app\Model\User();

        $cur_onepager = $onepager->getOnepager($id);
        if (!$cur_onepager) {
            echo 'Onepager no more exists.';
            die();
        }

        $authorised = false;
        if (Session::get('is_authenticated')) {
            $cur_user = $user->getUserOrBounce(Session::get('user_id'));
            if (Session::get('user_id') == $cur_onepager['author_id'] || $cur_user['is_staff']) {
                $authorised = true;
            }
        }

        if ($authorised) {
            $res = $onepager->delOnepager($id);
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
                'msg' => '没有权限',
                'status' => 'failed'
            ];
        }
        pushJson($data);
    }

    function reviewAction()
    {
        $is_authenticated = Session::get('is_authenticated');
        if (!$is_authenticated) {
            echo 'Login required.';
            die();
        }

        $id = $this->params->getParam('id');

        $onepager = new \app\Model\Onepager();
        $user = new \app\Model\User();

        $cur_onepager = $onepager->getOnepager($id);
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        if (!$cur_onepager) {
            echo 'Onepager no more exists.';
            die();
        }

        if (!$cur_user['is_staff']) {
            echo 'You have no privilege.';
            die();
        }
        
        $switch = ($cur_onepager['reviewed'])?'hide':'show';
        $res = $onepager->updateReviewStatus($cur_onepager['id'], $switch);

        $credit = new \app\Model\Credit();
        $existCredit = $credit->getLatestCreditRecord($cur_onepager['author_id'], 'Onepager', $cur_onepager['id']);

        if (!$existCredit) {
            $credit_amount = mt_rand(20, 30);
            $data = [
                'user_id' => $cur_onepager['author_id'],
                'onepager_id' => $cur_onepager['id'],
                'type' => 'Onepager',
                'credit' => $credit_amount,
                'note' => '一页书审核通过奖励 ' . $credit_amount . ' 个积分'
            ];
            // dump($data);die();
            $credit_res = $credit->addCredit($data);    
        }

        if ($res) {
            $data = [
                'msg' => '审核状态已更新！',
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

    function buyAction()
    {
        $id = $this->params->getParam('id');

        $is_authenticated = Session::get('is_authenticated');
        if (!$is_authenticated) {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        $onepager = new \app\Model\Onepager();
        $user = new \app\Model\User();
        $transaction = new \app\Model\Transaction();

        $cur_onepager = $onepager->getOnepager($id);
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        if (!$cur_onepager) {
            echo 'Onepager no more exists.';
            die();
        }

        if ($cur_onepager['price'] == 0) {
            echo 'Onepager is free.';
            die();
        }

        if (Session::get('user_id') == $cur_onepager['author_id'] || $cur_user['is_staff']) {
            echo 'You need no purchase.';
            die();
        }

        $res = $transaction->getTransaction(false, Session::get('user_id'), $cur_onepager['id']);
        if ($res) {
            echo 'You already bought this item.';
            die();
        }

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $this->view->assign('res_msg', $res_msg);
        $this->view->assign('onepager', $cur_onepager);
        $this->view->assign('user', $cur_user);
        $this->view->title = '购买一页书';
        $this->view->display();
    }

    function checkoutAction()
    {
        $is_authenticated = Session::get('is_authenticated');
        if (!$is_authenticated) {
            echo 'Login required.';
            die();
        }

        $id = $this->params->getParam('id');

        $onepager = new \app\Model\Onepager();
        $user = new \app\Model\User();
        $transaction = new \app\Model\Transaction();

        $cur_onepager = $onepager->getOnepager($id);
        $cur_user = $user->getUserOrBounce(Session::get('user_id'));

        if (!$cur_onepager) {
            echo 'Onepager no more exists.';
            die();
        }

        if ($cur_onepager['price'] == 0) {
            echo 'Onepager is free.';
            die();
        }

        if (Session::get('user_id') == $cur_onepager['author_id'] || $cur_user['is_staff']) {
            echo 'You need no purchase.';
            die();
        }

        $res = $transaction->getTransaction(false, Session::get('user_id'), $cur_onepager['id']);
        if ($res) {
            echo 'You already bought this item.';
            die();
        }

        if ($cur_user['credit'] >= $cur_onepager['price']) {
            $buyer_update = $user->updateCredit(Session::get('user_id'), $cur_onepager['price'], 'buyer');
            $seller_update = $user->updateCredit($cur_onepager['author_id'], $cur_onepager['price'], 'seller');
            $record_create = $transaction->createTransaction([
                'buyer_id' => Session::get('user_id'),
                'seller_id' => $cur_onepager['author_id'],
                'onepager_id' => $cur_onepager['id'],
                'amount' => $cur_onepager['price']
            ]);

            $res = $buyer_update && $seller_update && $record_create;

            if ($res) {
                $data = [
                    'msg' => '购买成功！',
                    'status' => 'success'
                ];
            } else {
                $data = [
                    'msg' => '购买失败',
                    'status' => 'failed'
                ];
            }
        } else {
            $data = [
                'msg' => '没有足够信用点，请先充值',
                'status' => 'failed'
            ];
        }
        pushJson($data);
    }

    function downloadAction()
    {
        $hash = $this->params->getParam('hash');
        if (empty(Session::get('encryptkey'))) {
            echo 'Download link expired.';
            die();
        } else {
            $id = base64_decode($hash) / Session::get('encryptkey');
            if (gettype($id) != 'integer') {
                echo 'Download link expired.';
                die();
            }
            Session::set('encryptkey', null);
        }
        // dump($id, gettype($id));die();

        $onepager = new \app\Model\Onepager();
        $cur_onepager = $onepager->getOnepager($id);

        if ($cur_onepager == false) {
            echo 'Download link expired.';
            die();
        }

        $file = PUBLIC_PATH . $cur_onepager['attachment_path'];
        $filename = $cur_onepager['attachment_filename'];

        if (file_exists($file)) {
            $onepager->updateDownloadCount($id);
            header('Content-length: ' . filesize($file));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
            @readfile($file);
        } else {
            echo 'Download Failed!';
        }
    }

    function commentAction()
    {
        $id = $this->params->getParam('id');

        $is_authenticated = Session::get('is_authenticated');
        if (!$is_authenticated) {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        $method = $this->_request->method();

        if (empty($id) || $method != 'POST') {
            echo 'Error request.';
            die();
        }

        $post = $this->params->_post;

        // 检测是否存在跨站攻击
        $csrf = $this->_request->checkCsrfToken();
        if (!$csrf) {
            Session::set('res_msg', 'CSRF校验未通过，请重试');
            header('location:' . $this->view->baseUrl() . '/portfolio/edit/id/' . $id . '/');
            die();
        }

        // 检测是否存在注入
        $safetyCheck = $this->params->checkInject($post['content']);
        if ($safetyCheck) {
            throw new Exception('Client Injection detected.');
        }

        $data = [
            'content' => strip_tags(trim($post['content'])),
            'author_id' => Session::get('user_id'),
            'onepager_id' => $id
        ];

        $comment = new \app\Model\Comment();
        $user = new \app\Model\User();
        $credit = new \app\Model\Credit();
        
        $res = $comment->addComment($data);

        $credit_amount = mt_rand(2, 5);
        $data = [
            'user_id' => Session::get('user_id'),
            'type' => 'Comment',
            'credit' => $credit_amount,
            'note' => '发表评论奖励 ' . $credit_amount . ' 个积分'
        ];
        // dump($data);die();
        $credit_res = $credit->addCredit($data);

        if ($res && $credit_res) {
            Session::set('res_msg', '评论发布成功！奖励了 ' . $credit_amount . ' 个积分');
        } else {
            Session::set('res_msg', '评论失败');
        }

        header('location:' . $this->view->baseUrl() . '/portfolio/view/id/' . $id . '/');
        die();
    }
}
