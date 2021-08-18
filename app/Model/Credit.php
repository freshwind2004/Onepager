<?php
namespace app\Model;

use Mini\Base\Model;

/**
 * Credit模型
 * 记录用户奖励积分信息。
 */
class Credit extends Model
{
    public function addCredit($data)
    {
        $db = $this->useDb('default');

        $credit = $this->table('credit')->data($data)->add();
        
        $user = $this->table('user')->where('id=' . $data['user_id'])->select('Row');

        if (!$credit || !$user) {
            return false;
        }

        $credit = $user['credit'] + $data['credit'];

        $res = $this->table('user')->data(['credit' => $credit])->where('id=' . $data['user_id'])->save();

        if ($res) {
            return $credit;
        }
        return $res;
    }

    public function getCreditRecord($id=false, $order_id=false)
    {
        $db = $this->useDb('default');

        if ($id != false) {
            $res = $this->table('credit')->where('id=' . $id)->select('Row');
        } elseif ($order_id != false) {
            $res = $this->table('credit')->where('`order_id`="' . $order_id . '"')->select('Row');
        } else {
            $res = false;
        }

        return $res;
    }

    public function getLatestCreditRecord($user_id, $type=false, $onepager_id=false)
    {
        $db = $this->useDb('default');

        if ($type != false ) {
            if (in_array($type, ['Topup', 'Checkin', 'Comment', 'Onepager'])) {
                if ($type == 'Onepager' && $onepager_id != false) {
                    $res = $this->table('credit')->where('user_id=' . $user_id .' and `onepager_id` = "' . $onepager_id . '" and `type` = "' . $type . '"')->order(['id' => 'DESC'])->select('Row');
                } else {
                    $res = $this->table('credit')->where('user_id=' . $user_id . ' and `type` = "' . $type . '"')->order(['id' => 'DESC'])->select('Row');
                }
            } else {
                $res = false;
            }
        } else {
            $res = $this->table('credit')->where('user_id=' . $user_id)->limit()->select('Row');
        }

        return $res;
    }

    public function getCreditRecords($page = 1, $per = 20)
    {
        $db = $this->useDb('default');

        $res = $this->table('credit')->limit(($page - 1) * $per, $per)->order(['id' => 'DESC'])->select();

        return $res;
    }

    public function getCreditRecordsByUser($user_id, $page = 1, $per = 20, $type=false)
    {
        $db = $this->useDb('default');

        $res = $this->table('credit')->where('user_id =' . $user_id)->limit(($page - 1) * $per, $per)->order(['id' => 'DESC'])->select();

        return $res;
    }

    public function getTotalCount($user_id = false)
    {
        $db = $this->useDb('default');
        if ($user_id != false) {
            $res = $this->table('credit')->where('user_id=' . $user_id)->field('COUNT(*) as num')->select('Row');
        } else {
            $res = $this->table('credit')->field('COUNT(*) as num')->select('Row');
        }
        // 查询后返回的是数组['num'=>String('123')]
        // dump($res);die();
        $count = (int)$res['num'];
        // 先转化为整数
        // dump($count);die();

        return $count;
    }

}