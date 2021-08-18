<?php
namespace app\Controller;

use Mini\Base\{Action, Session, Exception};

include(APP_PATH . DS . 'Function' . DS . 'Extend.func.php');

/**
 * Resource控制器
 * 显示资源页面
 */
class Tutorial extends Action
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
        // 获取URL参数
        $param_page = $this->params->getParam('page');
        $param_per = $this->params->getParam('per');
        $page = isset($param_page) ? intval($param_page) : 1;
        $per = isset($param_per) ? intval($param_per) : TUTORIAL_PAGE_SIZE;

        // 实例化一个模型
        $tutorial = new \app\Model\Tutorial();
        
        // 调用模型中的方法
        $total_count = $tutorial->getTotalCount();
        if ($total_count <= TUTORIAL_PAGE_SIZE) {
            $total_page = 1;
            $page = 1;
        } else {
            $total_page =  intval(ceil($total_count / $per));
            if ($page > $total_page) {
                $page = $total_page;
            }
        }
        $tutorials = $tutorial->getAllTutorials($page, $per);
        foreach ($tutorials as $key => $item) {
            $tutorials[$key]['content'] = getExcerpt($item['content']);
        }

        $page_range = range(1, $total_page);

        // 渲染并显示View
        $this->view->assign('total_count', $total_count);
        $this->view->assign('total_page', $total_page);
        $this->view->assign('page_range', $page_range);
        $this->view->assign('page', $page);
        $this->view->assign('per', $per);
        $this->view->assign('tutorials', $tutorials);

        $this->view->title = '浏览教程';
        $this->view->display();
    }

    function viewAction()
    {
        $param_id = $this->params->getParam('id');
        $id = isset($param_id) ? intval($param_id) : 1;

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        // 实例化一个模型
        $tutorial = new \app\Model\Tutorial();
        $user = new \app\Model\User();

        $cur_tutorial = $tutorial->getTutorial($id);

        if (!$cur_tutorial) {
            header('location:' . $this->view->baseUrl() . '/tutorial/');
            die();
        }

        $authorised = false;
        if (Session::get('is_authenticated')) {
            $cur_user = $user->getUserOrBounce(Session::get('user_id'));
            if (Session::get('user_id') == $cur_tutorial['author_id'] || $cur_user['is_staff']) {
                $authorised = true;
            }
        }

        $author = $user->getUser($cur_tutorial['author_id']);
        $cur_tutorial['author_name'] = isset($author['nickname'])?$author['nickname']:$author['username'];

        $this->view->assign('res_msg', $res_msg);
        $this->view->assign('tutorial', $cur_tutorial);
        $this->view->assign('authorised', $authorised);

        $this->view->title = $cur_tutorial['title'];
        $this->view->display();
    }

    function addAction()
    {
        $is_authenticated = Session::get('is_authenticated');
        if (!$is_authenticated) {
            header('location:' . $this->view->baseUrl() . '/user/login/?next=' . $_SERVER['REQUEST_URI']);
            die();
        }

        $method = $this->_request->method();

        if ($method == 'POST') {
            $post = $this->params->_post;
            // dump($post);die();

            // 检测是否存在跨站攻击
            $csrf = $this->_request->checkCsrfToken();
            if (!$csrf) {
                Session::set('res_msg', 'CSRF校验未通过，请重试');
                header('location:' . $this->view->baseUrl() . '/tutorial/add/');
                die();
            }

            // 检测是否存在注入
            $safetyCheck = $this->params->checkInject($post['title']);
            if ($safetyCheck) {
                throw new Exception('Client Injection detected.');
            }

            $data = [
                'title' => strip_tags(trim($post['title'])),
                'category_id' => trim($post['category_id']),
                'content' => trim($post['content'])
            ];

            if ($_FILES['attachment_image']['size'] != 0) {

                // 使用Extend的imageHandler压缩上传图片并保存
                $path = PUBLIC_PATH . DS . 'uploads' . DS . 'tutorial' . DS . date('Y') . DS . date('M');
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
                    $data['preview_path'] = '/uploads/tutorial/' . date('Y') . '/' . date('M') . '/' . $filename;
                } else {
                    throw new Exception('Preview process error.');
                }
            } else {
                $data['preview_path'] = TUTORIAL_DEFAULT_PREVIEW;
            }

            $data['author_id'] = Session::get('user_id');
            // dump($data);die();

            $tutorial = new \app\Model\Tutorial();
            $res = $tutorial->addTutorial($data);

            if ($res != false) {
                Session::set('res_msg', '添加成功！');
                header('location:' . $this->view->baseUrl() . '/tutorial/view/id/' . $res . '/');
            } else {
                echo 'Add failed.';
            }
            die();
        }
        // 结束POST逻辑开始GET逻辑

        if (Session::is_set('res_msg')) {
            $res_msg = Session::get('res_msg');
            Session::set('res_msg', null);
        } else {
            $res_msg = 0;
        }

        $category = new \app\Model\Category();
        $categories = $category->getAllCategory();

        $this->view->assign('res_msg', $res_msg);
        $this->view->assign('categories', $categories);

        $this->view->title = '添加教程';
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

        $tutorial = new \app\Model\Tutorial();
        $user = new \app\Model\User();

        $cur_user = $user->getUserOrBounce(Session::get('user_id'));
        $cur_tutorial = $tutorial->getTutorial($id);

        $authorised = false;

        if (!empty($cur_tutorial)) {
            if (Session::get('user_id') == $cur_tutorial['author_id'] || $cur_user['is_staff']) {
                $authorised = true;
            }
        } else {
            echo 'Tutorial no more exists.';
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
                    header('location:' . $this->view->baseUrl() . '/tutorial/edit/id/' . $id . '/');
                    die();
                }

                // 检测是否存在注入
                $safetyCheck = $this->params->checkInject($post['title']);
                if ($safetyCheck) {
                    throw new Exception('Client Injection detected.');
                }

                $data = [
                    'title' => strip_tags(trim($post['title'])),
                    'category_id' => trim($post['category_id']),
                    'content' => trim($post['content'])
                ];

                if ($_FILES['attachment_image']['size'] != 0) {
                    // 先删除旧文件
                    $image_exist_path = PUBLIC_PATH . $cur_tutorial['preview_path'];
                    if (($cur_tutorial['preview_path'] != TUTORIAL_DEFAULT_PREVIEW) && file_exists($image_exist_path)) {
                        unlink($image_exist_path);
                    }

                    // 使用Extend的imageHandler压缩上传图片并保存
                    $path = PUBLIC_PATH . DS . 'uploads' . DS . 'tutorial' . DS . date('Y') . DS . date('M');
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
 
                    if ($img) {
                        $data['preview_path'] = '/uploads/tutorial/' . date('Y') . '/' . date('M') . '/' . $filename;
                    } else {
                        throw new Exception('Preview process error.');
                    }
                }

                $data['updated_date'] = date('Y-m-d H:i:s');

                $res = $tutorial->editTutorial($id, $data);

                if ($res != false) {
                    Session::set('res_msg', '修改成功！');
                    header('location:' . $this->view->baseUrl() . '/tutorial/view/id/' . $id . '/');
                } else {
                    echo 'Edit failed.';
                }
                die();
            }
            // 结束POST逻辑开始GET逻辑

            if (Session::is_set('res_msg')) {
                $res_msg = Session::get('res_msg');
                Session::set('res_msg', null);
            } else {
                $res_msg = 0;
            }

            $category = new \app\Model\Category();
            $categories = $category->getAllCategory();

            $this->view->assign('res_msg', $res_msg);
            $this->view->assign('categories', $categories);
            $this->view->assign('tutorial', $cur_tutorial);

            $this->view->title = '编辑教程';
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

        $tutorial = new \app\Model\Tutorial();
        $user = new \app\Model\User();

        $cur_tutorial = $tutorial->getTutorial($id);
        if (!$cur_tutorial) {
            echo 'Tutorial no more exists.';
            die();
        }

        $authorised = false;
        if (Session::get('is_authenticated')) {
            $cur_user = $user->getUserOrBounce(Session::get('user_id'));
            if (Session::get('user_id') == $cur_tutorial['author_id'] || $cur_user['is_staff']) {
                $authorised = true;
            }
        }

        if ($authorised) {
            $res = $tutorial->removePreview($id);
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

        $tutorial = new \app\Model\Tutorial();
        $user = new \app\Model\User();

        $cur_tutorial = $tutorial->getTutorial($id);
        if (!$cur_tutorial) {
            echo 'Tutorial no more exists.';
            die();
        }

        $authorised = false;
        if (Session::get('is_authenticated')) {
            $cur_user = $user->getUserOrBounce(Session::get('user_id'));
            if (Session::get('user_id') == $cur_tutorial['author_id'] || $cur_user['is_staff']) {
                $authorised = true;
            }
        }

        if ($authorised) {
            $res = $tutorial->delTutorial($id);
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
}
