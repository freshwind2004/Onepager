<?php

namespace app\Model;

use Mini\Base\Model;

/**
 * Onepager Comment
 * Onepager评论模型用来操作Onepager的评论。
 */
class Comment extends Model
{
    public function addComment($data)
    {
        $db = $this->useDb('default');

        $res = $this->table('comment')->data($data)->add();

        if ($res) {
            $res = $this->table('comment')->field('last_insert_id() as id')->select('Row');
            return $res['id'];
        }

        return $res;
    }

    public function getCommentsByOnepager($onepager_id, $page = 1, $per = 20)
    {
        $db = $this->useDb('default');

        $res = $this->table('comment')->where('onepager_id=' . $onepager_id)->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();
        
        if ($res) {
            foreach ($res as $key => $item) {
                $author = $this->table('user')->where('id=' . $item['author_id'])->select('Row');
                $res[$key]['author_name'] = isset($author['nickname'])?$author['nickname']:$author['username'];
            }
        }

        return $res;
    }

    public function getCommentsByTutorial($tutorial_id, $page = 1, $per = 20)
    {
        $db = $this->useDb('default');

        $res = $this->table('comment')->where('tutorial_id=' . $tutorial_id)->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();

        return $res;
    }

    public function getCommentsByUser($author_id, $page = 1, $per = 20)
    {
        $db = $this->useDb('default');

        $res = $this->table('comment')->where('author_id=' . $author_id)->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();

        return $res;
    }


    public function getAllComments($page = 1, $per = 10)
    {
        $db = $this->useDb('default');

        $res = $this->table('comment')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();

        return $res;
    }

    public function delComment($id)
    {
        $db = $this->useDb('default');
        
        $res = $this->table('comment')->where('id=' . $id)->delete();

        return $res;
    }

    public function getTotalCount($author_id = false)
    {
        $db = $this->useDb('default');
        if ($author_id != false) {
            $res = $this->table('comment')->where('author_id=' . $author_id)->field('COUNT(*) as num')->select('Row');
        } else {
            $res = $this->table('comment')->field('COUNT(*) as num')->select('Row');
        }
        // 查询后返回的是数组['num'=>String('123')]
        // dump($res);die();
        $count = (int)$res['num'];
        // 先转化为整数
        // dump($count);die();

        return $count;
    }

}
