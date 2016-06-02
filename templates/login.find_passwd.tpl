<{include file="header.tpl"}>
<div id="find_passwd_form">
    <form method="post" action="/?ct=login&ac=get_mobile_key">
        <div id="account_area">
            <label for="account">手机号: </label>
            <input id="account" name="account" type="text"></input>
        </div>
        <div id="verify_area">
            <label for="verify_code">验证码: </label>
            <input id="verify_code" name="verify_code" type="text"></input>
            <span class="verify_image"><img id="code_img" src="/?ct=register&ac=verifycode&time=<{$time}>" title="看不清，请点击重试" onClick="change_code()" height="25" width="76" /></span><a href="#" onClick="change_code()">换一个</a>
        </div>
            <input id="phone_code_btn" type="button" value="免费获取手机验证码"></input>
    </form>
    <form method="post" action="/?ct=login&ac=auth2">
        <div id="auth_area">
            <label for="auth_code">手机验证码: </label>
            <input id="auth_code" name="auth_code" type="auth_code"></input>
            <input id="phone_code_btn" type="button" value="免费获取手机验证码"></input>
        </div>
        <div id="commit_area">
            <input id="login_btn" type="submit" value="提交"></input>
        </div>
    </form>
</div>
<script type="text/javascript">
    function change_code() {
        var code_img = document.getElementById('code_img');
        code_img.src = '?ct=register&ac=verifycode&seed=' + Math.random();
    }
    change_code();
    var wait=60;
    function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            o.value="免费获取手机验证码";
            wait = 60;
        }
        else {
            o.setAttribute("disabled", true);
            o.value="重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                time(o)
            }, 1000);
        }
    }
    document.getElementById("phone_code_btn").onclick = function(){ time(this); }
</script>
<{include file="footer.tpl"}>
