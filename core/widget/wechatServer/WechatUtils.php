<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
require_once 'ImgCompress.php';

class WechatUtils {

    public static function uploadPic($blogUrl, $name, $pic,$type,$suffix){
        $DIRECTORY_SEPARATOR = "/";
        $childDir = $DIRECTORY_SEPARATOR.'usr'.$DIRECTORY_SEPARATOR.'uploads' . $DIRECTORY_SEPARATOR .'wechatServer' .$DIRECTORY_SEPARATOR;
        $dir = __TYPECHO_ROOT_DIR__ . $childDir;
        if (!file_exists($dir)){
            mkdir($dir, 0777, true);
        }
        $fileName = $name. $suffix;
        $file = $dir .$fileName;
        if ($type == "web"){
            $img = self::getDataFromWebUrl($pic);
        }else{
            $img = $pic;
        }
        $fp2 = fopen($file , "a");
        fwrite($fp2, $img);
        fclose($fp2);

        (new Imgcompress($file,1))->compressImg($file);

        return $blogUrl.$childDir.$fileName;
    }


    public static  function getDataFromWebUrl($url){
        $file_contents = "";
        if (function_exists('file_get_contents')) {
            $file_contents = @file_get_contents($url);
        }
        if ($file_contents == "") {
            $ch = curl_init();
            $timeout = 30;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }
        return $file_contents;
    }
}