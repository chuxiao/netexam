<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Login</title>
    </head>
    <body>
        <present name="error_msg">
        <p>{$error_msg}</p>
        </present>
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
    </body>
</html>

