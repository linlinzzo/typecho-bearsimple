<?php
    /**
    * Memos
    *
    * @package custom
    */
?>
<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('compoment/head.php');?>
<link rel="stylesheet" href="https://staticfile.typecho.co.uk/fancybox/fancybox.min.css">
<?php if(Bsoptions('Emoji') == true) :?>
<link href="<?php AssetsDir();?>assets/vendors/bs-emoji/bs-emoji.css" rel="stylesheet" type="text/css">
<?php endif; ?>
<div class="pure-g" id="layout">
            <div class="pure-u-1 pure-u-md-<?php if(Bsoptions('site_style') == '1' || Bsoptions('site_style') == ''):?>3<?php endif;?><?php if(Bsoptions('site_style') == '2'):?>4<?php endif;?>-4">
                <div class="content_container">
               <?php if(Bsoptions('Diy') == true): ?><div class="ui <?php if(Bsoptions('postType') == "1"): ?>raised<?php endif; ?><?php if(Bsoptions('postType') == "2"): ?>stacked<?php endif; ?><?php if(Bsoptions('postType') == "3"): ?>tall stacked<?php endif; ?><?php if(Bsoptions('postType') == "4"): ?>piled<?php endif; ?> segment" <?php if(Bsoptions('postradius')): ?>style="border-radius:<?php echo Bsoptions('postradius'); ?>px"<?php endif; ?>><?php endif; ?>
                    <h2><i class="lightbulb outline icon"></i> <?php $this->title() ?></h2>
                  

<div class="memos-container">
    <!-- memos list -->
			<div id="memos_list"></div>
			
			<!-- memos loading -->
			<div class="ui basic segment" id="memo-loading" style="display:none">
    <div class="ui active blue elastic loader"></div>
    <br>
    <br>
    <br>
    <br>
</div>

<!-- memos next -->
			<div id="memos_next"></div>
			
		</div>

  
   

</div></div>


