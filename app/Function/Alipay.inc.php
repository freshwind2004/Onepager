<?php
// 生成一个码支付的URL请求并转到该地址
function alipay_url($order_id, $user_id, $amount)
{
    $codepay_id = "619405"; //这里改成码支付ID
    $codepay_key = "jIq4WzbzAqAEJGMnI2oc1WVZ6VQ5nURF"; //这是您的通讯密钥

    $data = array(
        "id" => $codepay_id, //你的码支付ID
        "pay_id" => $order_id, //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
        "type" => 1, //1支付宝支付 3微信支付 2QQ钱包
        "price" => $amount, //金额
        "param" => $user_id, //自定义参数
        "notify_url" => "https://onepager.work/credit/notify/", //通知地址
        "return_url" => "https://onepager.work/credit/return/", //跳转地址
    ); //构造需要传递的参数

    ksort($data); //重新排序$data数组
    reset($data); //内部指针指向数组中的第一个元素

    $sign = ''; //初始化需要签名的字符为空
    $urls = ''; //初始化URL参数为空

    foreach ($data as $key => $val) { //遍历需要传递的参数
        if ($val == '' || $key == 'sign') continue; //跳过这些不参数签名
        if ($sign != '') { //后面追加&拼接URL
            $sign .= "&";
            $urls .= "&";
        }
        $sign .= "$key=$val"; //拼接为url参数形式
        $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值

    }
    $query = $urls . '&sign=' . md5($sign . $codepay_key); //创建订单所需的参数
    $url = "https://api.xiuxiu888.com/creat_order/?{$query}"; //支付页面

    header("Location:{$url}"); //跳转到支付页面
}

// 判断码支付返回的POST/GET信息是否合法
function codepay_return_verify($post)
{
    ksort($post); //排序post参数
    reset($post); //内部指针指向数组中的第一个元素
    $codepay_key = "jIq4WzbzAqAEJGMnI2oc1WVZ6VQ5nURF"; //这是您的密钥
    $sign = ''; //初始化
    foreach ($post as $key => $val) { //遍历POST参数
        if ($val == '' || $key == 'sign') continue; //跳过这些不签名
        if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
        $sign .= "$key=$val"; //拼接为url参数形式
    }
    if (!$post['pay_no'] || md5($sign . $codepay_key) != $post['sign']) { //不合法的数据
        return 'fail';  //返回失败 继续补单
    } else { //合法的数据
        // 业务处理 在主函数中执行，此处注释
        // $pay_id = $post['pay_id']; //需要充值的ID 或订单号 或用户名
        // $money = (float)$post['money']; //实际付款金额
        // $price = (float)$post['price']; //订单的原价
        // $param = $post['param']; //自定义参数
        // $pay_no = $post['pay_no']; //流水号
        return 'success'; //返回成功 不要删除哦
    }
}
