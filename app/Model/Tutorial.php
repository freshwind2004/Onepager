<?php
namespace app\Model;

use Mini\Base\Model;


/**
 * Tutorial模型
 * 操作教程数据模型
 */
class Tutorial extends Model
{

    public function getTutorial($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('tutorial')->where('id=' . $id)->select('Row');

        if ($res) {
            $category = $this->table('category')->where('id=' . $res['category_id'])->select('Row');
            $res['category_name'] = $category['name'];
        }

        return $res;
    }

    public function getAllTutorials($page = 1, $per = 10)
    {
        $db = $this->useDb('default');

        $res = $this->table('tutorial')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();
        
        return $res;
    }

    public function addTutorial($data)
    {
        $db = $this->useDb('default');

        $res = $this->table('tutorial')->data($data)->add();

        if ($res) {
            $res = $this->table('tutorial')->field('last_insert_id() as id')->select('Row');
            return $res['id'];
        }

        return $res;
    }

    public function editTutorial($id, $data)
    {
        $db = $this->useDb('default');

        $res = $this->table('tutorial')->data($data)->where('id=' . $id)->save();

        return $res;
    }

    public function delTutorial($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('tutorial')->where('id=' . $id)->select('Row');

        if ($res) {
            // 删除预览图
            if (($res['preview_path'] != TUTORIAL_DEFAULT_PREVIEW) && file_exists(PUBLIC_PATH . $res['preview_path'])) {
                unlink(PUBLIC_PATH . $res['preview_path']);
            }

            $del = $this->table('tutorial')->where('id=' . $id)->delete();
            return $del;
        }

        return $res;
    }

    public function removePreview($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('tutorial')->where('id=' . $id)->select('Row');

        if ($res) {
            // 预览图
            if (($res['preview_path'] != TUTORIAL_DEFAULT_PREVIEW) && file_exists(PUBLIC_PATH . $res['preview_path'])) {
                unlink(PUBLIC_PATH . $res['preview_path']);
            }
            $update = $this->table('tutorial')->data(['preview_path' => TUTORIAL_DEFAULT_PREVIEW])->where('id=' . $id)->save();
            return $update;
        }

        return $res;
    }

    public function getTotalCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('tutorial')->field('COUNT(*) as num')->select('Row');
        // 查询后返回的是数组['num'=>String('123')]
        // dump($res);die();
        $count = (int)$res['num'];
        // 先转化为整数
        // dump($count);die();

        return $count;
    }

}