<script>
$(document).ready(function(){

$("#memo-loading").show();

var number = 5;
var stin = 'first';
var numberx = '';
                getData();
                function getData() {
                 
                    $.ajax({
                        type: "POST",
                        async:true,
                        url: "<?php getMemosAction(); ?>",
                        data: {
                            "action":'getmemo',
                            'number':number,
                            'stin':stin,
                            'numberx':numberx
                        },
                        dateType: "json",
                        success: function(json) {
                            json = JSON.parse(json);
                            total = json.total;
                            number = json.number;
                            stin = json.stin;
                            numberx = json.numberx;
                            
                            if(stin == 'first'){
                            content(json.list,json.hiddenagree);
                            }
                            else{
                            content(json.list,json.hiddenagree,'next');    
                            }
                      
                        },
                        complete: function() {
                            pageList();
                            getLike();
                          <?php if(Bsoptions('Lazyload') == true) :?>
                           lazyLoad();
                           <?php endif; ?> 
                           $("#memo-loading").hide();

                        },
                        error: function() {
                          
                        }
                    });
                };
                function pageList() {
                     if(number < total){
                            number = parseInt(number) + 5;
                    var html = "<center><div class=\"ui icon tiny button\" id=\"#memo-click\" data-number="+number+"><i class=\"hand point right outline icon\"></i> 加载更多</div></center>";
                    var $html = $(html);
                    $html.find("div").click(function() {
                        $("#memo-loading").show();
                        $html.find("div").css("pointer-events","none");
                        number = $(this).attr("data-number");
                        stin = 'next';
                        getData();
                        
                    });
                    $("#memos_next").html($html);
                          }
                          else{
                     $("#memos_next").hide();         
                          }
                }
                function content(list,hidden,type = 'first') {
                    var str = " ";
                     for(var i in list) {
    if(hidden == true){
    str += '<li class="memo-li-' + list[i]['memos_id'] + '" data-memoid="' + list[i]['memos_id'] + '"><div class="memo-main-level" id="memos-' + list[i]['memos_id'] + '"><div class="memo-avatar"><img <?php echo lazyload('auto');?>src="<?php echo imgravatarq($this->author->mail); ?>"></div><div class="memo-box"><div class="memo-head"><h6 class="memo-name by-author">' + list[i]['memos_name'] + '</h6> <span>' + list[i]['memos_time'] + '</span> </div><div class="memo-content">' + list[i]['memos_content'] +'<div class="memo-attachment">'+list[i]['resource']+'</div>   <emoji-reaction theme="light" endpoint="<?php getEmactionAction();?>" reacttargetid="memos-' + list[i]['memos_id'] + '" style="flex-wrap: nowrap; max-width: 100%; display: flex; gap: 0.375rem;height: 1.5rem;"></emoji-reaction>  </div></div></div>  </li>';    
    }
    else{
str += '<li class="memo-li-' + list[i]['memos_id'] + '" data-memoid="' + list[i]['memos_id'] + '"><div class="memo-main-level" id="memos-' + list[i]['memos_id'] + '"><div class="memo-avatar"><img <?php echo lazyload('auto');?>src="<?php echo imgravatarq($this->author->mail); ?>"></div><div class="memo-box"><div class="memo-head"><h6 class="memo-name by-author">' + list[i]['memos_name'] + '</h6> <span>' + list[i]['memos_time'] + '</span> </div><div class="memo-content">' + list[i]['memos_content'] +'<div class="memo-attachment">'+list[i]['resource']+'</div><?php if(Bsoptions('emaction_Openplace') && in_array('3',Bsoptions('emaction_Openplace'))):?><emoji-reaction theme="light" endpoint="<?php getEmactionAction();?>" reacttargetid="memos-' + list[i]['memos_id'] + '" style="flex-wrap: nowrap; max-width: 100%; display: flex; gap: 0.375rem;height: 1.5rem;"></emoji-reaction><?php endif; ?></div></div></div>  <div class="ui tertiary button like" style="margin-top:10px;float:right;" data-memoid="' + list[i]['memos_id'] + '">点赞（<span id="memo-' + list[i]['memos_id'] + '">' + list[i]['memos_agree'] + '</span>）</div></li>';
}
str = str.replace(RegExp('" data-memo-image>,', "g"),'">');
str = str.replace(RegExp('</a>,', "g"),'</a>');
}
if(type == 'first'){
$("#memos_list").html('<ul id="memos-list" class="memos-list memos-list-front">'+str+'</ul>');
}
else{
$("#memos-list").append(str);   
}
                };
                function getLike(){
                $('.ui.tertiary.button.like').off('click').on('click',function(){
$(this).css("pointer-events","none").addClass('loading');
                    $.ajax({
                        type: "POST",
                        async:true,
                        url: "<?php getMemosAction(); ?>",
                        data: {
                            "action":'getmemolike',
                            'memoid':$(this).attr('data-memoid'),
                        },
                        success: function(json) {
                            jsonx = $.trim(json);
                            result = JSON.parse(jsonx);
                           switch(result.code){  
                           case 1:
                    $('body').toast({
							    title:'点赞成功',
							    class: 'green',
							    message: '您已经点赞成功啦～', 
							    showIcon: 'grin beam outline',
							    showProgress: 'top',
										});
					$('#memo-'+result.memo_id).html(result.memo_agree);				 $('.ui.tertiary.button.like').css("pointer-events","auto").removeClass('loading');
										break;
						case 0:
						$('body').toast({
							    title:'点赞失败',
							    class: 'warning',
							    message: result.message, 
							    showIcon: 'flushed outline',
							    showProgress: 'top',
										});
						$('.ui.tertiary.button.like').css("pointer-events","auto").removeClass('loading');
						    break;
                       }
                       
                        },
                        error: function() {
                        $('body').toast({
							    title:'点赞失败',
							    class: 'warning',
							    message: '呜呜呜，可能是网络出现了问题，请稍后再试~', 
							    showIcon: 'flushed outline',
							    showProgress: 'top',
										});  
						$('.ui.tertiary.button.like').css("pointer-events","auto").removeClass('loading');
                        }
                    });
                })
                }


});
</script>

<?php if(Bsoptions('Diy') == true): ?></div><?php endif; ?>

<?php $this->need('compoment/sidebar.php'); ?>
<?php $this->need('compoment/foot.php'); ?>