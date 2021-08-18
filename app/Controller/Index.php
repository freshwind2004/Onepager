<?php
namespace app\Controller;

use Mini\Base\{Action, Session};


/**
 * 这是一个控制器的案例
 */
class Index extends Action
{
    /**
     * 初始化
     */
    function _init()
    {
        include(APP_PATH . DS . 'Function' . DS . 'Islogged.inc.php');
        $this->view->title = 'Home';
        // 使用布局，需在入口文件 Public/index.php 中定义常量 LAYOUT_ON 的值为 true
        $this->view->_layout->setLayout('default');
        // $this->view->_layout->header = $this->view->render(LAYOUT_PATH . '/header.php');
    }
    
    /**
     * 默认动作
     */
    function indexAction()
    {
               
        // 实例化一个模型
        $onepager = new \app\Model\Onepager();
    
        // 调用模型中的方法
        $onepagers = $onepager->getAllOnepagers(1, 9);
        
        // 向View传值
        $this->view->assign('onepagers', $onepagers);
        
        // 渲染并显示View
        $this->view->display();
    }
}
