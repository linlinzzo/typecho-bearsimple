<?php
function AssetsDir(){
    $options = Helper::options();
    if($options->Assets == '1' || $options->Assets == null){
        $dir = $options->themeUrl.'/';
    }
    else{
        $dir = $options->Assets_Custom;
    }
    echo $dir;
}

function AssetsDir2(){
   $options = Helper::options();
        $dir = $options->themeUrl.'/';
   
    echo $dir;
}