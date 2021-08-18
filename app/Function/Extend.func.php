<?php
// 扩展函数

/*********************************************************************
  函数名称:encrypt
  函数作用:加密解密字符串
  使用方法:
  加密   :encrypt('str','E','nowamagic');
  解密   :encrypt('被加密过的字符串','D','nowamagic');
  参数说明:
  $string  :需要加密解密的字符串
  $operation:判断是加密还是解密:E:加密  D:解密
  $key   :加密的钥匙(密匙);
 *********************************************************************/

function encrypt($string, $operation, $key = '')
{
    $key = md5($key);
    $key_length = strlen($key);
    $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
    $string_length = strlen($string);
    $rndkey = $box = array();
    $result = '';
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($key[$i % $key_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'D') {
        if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
            return substr($result, 8);
        } else {
            return '';
        }
    } else {
        return str_replace('=', '', base64_encode($result));
    }
}

/*********************************************************************
  函数名称:imageHandler
  函数作用:缩放并按比例剪裁图片
  使用方法:
  imageHandler($源文件位置, $目标文件位置, $tn_w = 目标宽, $tn_h = 目标高, $quality = 80, $wmsource = 水印文件位置)
 *********************************************************************/
function imageHandler($source_image, $destination, $tn_w = 100, $tn_h = 100, $quality = 80, $wmsource = false)
{
    // The getimagesize functions provides an "imagetype" string contstant, which can be passed to the image_type_to_mime_type function for the corresponding mime type
    $info = getimagesize($source_image);
    $imgtype = image_type_to_mime_type($info[2]);
    // Then the mime type can be used to call the correct function to generate an image resource from the provided image
    switch ($imgtype) {
        case 'image/jpeg':
            $source = imagecreatefromjpeg($source_image);
            break;
        case 'image/gif':
            $source = imagecreatefromgif($source_image);
            break;
        case 'image/png':
            $source = imagecreatefrompng($source_image);
            break;
        default:
            die('Invalid image type.');
    }
    // Now, we can determine the dimensions of the provided image, and calculate the width/height ratio
    $src_w = imagesx($source);
    $src_h = imagesy($source);
    $src_ratio = $src_w / $src_h;
    // Now we can use the power of math to determine whether the image needs to be cropped to fit the new dimensions, and if so then whether it should be cropped vertically or horizontally. We're just going to crop from the center to keep this simple.
    if ($tn_w / $tn_h > $src_ratio) {
        $new_h = $tn_w / $src_ratio;
        $new_w = $tn_w;
    } else {
        $new_w = $tn_h * $src_ratio;
        $new_h = $tn_h;
    }
    $x_mid = $new_w / 2;
    $y_mid = $new_h / 2;
    // Now actually apply the crop and resize!
    $newpic = imagecreatetruecolor(round($new_w), round($new_h));
    imagecopyresampled($newpic, $source, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
    $final = imagecreatetruecolor($tn_w, $tn_h);
    imagecopyresampled($final, $newpic, 0, 0, ($x_mid - ($tn_w / 2)), ($y_mid - ($tn_h / 2)), $tn_w, $tn_h, $tn_w, $tn_h);
    // If a watermark source file is specified, get the information about the watermark as well. This is the same thing we did above for the source image.
    if ($wmsource) {
        $info = getimagesize($wmsource);
        $imgtype = image_type_to_mime_type($info[2]);
        switch ($imgtype) {
            case 'image/jpeg':
                $watermark = imagecreatefromjpeg($wmsource);
                break;
            case 'image/gif':
                $watermark = imagecreatefromgif($wmsource);
                break;
            case 'image/png':
                $watermark = imagecreatefrompng($wmsource);
                break;
            default:
                die('Invalid watermark type.');
        }
        // Determine the size of the watermark, because we're going to specify the placement from the top left corner of the watermark image, so the width and height of the watermark matter.
        $wm_w = imagesx($watermark);
        $wm_h = imagesy($watermark);
        // Now, figure out the values to place the watermark in the bottom right hand corner. You could set one or both of the variables to "0" to watermark the opposite corners, or do your own math to put it somewhere else.
        $wm_x = $tn_w - $wm_w;
        $wm_y = $tn_h - $wm_h;
        // Copy the watermark onto the original image
        // The last 4 arguments just mean to copy the entire watermark
        imagecopy($final, $watermark, $wm_x, $wm_y, 0, 0, $tn_w, $tn_h);
    }
    // Ok, save the output as a jpeg, to the specified destination path at the desired quality.
    // You could use imagepng or imagegif here if you wanted to output those file types instead.
    if (Imagejpeg($final, $destination, $quality)) {
        return true;
    }
    // If something went wrong
    return false;
}

/*********************************************************************
  函数名称:getExcerpt
  函数作用:获取字串中文摘要并移除html标签
  使用方法:
  getExcerpt($内容, $count = 字数)
 *********************************************************************/
function getExcerpt($content, $count = EXCERPT_LENGTH)
{
    $content = preg_replace("@<script(.*?)</script>@is", "", $content);
    $content = preg_replace("@<iframe(.*?)</iframe>@is", "", $content);
    $content = preg_replace("@<style(.*?)</style>@is", "", $content);
    $content = preg_replace("@<(.*?)>@is", "", $content);
    $content = str_replace(PHP_EOL, '', $content);
    $space = array("　", "  ");
    $go_away = array("", "");
    $content = str_replace($space, $go_away, $content);
    $res = mb_substr($content, 0, $count, 'UTF-8');
    if (mb_strlen($content, 'UTF-8') > $count) {
        $res = $res . "...";
    }
    return $res;
}

/*********************************************************************
  函数名称:base32_encode
  函数作用:使用base32编码内容
  使用方法:
  base32_encode($内容)
 *********************************************************************/
function base32_encode($input) {
    $BASE32_ALPHABET = 'abcdefghijklmnopqrstuvwxyz234567';
    $output = '';
    $v = 0;
    $vbits = 0;
 
    for ($i = 0, $j = strlen($input); $i < $j; $i++) {
        $v <<= 8;
        $v += ord($input[$i]);
        $vbits += 8;
 
        while ($vbits >= 5) {
            $vbits -= 5;
            $output .= $BASE32_ALPHABET[$v >> $vbits];
            $v &= ((1 << $vbits) - 1);
        }
    }
 
    if ($vbits > 0) {
        $v <<= (5 - $vbits);
        $output .= $BASE32_ALPHABET[$v];
    }
 
    return $output;
}

/*********************************************************************
  函数名称:base32_decode
  函数作用:使用base32解码内容
  使用方法:
  base32_decode($内容)
 *********************************************************************/
function base32_decode($input) {
    $output = '';
    $v = 0;
    $vbits = 0;
 
    for ($i = 0, $j = strlen($input); $i < $j; $i++) {
        $v <<= 5;
        if ($input[$i] >= 'a' && $input[$i] <= 'z') {
            $v += (ord($input[$i]) - 97);
        } elseif ($input[$i] >= '2' && $input[$i] <= '7') {
            $v += (24 + $input[$i]);
        } else {
            exit(1);
        }
 
        $vbits += 5;
        while ($vbits >= 8) {
            $vbits -= 8;
            $output .= chr($v >> $vbits);
            $v &= ((1 << $vbits) - 1);
        }
    }
    return $output;
}
