<{include file="header.tpl"}>
<div id="login_form">
    <form method="post" action="login/check_user">
        <div id="account_area">
            <label for="account">Account: </label>
            <input id="account" name="account" type="text"></input>
        </div>
        <div id="passwd_area">
            <label for="passwd">Password: </label>
            <input id="passwd" name="passwd" type="password"></input>
        </div>
        <div id="verify_area">
        </div>
        <div id="commit_area">
            <input id="login_btn" type="submit" value="Login"></input>
        </div>
    </form>
</div>
<{include file="footer.tpl"}>
