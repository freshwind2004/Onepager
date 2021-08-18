<?php
namespace app\Model;

use Mini\Base\Model;


/**
 * Resource模型
 * 资源分享数据模型
 */
class Resource extends Model
{

    public function getResource($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('resource')->where('id=' . $id)->select('Row');

        return $res;
    }

    public function getAllResources($page = 1, $per = 10)
    {
        $db = $db = $this->useDb('default');

        $res = $this->table('resource')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();
        
        return $res;
    }

    public function addResource($data)
    {
        $db = $this->useDb('default');

        $res = $this->table('resource')->data($data)->add();

        if ($res) {
            $res = $this->table('resource')->field('last_insert_id() as id')->select('Row');
            return $res['id'];
        }

        return $res;
    }

    public function editResource($id, $data)
    {
        $db = $this->useDb('default');

        $res = $this->table('resource')->data($data)->where('id=' . $id)->save();

        return $res;
    }

    public function delResource($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('resource')->where('id=' . $id)->select('Row');

        if ($res) {
            // 删除预览图
            if (($res['preview_path'] != RESOURCE_DEFAULT_PREVIEW) && file_exists(PUBLIC_PATH . $res['preview_path'])) {
                unlink(PUBLIC_PATH . $res['preview_path']);
            }

            $del = $this->table('resource')->where('id=' . $id)->delete();
            return $del;
        }

        return $res;
    }

    public function removePreview($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('resource')->where('id=' . $id)->select('Row');

        if ($res) {
            // 预览图
            if (($res['preview_path'] != RESOURCE_DEFAULT_PREVIEW) && file_exists(PUBLIC_PATH . $res['preview_path'])) {
                unlink(PUBLIC_PATH . $res['preview_path']);
            }
            $update = $this->table('resource')->data(['preview_path' => RESOURCE_DEFAULT_PREVIEW])->where('id=' . $id)->save();
            return $update;
        }

        return $res;
    }

    public function getTotalCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('resource')->field('COUNT(*) as num')->select('Row');
        // 查询后返回的是数组['num'=>String('123')]
        // dump($res);die();
        $count = (int)$res['num'];
        // 先转化为整数
        // dump($count);die();

        return $count;
    }

}