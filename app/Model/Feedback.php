<?php
namespace app\Model;

use Mini\Base\Model;

/**
 * 交易模型
 * 
 */
class Feedback extends Model
{
    public function addFeedback($data)
    {
        $db = $this->useDb('default');
        
        $res = $this->table('feedback')->data($data)->add();
        
        return $res;
    }

    public function getFeedback($id)
    {
        $db = $this->useDb('default');
        
        $res = $this->table('feedback')->where('id=' . $id)->select('Row');

        return $res;
    }

    public function getFeedbacks($page = 1, $per = 10)
    {
        $db = $this->useDb('default');
        
        $res = $this->table('feedback')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();

        return $res;
    }

    public function delFeedback($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('feedback')->where('id=' . $id)->delete();

        return $res;
    }

    public function updateFeedbackStatus($id, $switch)
    {
        $db = $this->useDb('default');

        $update = $this->table('feedback')->where('id=' . $id)->data(['status' => $switch])->save();

        return $update;
    }

    public function getTotalCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('feedback')->field('COUNT(*) as num')->select('Row');

        $count = (int)$res['num'];

        return $count;
    }
}
