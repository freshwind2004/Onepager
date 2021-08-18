<?php
namespace app\Model;

use Mini\Base\Model;

/**
 * 交易模型
 * 
 */
class Transaction extends Model
{
    public function getTransaction($id=false, $user_id=false, $onepager_id=false)
    {
        $db = $this->useDb('default');
        
        if ($id != false) {
            $res = $this->table('transaction')->where('id=' . $id)->select('Row');
        } elseif (($user_id != false) && ($onepager_id != false)) {
            $res = $this->table('transaction')->where('buyer_id=' . $user_id . ' and ' . 'onepager_id=' . $onepager_id)->select('Row');
        } else {
            $res = false;
        }
        
        return $res;
    }

    public function getMyTransactions($user_id, $identity='buyer', $page = 1, $per = 10)
    {
        $db = $this->useDb('default');
        
        if (in_array($identity, ['buyer', 'seller'])) {
            $res = $this->table('transaction')->where($identity . '_id=' . $user_id)->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();
        } else {
            $res = false;
        }

        return $res;
    }

    public function createTransaction($data)
    {
        $db = $this->useDb('default');
        
        $res = $this->table('transaction')->data($data)->add();
        
        return $res;
    }

    public function getTotalCount($buyer_id = false)
    {
        $db = $this->useDb('default');
        if ($buyer_id != false) {
            $res = $this->table('transaction')->where('buyer_id=' . $buyer_id)->field('COUNT(*) as num')->select('Row');
        } else {
            $res = $this->table('transaction')->field('COUNT(*) as num')->select('Row');
        }
        // 查询后返回的是数组['num'=>String('123')]
        // dump($res);die();
        $count = (int)$res['num'];
        // 先转化为整数
        // dump($count);die();

        return $count;
    }
}
