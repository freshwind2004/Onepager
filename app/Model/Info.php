<?php
namespace app\Model;

use Mini\Base\Model;
// use Mini\Base\Config;
// use Mini\Db\Db; // 工厂模式
// use Mini\Db\Mysql; // 直接调用

/**
 * 这是一个模型的案例
 * MiniFramework 从 1.0.0 开始全面启用了命名空间，创建模型时，需在文件顶部放置 namespace app\Model; 进行声明。
 */
class Info extends Model
{
    public function getInfo()
    {
        //如果你开启了数据库自动连接功能，就可以用下面这行代码自动加载数据库对象了
        $db = $this->useDb('default');
        
        //这是手工连接数据库的方法，需要解开顶部 use Mini\Db\Db; 和 use Mini\Base\Config; 两行代码的注释
        //$dbParams = Config::getInstance()->load('database:default');
        //$db = Db::factory('Mysql', $dbParams);

        //MiniFramework 从 2.0 开始支持直接调用 Mini\Db\Mysql
        //  需要解开顶部的 user Mini\Db\Mysql; 和 use Mini\Base\Config; 两行代码的注释
        //  这样直接调用的好处是可以让IDE更好地对类的方法进行提示，方便开发者进行编码。
        //$dbParams = Config::getInstance()->load('database:default');
        //$db = new Mysql($dbParams);

        // 示例1：向 user 表中一次插入一条纪录
        // $data1 = array('id' => 1, 'username' => 'zhangsan', 'nickname' => '张三');
        // dump($this->table('user')->data($data1)->add());

        // // 示例2：向 user 表中一次插入多条纪录
        // $data2 = array(
        //     array('id' => 2, 'name' => '李四'),
        //     array('id' => 3, 'name' => '王五')
        // );
        // dump($this->table('user')->data($data2)->add());

        // // 示例3：删除 user 表中 id 为 2 的纪录
        // dump($this->table('user')->where('id=2')->delete());

        // // 示例4：修改 user 表中 id 为 3 的记录
        // dump($this->table('user')->data(array('name' => '赵六'))->where('id=3')->save());

        // // 示例5：查询 user 表中的全部纪录
        // dump($this->table('user')->select());

        // // 示例6：查询 user 表中的全部纪录，但只返回前 2 条纪录
        // dump($this->table('user')->limit(2)->select());
        // // 上方示例6中，limit(2) 等价于 limit(0, 2)，用法与 SQL 中的 LIMIT 语法一致

        // // 示例7：查询 user 表中的全部纪录，按 id 字段倒序排列结果
        // dump($this->table('user')->order(array('id' => 'DESC'))->select());
        // // 上方示例7中的 order() 方法也可直接传入字符串，例如 order('id DESC')

        // // 示例8：查询 user 表中 id 为 1 的记录
        // $res = $this->table('user')->where('id=1')->select('Row');
        // // 上方示例8中，select() 方法传入 Row 参数时返回的结果为键值对形式的一维数组

        // // 示例9：查询 user 表中行数
        // $res = $this->table('user')->->field('COUNT(*) as num')->select('Row');
        // // 上方示例9中，select() 方法查询 Row 的行数并输出为num

        // // 输出查询结果
        // dump($res);
        
        return "Hello World!";
    }
}