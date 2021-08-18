<?php
namespace app\Model;

use Mini\Base\Model;

/**
 * Topup模型
 * 记录用户充值信息。
 */
class Topup extends Model
{
    public function addTopup($data)
    {
        $db = $this->useDb('default');

        $res = $this->table('topup')->data($data)->add();
        
        return $res;
    }

    public function updateTopup($topup_id, $data)
    {
        $db = $this->useDb('default');

        $res = $this->table('topup')->where('id=' . $topup_id)->select('Row');

        if (!$res) {
            return false;
        }

        $updated = $this->table('topup')->data($data)->where('id=' . $topup_id)->save();

        return $updated;
    }

    public function getTopupRecord($id=false, $order_id=false)
    {
        $db = $this->useDb('default');

        if ($id != false) {
            $res = $this->table('topup')->where('id=' . $id)->select('Row');
        } elseif ($order_id != false) {
            $res = $this->table('topup')->where('`order_id`="' . $order_id . '"')->select('Row');
        } else {
            return false;
        }

        return $res;
    }

    public function getTopupRecords($page = 1, $per = 10, $user_id=false)
    {
        $db = $this->useDb('default');

        if ($user_id != false) {
            $res = $this->table('topup')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->where('user_id=' . $user_id)->select();
        } else {
            $res = $this->table('topup')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();
        }

        return $res;
    }

    public function delTopupRecord($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('topup')->where('id=' . $id)->delete();

        return $res;
    }
    
    public function getTotalCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('topup')->field('COUNT(*) as num')->select('Row');
        // 查询后返回的是数组['num'=>String('123')]
        $count = (int)$res['num'];
        // 先转化为整数

        return $count;
    }

    public function getTopupSummary()
    {
        $db = $this->useDb('default');

        $res = $this->table('topup')->field('sum(money) as sum')->select('Row');

        $sum = round($res['sum'], 2);

        return $sum;
    }
}