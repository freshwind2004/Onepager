<?php

namespace app\Model;

use Mini\Base\Model;

/**
 * Onepager
 * Onepager模型用来操作Onepager数据类。
 */
class Onepager extends Model
{
    public function addOnepager($data)
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->data($data)->add();

        if ($res) {
            $res = $this->table('onepager')->field('last_insert_id() as id')->select('Row');
            return $res['id'];
        }

        return $res;
    }

    public function editOnepager($id, $data)
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->data($data)->where('id=' . $id)->save();

        return $res;
    }

    public function getOnepager($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->where('id=' . $id)->select('Row');

        if ($res) {
            $category = $this->table('category')->where('id=' . $res['category_id'])->select('Row');
            $author = $this->table('user')->where('id=' . $res['author_id'])->select('Row');
            // dump($author);die();
            $res['category_name'] = $category['name'];
            $res['category_url'] = $category['url'];
            $res['author_name'] = isset($author['nickname']) ? $author['nickname'] : $author['username'];
        }

        return $res;
    }

    public function getAdjacentOnepagers($id)
    {
        $db = $this->useDb('default');

        $next = $this->table('onepager')->where('id<' . $id . ' and reviewed = 1')->limit(1)->order(array('id' => 'DESC'))->select('Row');
        $previous = $this->table('onepager')->where('id>' . $id . ' and reviewed = 1')->limit(1)->order(array('id' => 'DESC'))->select('Row');

        $res = [
            'previous' => $previous,
            'next' => $next
        ];

        return $res;
    }

    public function getRelatedOnepagers($id, $limit=5)
    {
        $db = $this->useDb('default');

        $op = $this->table('onepager')->where('id=' . $id)->select('Row');

        $res = $this->table('onepager')->where('category_id=' . (int)$op['category_id'] . ' and id <>' . $id . ' and reviewed = 1')->limit($limit)->order(array('id' => 'DESC'))->select();

        return $res;
    }

    public function getOnepagersByUser($author_id, $page = 1, $per = 10)
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->where('author_id=' . $author_id)->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();

        return $res;
    }

    public function getOnepagersByCategory($category_id, $page = 1, $per = 10)
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->where('category_id=' . $category_id . ' and reviewed = 1')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();

        return $res;
    }

    public function getAllOnepagers($page = 1, $per = 10, $manage = false)
    {
        $db = $this->useDb('default');

        if ($manage) {
            $res = $this->table('onepager')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();
        } else {
            $res = $this->table('onepager')->where('reviewed = 1')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();
        }

        return $res;
    }

    public function delOnepager($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->where('id=' . $id)->select('Row');

        if ($res) {
            // 删除文件和预览图
            if (file_exists(PUBLIC_PATH . $res['attachment_path'])) {
                unlink(PUBLIC_PATH . $res['attachment_path']);
            }
            if (($res['preview_path'] != ONEPAGER_DEFAULT_PREVIEW) && file_exists(PUBLIC_PATH . $res['preview_path'])) {
                unlink(PUBLIC_PATH . $res['preview_path']);
            }

            $del = $this->table('onepager')->where('id=' . $id)->delete();
            return $del;
        }

        return $res;
    }

    public function removePreview($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->where('id=' . $id)->select('Row');

        if ($res) {
            // 预览图
            if (($res['preview_path'] != ONEPAGER_DEFAULT_PREVIEW) && file_exists(PUBLIC_PATH . $res['preview_path'])) {
                unlink(PUBLIC_PATH . $res['preview_path']);
            }
            $update = $this->table('onepager')->data(['preview_path' => ONEPAGER_DEFAULT_PREVIEW])->where('id=' . $id)->save();
            return $update;
        }

        return $res;
    }

    public function getTotalCount($author_id = false, $category_id = false, $switch='Reviewed')
    {
        $db = $this->useDb('default');

        if (in_array($switch, ['All', 'Reviewed'])) {
            if ($switch == 'All') {
                $sql_addon = '';
            } else {
                $sql_addon = ' and reviewed=1';
            }
        }

        if ($author_id != false) {
            $res = $this->table('onepager')->where('author_id=' . $author_id . $sql_addon)->field('COUNT(*) as num')->select('Row');
        } elseif ($category_id != false) {
            $res = $this->table('onepager')->where('category_id=' . $category_id . $sql_addon)->field('COUNT(*) as num')->select('Row');
        } else {
            if ($switch == 'All') {
                $res = $this->table('onepager')->field('COUNT(*) as num')->select('Row');
            } else {
                $res = $this->table('onepager')->where('reviewed=1')->field('COUNT(*) as num')->select('Row');
            }
        }
        // 查询后返回的是数组['num'=>String('123')]
        // dump($res);die();
        $count = (int)$res['num'];
        // 先转化为整数
        // dump($count);die();

        return $count;
    }

    public function updateReviewStatus($id, $review_status)
    {
        $db = $this->useDb('default');

        $switch = ($review_status == 'show')?1:0;
        $update = $this->table('onepager')->where('id=' . $id)->data(['reviewed' => $switch])->save();

        return $update;
    }

    public function updateDownloadCount($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->where('id=' . $id)->select('Row');
        $count = $res['download_count'] + 1;

        $update = $this->table('onepager')->where('id=' . $id)->data(['download_count' => $count])->save();

        return $update;
    }

    public function getPaidOnepagerCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->where('price > 0')->field('COUNT(*) as num')->select('Row');
        // 查询后返回的是数组['num'=>String('123')]
        $count = (int)$res['num'];
        // 先转化为整数

        return $count;
    }

    public function getDownloadSummary()
    {
        $db = $this->useDb('default');

        $res = $this->table('onepager')->field('sum(download_count) as sum')->select('Row');

        $sum = (empty($res['sum']))?0:$res['sum'];

        return $sum;
    }
}
