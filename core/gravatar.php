<?php
function getrealurl($url){

$header = get_headers($url, 1);

print_r($header);

if (strpos($header[0],'301') || strpos($header[0],'302')) {

if(is_array($header['Location'])) {

return $header['Location'][count($header['Location'])-1];

}else{

return $header['Location'];

}

}else {

return $url;

}

}

function imgravatarq($email)

{

    $options = bsOptions::getInstance()::get_option( 'bearsimple' );
    switch($options['avatar__choose']){
        case 'cravatar':
            $email = md5($email);
            $default_avatar = ''; 
            if(!empty($options['Cravatar_default'])){
                $default_avatar = 'default='.urlencode($options['Cravatar_default']);
            }
            return "//cravatar.cn/avatar/" . $email . "?".$default_avatar;
            break;
        case 'weavatar':
            $email = md5($email);
            if(!empty($options['Weavatar_default'])){
                $default_avatar = 'd='.urlencode($options['Weavatar_default']);
            }
            return "//weavatar.com/avatar/" . $email . "?".$default_avatar;
            break;
            case 'diyavatar':
                $email = md5($email);
            if($options['DiyAvatar']){
               $avatar = explode(",",$options['DiyAvatar']);
    $Avatar = $avatar[rand(0,count($avatar)-1)];
    return $Avatar;
            }
            else{
              return "//weavatar.com/avatar/" . $email . "?";
            }
            break;
            case 'gravatar':
              $b=str_replace('@qq.com','',$email);
             if(stristr($email,'@qq.com')&&is_numeric($b)&&strlen($b)<11&&strlen($b)>4){
                 $email = md5($email);
    return "//weavatar.com/avatar/" . $email . "?";
             }
             else{
                 $email = md5($email);
                if($options['Gravatar'] == '1'){
                return "//cn.gravatar.com/gravatar/" . $email . "?";
              
            }
           
            else if($options['Gravatar'] == '3'){
                return "//cdn.v2ex.com/gravatar/" . $email . "?";
                
            }
            else if($options['Gravatar'] == '4'){
                return "//gravatar.loli.net/avatar/" . $email . "?";
               
            }
            else if($options['Gravatar'] == '7'){
                return "//".$options['GravatarUrl'] . $email . "?";
            }
             }
             break;
            default:
                $email = md5($email);
            return "//weavatar.com/avatar/" . $email . "?";
}
}