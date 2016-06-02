<{include file="header.tpl"}>
<div id="login_form">
    <form method="post" action="/?ct=login&ac=auth" onsubmit="return submit_check()" >
        <div id="account_area">
            <label for="account">手机号: </label>
            <input id="account" name="account" type="text"></input>
        </div>
        <div id="passwd_area">
            <label for="passwd">密码: </label>
            <input id="passwd" name="passwd" type="password"></input>
        </div>
        <div id="verify_area">
            <label for="verify_code">验证码: </label>
            <input id="verify_code" name="verify_code" type="text"></input>
            <span class="verify_image"><img id="code_img" src="/?ct=register&ac=verifycode&time=<{$time}>" title="看不清，请点击重试" onClick="change_code()" height="25" width="76" /></span><a href="#" onClick="change_code()">换一个</a>
        </div>
        <div id="commit_area">
            <input id="login_btn" type="submit" value="登录"></input>
        </div>
    </form>
    <a href="/?ct=login&ac=find_passwd">找回密码?</a>
    <a href="/?ct=register">注册新用户</a>
</div>
<script type="text/javascript">
    function change_code() {
        var code_img = document.getElementById('code_img');
        code_img.src = '?ct=register&ac=verifycode&seed=' + Math.random();
    }
    change_code();
    function submit_check() {
        if ($("#account").val() == "") {
            // TODO:
            $("#account").focus();
            return false;
        }
        if ($("#passwd").val() == "") {
            // TODO:
            $("#passwd").focus();
            return false;
        }
        if ($("#verify_code").val() == "") {
            // TODO:
            $("#verify_code").focus();
            return false;
        }
        return true;
    };
</script>
<{include file="footer.tpl"}>
