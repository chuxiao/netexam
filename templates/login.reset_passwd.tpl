<{include file="header.tpl"}>
<script type="text/javascript" src="static/js/sha1.js"></script>
<div id="reset_passwd_form">
    <form method="post" action="/?ct=login&ac=reset_passwd" onsubmit="return submit_check()">
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
<script>
    function submit_check() {
        if ($("#passwd").val() == "") {
            // TODO:
            $("#passwd").focus();
            return false;
        }
        if ($("#passwd2").val() == "") {
            // TODO:
            $("#passwd2").focus();
            return false;
        }
        if ($("#passwd").val() != $("#passwd2").val()) {
            // TODO:
            $("#passwd").val("");
            $("#passwd2").val("");
            $("#passwd").focus();
            return false;
        }
        $("#passwd").val(hex_sha1($("#passwd").val()));
        $("#passwd2").val(hex_sha1($("#passwd2").val()));
        return true;
    }
</script>
<{include file="footer.tpl"}>
