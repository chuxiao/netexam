<{include file="header.tpl"}>
<script type="text/javascript" src="static/js/md5.js"></script>
<div id="login_form">
    <form method="post" action="/admin/?ct=login&ac=auth" onsubmit="return submit_check()" >
        <div id="account_area">
            <label for="account">账号: </label>
            <input id="account" name="account" type="text"></input>
        </div>
        <div id="passwd_area">
            <label for="passwd">密码: </label>
            <input id="passwd" name="passwd" type="password"></input>
        </div>
        <div id="verify_area">
            <label for="verify_code">验证码: </label>
            <input id="verify_code" name="verify_code" type="text"></input>
            <span class="verify_image"><img id="code_img" src="/admin/?ct=login&ac=verifycode&time=<{$time}>" title="看不清，请点击重试" onClick="change_code()" height="25" width="76" /></span><a href="#" onClick="change_code()">换一个</a>
        </div>
        <div id="commit_area">
            <input id="login_btn" type="submit" value="登录"></input>
        </div>
    </form>
</div>
<script type="text/javascript">
    function change_code() {
        var code_img = document.getElementById('code_img');
        code_img.src = '/admin/?ct=login&ac=verifycode&seed=' + Math.random();
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
        $("#passwd").val(hex_md5($("#passwd").val()));
        return true;
    };
</script>
<{include file="footer.tpl"}>
