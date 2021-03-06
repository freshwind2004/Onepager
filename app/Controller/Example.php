<?php
namespace app\Controller;

use Mini\Base\{Action, Session, Upload, Log, Debug};
use Mini\Captcha\Captcha;

include (APP_PATH . DS . 'Function' . DS . 'Extend.func.php');

/**
 * Example
 */
class Example extends Action
{
    /**
     * Example 1: Captcha
     */
    function captchaAction()
    {
        if (!empty($_POST['code'])) {
            $captcha = new Captcha();
            $res = $captcha->check($_POST['code']);
            if ($res) {
                $this->view->assign('info', 'success');
            } else {
                $this->view->assign('info', 'fail');
            }
            $this->view->assign('code', $_POST['code']);
        }
        
        $this->view->display();
    }
    
    function getcaptchaAction()
    {
        $captcha = new Captcha();
        $captcha->create();
    }
    
    /**
     * Example 2: Session
     */
    function sessionAction()
    {
        $t = time();
        $this->view->assign('t', $t);
        
        Session::start();
        if (! Session::is_set('example_session')) {
            Session::set('example_session', $t);
        }
        
        $this->view->assign('session_time', Session::get('example_session'));
        
        $this->view->display();
    }
    
    /**
     * Example 3: Upload
     */
    function uploadAction()
    {
        if (! empty($_FILES)) {

            $path = PUBLIC_PATH . DS . 'uploads' . DS . date('Y') . DS . date('m');

            if (! file_exists($path) && ! is_dir($path)) {
                if (! mkdir($path, 0700, true)) {
                    return false;
                }
            }

            $oriName = $_FILES['f1']['name'];
            $fileExtName = strtolower(getFileExtName($oriName));

            $filename = md5($oriName) . '.' . $fileExtName;

            $width = 900;
            $height = 600;

            $img = imageHandler($_FILES['f1']['tmp_name'], $path . DS . $filename, $width, $height);
                      
            $upload = new Upload();
            $upload->savePath =  date('Y') . DS . date('m');
            $res = $upload->save($_FILES); // or $_FILES['f1']
            
            // echo "<br />ErrorMsg:";
            // $errmsg = $upload->getErrorMsg();
            // dump($errmsg);
            
            // echo "<br />Result:";
            dump($img);
            dump($res);
        }
        
        $this->view->display();
    }
    
    /**
     * Example 4: Log
     */
    function logAction()
    {
        $message = 'This is a log test.';

        // ?????? LOG_ON ??? true ????????????????????????????????????????????????????????????????????????
        Log::record($message, 'INFO', ['file'=>__FILE__, 'line'=>__LINE__]);
        
    }
    
    /**
     * Example 5: Debug(timer)
     */
    function debugtimerAction()
    {
        // ????????????
        Debug::timerStart();
        
        sleep(1);
        
        // ?????????????????????
        Debug::timerPoint();
        
        sleep(1);
        
        // ???????????????????????????
        Debug::timerPoint();
        
        sleep(1);
        
        // ????????????
        Debug::timerEnd();
        
        // ?????? dump ?????????????????????
        Debug::getTimerRecords(true);
        
        die();
    }
    
    /**
     * Example 6: Sign
     */
    function signAction()
    {
        // ??????????????????
        $data = [
            'info' => 'MiniFramework',
            
            // signTime?????????????????????????????????????????????????????????
            'signTime' => time()
        ];
        
        $signObj = new \Mini\Security\Sign();
        
        // ??????????????????
        $sign = $signObj->sign($data);
        
        // ?????????????????????????????????GET??????
        $data['sign'] = $sign;
        dump($data);
        
        // ????????????GET??????URL
        $tmp = [];
        foreach ($data as $key => $val) {
            $tmp[] = $key . '=' . $val;
        }
        $dataStr = implode('&', $tmp);
        $url = $this->view->baseUrl() . '/example/verifysign?' . $dataStr;
        echo '<a href="' . $url . '" target="_blank">Click to verify sign</a>';
        
        die();
    }
    
    /**
     * Example 7: Verify Sign
     */
    function verifysignAction()
    {
        $signObj = new \Mini\Security\Sign();
        
        // ???????????????????????????30??????????????????300??????
        $signObj->setExpireTime(30);
        
        // ???????????????????????????????????????get?????????GET????????????????????????
        $res = $signObj->verifySign('get');
        dump($res);
        
        die();
    }
}
