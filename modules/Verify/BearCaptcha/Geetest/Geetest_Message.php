<div id="geetest_captcha_m"></div>
<script>
$(function(){
    $.getScript('<?php AssetsDir();?>assets/js/gt4.js',function(){
initGeetest4({
    product: 'float',
    captchaId: '<?php echo Bsoptions('geeid'); ?>'
},function (captcha) {
    $("#geetest_captcha_m").empty();
    captcha.appendTo("#geetest_captcha_m");
    captcha.onSuccess(function(){
      document.getElementById("commentsubmit").removeAttribute("disabled");
      var result = captcha.getValidate();
      var lot_number = $('<input type="text" name="lot_number" id="lot_number" style="display:none"/>');
          lot_number.attr('value', result.lot_number);
      var captcha_output = $('<input type="text" name="captcha_output" id="captcha_output" style="display:none"/>');
          captcha_output.attr('value', result.captcha_output);
      var pass_token = $('<input type="text" name="pass_token" id="pass_token" style="display:none"/>');
          pass_token.attr('value', result.pass_token);
      var gen_time = $('<input type="text" name="gen_time" id="gen_time" style="display:none"/>');
          gen_time.attr('value', result.gen_time);
$('#commentform').append(lot_number);
$('#commentform').append(captcha_output);
$('#commentform').append(pass_token);
$('#commentform').append(gen_time);
    })
});
});
});
</script>