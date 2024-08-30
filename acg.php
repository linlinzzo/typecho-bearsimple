<?php
    /**
    * 我的追番
    *
    * @package custom
    */
?>
<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('compoment/head.php');?>
<style>
    #imga{
        height:auto;
    }
     @media (max-width: 767px) {
       #imga{
        height:auto;
    } 
    }
</style>
<div class="pure-g" id="layout">
            <div class="pure-u-1 pure-u-md-<?php if(Bsoptions('site_style') == '1' || Bsoptions('site_style') == ''):?>3<?php endif;?><?php if(Bsoptions('site_style') == '2'):?>4<?php endif;?>-4">
                <div class="content_container">
               <?php if(Bsoptions('Diy') == true): ?><div class="ui <?php if(Bsoptions('postType') == "1"): ?>raised<?php endif; ?><?php if(Bsoptions('postType') == "2"): ?>stacked<?php endif; ?><?php if(Bsoptions('postType') == "3"): ?>tall stacked<?php endif; ?><?php if(Bsoptions('postType') == "4"): ?>piled<?php endif; ?> segment" <?php if(Bsoptions('postradius')): ?>style="border-radius:<?php echo Bsoptions('postradius'); ?>px"<?php endif; ?>><?php endif; ?>
                    <h2><i class="video icon"></i> <?php $this->title() ?></h2>

<?php if(Bsoptions('acg_choose') == '2'): ?> 


  <?php if(!empty(Bsoptions('bilibili_note'))): ?>     
    <div class="ui segment">
  <i class="red heart icon"></i>
  <?php echo Bsoptions('bilibili_note');?>
</div>
<?php endif; ?>
  <?php
  if(BsOptions('bangumi_accountid')){
$page = isset($_GET['page'])?$_GET['page']:"1";
$rePage = autoPage(getBangumi(),"6",$page);
echo '<div class="finish_content ui three column doubling stackable masonry grid">';
foreach($rePage['ret'] as $key=>$val){
    $progress = 0;
    if($val['ep_status'] > $val['subject']['eps']){
       $val['ep_status'] = $val['subject']['eps']; 
    }
if ($val['subject']['eps'] != 0) {
    $progress = ($val['ep_status'] / $val['subject']['eps']) * 100;
}
$roundedPercentage = round($progress, 2);
$formattedPercentage = number_format($roundedPercentage, 2, '.', '');
if($val['ep_status'] == $val['subject']['eps']){
    $color = 'success';
}
else{
    $color = 'blue';
}
  echo '
      <div class="column"><div class="ui fluid card" style="word-break:break-all;"><div class="blurring dimmable image"><div class="ui dimmer"><div class="content"><div class="center"><a class="ui inverted button" href="https://bgm.tv/subject/'.$val['subject_id'].'" target="_blank">我也要看</a></div></div></div><img id="imga" '.lazyload('auto').'src="'.$val['subject']['images']['small'].'" referrerPolicy="no-referrer"></div><div class="content" style="text-align:center;"><a class="header" href="https://bgm.tv/subject/'.$val['subject_id'].'" target="_blank">《'.$val['subject']['name'].'》</a><div class="meta"> <div class="bstools" bstitle="观看进度('.$formattedPercentage.'%)" style="margin-top:10px"></div><div class="bsprogress bsprogress-striped active">
      <div role="bsprogressbar" style="width: '.$formattedPercentage.'%;" class="bsprogress-bar bsprogress-bar-'.$color.'"><span>'.$val['ep_status'].'/'.$val['subject']['eps'].'</span></div>
    </div>   </div>      </div>    </div></div>';
}
echo '</div>';
echo $rePage['page'];
}
?>
</div></div>
<script>
    $(function(){
        $('.ui.dimmer').click();
    })
</script>
<?php else:?>



<div class="ui divided selection icon list">
  <a class="item">
    <div class="ui red horizontal label">Tips</div>
    右上角显示打勾标志=已完结
  </a>
</div>
<?php if(!empty(Bsoptions('bilibili_note'))): ?>     
    <div class="ui segment">
  <i class="red heart icon"></i>
  <?php echo Bsoptions('bilibili_note');?>
</div>
<?php endif; ?>
 <div id="loading"  class="ui icon message" style="display:none">
  <i class="video circle loading icon"></i>
  <div class="content">
    <div class="header">
      正在获取中.....
    </div>
    <p>请稍等一下哦～马上就出来了</p>
  </div>
</div>
<div class="ui active inverted dimmer" id="commonLoad" style="display:none">
    <div class="ui active black elastic loader"></div>
  </div>
                <div class="finish_content ui three column doubling stackable masonry grid">
                </div>
<br>
            <!--分页链接-->
            <div class="pagelist">
            </div>
</div>
</div>
 <script>
