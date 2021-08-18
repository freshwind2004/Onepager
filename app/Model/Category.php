<?php
namespace app\Model;

use Mini\Base\Model;

/**
 * Onepager的分类模型
 * 
 */
class Category extends Model
{
    public function addCategory($data)
    {
        $db = $this->useDb('default');

        $res = $this->table('category')->data($data)->add();

        return $res;
    }

    public function getCategory($url=false, $id=false)
    {
        $db = $this->useDb('default');
        // dump($url);die();
        if ($id != false) {
            $category = $this->table('category')->where('id=' . $id)->select('Row');
        } else {
            $category = $this->table('category')->where('`url`="' . $url . '"')->select('Row');
            // dump($category);die();
        }

        return $category;
    }

    public function getAllCategory($limit=25, $stats=false)
    {
        $db = $this->useDb('default');
        
        if ($limit == false) {
            $categories = $this->table('category')->select();
        } else {
            $categories = $this->table('category')->limit($limit)->select();
        }

        if ($stats) {
            foreach ($categories as $key => $item) {
                $res = $this->table('onepager')->where('category_id=' . $item['id'])->field('COUNT(*) as num')->select('Row');
                $categories[$key]['count'] = (int)$res['num'];
            }
        }

        return $categories;
    }

    public function delCategory($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('category')->where('id=' . $id)->delete();

        return $res;
    }

    public function getTotalCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('category')->field('COUNT(*) as num')->select('Row');

        $count = (int)$res['num'];

        return $count;
    }
}