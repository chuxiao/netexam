<{include file="header.tpl"}>
<div id="reset_passwd_form">
    <form method="post" action="/?ct=login&ac=reset_passwd">
        <div id="passwd_area">
            <label for="passwd">密码: </label>
            <input id="passwd" name="passwd" type="password"></input>
        </div>
        <div id="passwd_area2">
            <label for="passwd2">再次输入密码: </label>
            <input id="passwd2" name="passwd2" type="password"></input>
        </div>
        <div id="commit_area">
            <input id="login_btn" type="submit" value="Login"></input>
        </div>
    </form>
</div>
<{include file="footer.tpl"}>
