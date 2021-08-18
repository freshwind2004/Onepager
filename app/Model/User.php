<?php

namespace app\Model;

use Mini\Base\{Model, Session};

/**
 * 用户模型
 * 查询用户的资料。
 */
class User extends Model // 继承 Model 模型类
{
    public function getUser($user_id = false, $username = false)
    {
        // 设置当前使用的数据库（这里的 default 是数据库连接对象的名称）
        $db = $this->useDb('default');

        if ($username != false) {
            $res = $this->table('user')->where('`username`="' . $username . '"')->select('Row');
        } else {
            $res = $this->table('user')->where('id=' . $user_id)->select('Row');
        }

        return $res;
    }

    public function getUserOrBounce($user_id)
    {
        // 设置当前使用的数据库（这里的 default 是数据库连接对象的名称）
        $db = $this->useDb('default');

        if (empty($user_id)) {
            Session::destroy();
            header('location:' . $_SERVER['HTTP_REFERER']);
            die();
        }

        $res = $this->table('user')->where('id=' . $user_id)->select('Row');

        // 当未查询到用户时
        if (!$res) {
            Session::destroy();
            header('location:' . $_SERVER['HTTP_REFERER']);
            die();
        }

        return $res;
    }

    public function getAllUsers($page = 1, $per = 10)
    {
        // 设置当前使用的数据库（这里的 default 是数据库连接对象的名称）
        $db = $this->useDb('default');

        $res = $this->table('user')->limit(($page - 1) * $per, $per)->order(array('id' => 'DESC'))->select();

        return $res;
    }

    public function updateUser($user_id, $data)
    {
        $db = $this->useDb('default');
        $res = $this->table('user')->where('id=' . $user_id)->select('Row');

        if (!$res) {
            return false;
        }

        $updated = $this->table('user')->data($data)->where('id=' . $user_id)->save();

        return $updated;
    }

    public function updateCredit($user_id, $credit, $identity)
    {
        $db = $this->useDb('default');

        $res = $this->table('user')->where('id=' . $user_id)->select('Row');

        if (!$res) {
            return false;
        }

        if ($identity == 'buyer') {
            $new_credit = $res['credit'] - $credit;
        } else {
            $new_credit = $res['credit'] + $credit;
        }

        $updated = $this->table('user')->data(['credit' => $new_credit])->where('id=' . $user_id)->save();

        return $updated;
    }

    public function updateUserPrivilege($user_id, $switch)
    {
        $db = $this->useDb('default');

        $res = $this->table('user')->data(['is_staff' => $switch])->where('id=' . $user_id)->save();

        return $res;
    }

    public function changepwd($user_id, $data)
    {
        $db = $this->useDb('default');

        $salt = getRandomString(8);

        $data['salt'] = $salt;
        $data['password'] = md5($data['password'] . $salt);
        $updated = $this->table('user')->data($data)->where('id=' . $user_id)->save();

        return $updated;
    }

    public function addUser($data)
    {
        $db = $this->useDb('default');

        $salt = getRandomString(8);

        $data['salt'] = $salt;
        $data['password'] = md5($data['password'] . $salt);
        $res = $this->table('user')->data($data)->add();

        if ($res) {
            $user = $this->table('user')->field('last_insert_id() as id')->select('Row');
            Session::start();
            Session::set('is_authenticated', true);
            Session::set('user_id', $user['id']);
            Session::set('username', $data['username']);
        }

        return $res;
    }

    public function addUserByAdmin($data)
    {
        $db = $this->useDb('default');

        $salt = getRandomString(8);

        $data['salt'] = $salt;
        $data['password'] = md5($data['password'] . $salt);
        $res = $this->table('user')->data($data)->add();

        return $res;
    }

    public function delUser($id)
    {
        $db = $this->useDb('default');

        $res = $this->table('user')->where('id=' . $id)->delete();

        return $res;
    }

    public function login($username, $password)
    {
        $db = $this->useDb('default');
        $res = $this->table('user')->where('`username`="' . $username . '"')->select('Row');

        if (!$res) {
            return false;
        }

        $encrypt_password = md5($password . $res['salt']);
        if ($encrypt_password == $res['password']) {
            $date = ['last_login' => date('Y-m-d H:i:s')];
            $last_login = $this->table('user')->data($date)->where('id=' . $res['id'])->save();
            if (!$last_login) {
                return false;
            }

            Session::start();
            Session::set('is_authenticated', true);
            Session::set('user_id', $res['id']);
            Session::set('username', $res['username']);
        } else {
            return false;
        }
    }

    public function cookieLogin($username, $match)
    {
        $db = $this->useDb('default');
        $res = $this->table('user')->where('`username`="' . $username . '"')->field('id, username, salt')->select('Row');

        if (!$res) {
            return false;
        }

        // cookie明文内容由用户名、用户salt和cookie salt三部分构成
        $encrypt_username = md5($username . $res['salt'] . COOKIES_SALT);
        if ($match == $encrypt_username) {
            Session::start();
            Session::set('is_authenticated', true);
            Session::set('user_id', $res['id']);
            Session::set('username', $res['username']);
        } else {
            return false;
        }
    }

    public function getTotalCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('user')->field('COUNT(*) as num')->select('Row');
        // 查询后返回的是数组['num'=>String('123')]
        $count = (int)$res['num'];
        // 先转化为整数

        return $count;
    }

    public function getStaffCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('user')->where('is_staff=1')->field('COUNT(*) as num')->select('Row');
        // 查询后返回的是数组['num'=>String('123')]
        $count = (int)$res['num'];
        // 先转化为整数

        return $count;
    }

    public function getActiveUserCount()
    {
        $db = $this->useDb('default');

        $res = $this->table('user')->where('DATE_SUB(CURDATE(), INTERVAL ' . ACTIVE_PERIOD . ' DAY) <= date(last_login)')->field('COUNT(*) as num')->select('Row');
        // 查询后返回的是数组['num'=>String('123')]
        $count = (int)$res['num'];
        // 先转化为整数

        return $count;
    }

    public function getCreditSum()
    {
        $db = $this->useDb('default');

        $res = $this->table('user')->field('sum(credit) as sum')->select('Row');

        $sum = (int)$res['sum'];

        return $sum;
    }
}