$("#loading").show();
            $(function() {
                var page = 1;
                var n = 0;
                var max = 1;
                getData();
                function getData() {
                    $.ajax({
                        type: "POST",
                        async:true,
                        url: "<?php getAcgFile(); ?>",
                        data: {
                            "page": page,
                            "type": 'acg'
                        },
                        dateType: "json",
                        success: function(json) {
                            json = JSON.parse(json);
                            n = json.total;
                            max = json.max;
                            if(json.total == null){
                             toastr.warning('啊哦，暂未获取到番剧~~请稍后再试');   
                             $("#loading").hide();
                            }
                            else{
                            content(json.list);
                            }
                        },
                        complete: function() {
                            pageList();
                            <?php if(Bsoptions('Lazyload') == true) :?>
                           lazyLoad();
                           <?php endif; ?>
                            $('.ui.dimmer').click();
                            $("#commonLoad").fadeOut();
                        },
                        error: function() {
                            alert("番剧获取错误，请稍后再试~~");
                        }
                    });
                };
                function pageList() {
                    page = Math.min(page, max);
                    page = Math.max(page, 1);
                    var html = "<center><div class=\"ui circular labels\"><a class=\"ui label\" data-page="+ page +">共" + n + "条</a><a class=\"ui label\" data-page="+ page +">第" + page + "/" + max + "页</a>";
                    html += '<a class=\"ui label\" href="#" data-page="1">首页</a>';
                    html += (page > 1) ? '<a class=\"ui label\" href="#" data-page="' + (page - 1) + '">上一页</a>' : '<a class=\"ui label\" href="#" data-page="1">上一页</a>';
                    html += (page < max) ? '<a class=\"ui label\" href="#" data-page="' + (page + 1) + '">下一页</a>' : '<a class=\"ui label\" href="#" data-page="' + max + '">下一页</a>';
                    html += '<a class=\"ui label\" href="#" data-page="' + max + '">尾页</a><div class=\"ui mini input\"><input id=\"dipage\" type=\"number\" placeholder=\"输入跳转的页码\"></div><a id=\"gopage\" class=\"ui label\" href="#">跳转到指定页</a></div></center>';
                    var $html = $(html);
                    
                    $html.find("a").click(function() {
                        if($(this).attr("id")!=='gopage'){
                        page = $(this).attr("data-page");
                        }
                        else{
                            var dipage = document.getElementById("dipage").value;
                            switch(dipage){
                            case dipage < 1 :
                                page = 1;
                            break;
                            case dipage > max:
                                page = max;
                            break;
                            default:
                            page = dipage;
                        };
                        };
                        $("#commonLoad").fadeIn();
                        getData();
                    });
                    
                    
                    $(".pagelist").html($html);
                }
                function content(list) {
                    var str = " ";
                    for(var i in list) {
if(list[i]['is_finish'] == "1"){
                        str += '<div class="column"><div  class="ui fluid card" style="word-break:break-all;"><div class="blurring dimmable image"><a class="ui red right corner label"><i class="calendar check outline icon"></i></a><div class="ui dimmer"><div class="content"><div class="center"><a class="ui inverted button" href="' + list[i]['url'] + '"<?php if(Bsoptions('Link_blank') == true):?> target="_blank"<?php endif; ?>>我也要看</a></div></div></div><img id="imga" <?php echo lazyload('auto');?>src="' + list[i]['cover'] + '" referrerPolicy="no-referrer"></div><div class="content" style="text-align:center;"><a class="header" href="' + list[i]['url'] + '"<?php if(Bsoptions('Link_blank') == true):?> target="_blank"<?php endif; ?>>《' + list[i]['title'] + '》</a><div class="meta"><a>' + list[i]['subtitle_14'] + '</a></div></div></div></div>'
}
else{
str += '<div class="column"><div class="ui fluid card" style="word-break:break-all;"><div class="blurring dimmable image"><div class="ui dimmer"><div class="content"><div class="center"><a class="ui inverted button" href="' + list[i]['url'] + '"<?php if(Bsoptions('Link_blank') == true):?> target="_blank"<?php endif; ?>>我也要看</a></div></div></div><img id="imga" <?php echo lazyload('auto');?>src="' + list[i]['cover'].replace('http://','//') + '" referrerPolicy="no-referrer"></div><div class="content" style="text-align:center;"><a class="header" href="' + list[i]['url'] + '"<?php if(Bsoptions('Link_blank') == true):?> target="_blank"<?php endif; ?>>《' + list[i]['title'] + '》</a><div class="meta"><a>' + list[i]['subtitle_14'] + '</a></div></div></div></div>'
}
};
$(".finish_content").html(str);
$("#loading").hide();


                }
            })
        </script>
  <?php endif;?>
<?php if(Bsoptions('Diy') == true): ?></div><?php endif; ?>
<?php $this->need('compoment/sidebar.php'); ?>
<?php $this->need('compoment/foot.php'); ?>