<{include file="header.tpl"}>
<div id="find_passwd_form">
    <form method="post" action="/?ct=login&ac=auth2">
        <div id="account_area">
            <label for="account">手机号: </label>
            <input id="account" name="account" type="text"></input>
        </div>
        <div id="verify_area">
            <label for="verify_code">验证码: </label>
            <input id="verify_code" name="verify_code" type="text"></input>
            <span class="verify_image"><img id="code_img" src="/?ct=register&ac=verifycode&time=<{$time}>" title="看不清，请点击重试" onClick="change_code()" height="25" width="76" /></span><a href="#" onClick="change_code()">换一个</a>
        </div>
        <a href="#" onClick="get_auth_code()">获取手机密令</a>
        <div id="auth_area">
            <label for="auth_code">手机密令: </label>
            <input id="auth_code" name="auth_code" type="auth_code"></input>
        </div>
        <div id="commit_area">
            <input id="login_btn" type="submit" value="确定"></input>
        </div>
    </form>
    <p>手机密令将在60秒后过期，如过期请重新刷新页面并获取密令。</p>
</div>
<script type="text/javascript">
    function change_code() {
        var code_img = document.getElementById('code_img');
        code_img.src = '?ct=register&ac=verifycode&seed=' + Math.random();
    }
    change_code();
    function get_auth_code() {
    }
</script>
<{include file="footer.tpl"}>
