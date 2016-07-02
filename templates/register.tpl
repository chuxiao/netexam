<{include file="header.tpl"}>
<script type="text/javascript" src="static/js/moment.min.js"></script>
<script type="text/javascript" src="static/js/combodate.js"></script>
<script type="text/javascript" src="static/js/sha1.js"></script>
<div id="register_form">
    <form method="post" action="/?ct=register&ac=register" onsubmit="return submit_check()">
        <div id="account_area">
            <label for="account">手机号: </label>
            <input id="account" name="account" type="text"></input>
        </div>
        <div id="passwd_area">
            <label for="passwd">密码: </label>
            <input id="passwd" name="passwd" type="password"></input>
            <label for="passwd2">密码确认: </label>
            <input id="passwd2" name="passwd2" type="password"></input>
        </div>
        <div id="details">
            <label for="nickname">昵称: </label>
            <input id="nickname" name="nickname" type="text"></input>
            <label>性别: </label>
            <input type="radio" checked="checked" name="gender" value="1" />男
            <input type="radio" name="gender" value="0" />女
            <label for="birthday">出生年月: </label>
            <input id="birthday" data-format="YYYY-MM" data-template="YYYY年MM月" name="birthday" value="1970-01" type="text" />
        </div>
        <div id="verify_area">
            <label for="verify_code">验证码: </label>
            <input id="verify_code" name="verify_code" type="text"></input>
            <span class="verify_image"><img id="code_img" src="/?ct=register&ac=verifycode&time=<{$time}>" title="看不清，请点击重试" onClick="change_code()" height="25" width="76" /></span><a href="#" onClick="change_code()">换一个</a>
        </div>
        <div id="auth_area">
            <label for="auth_code">手机验证码: </label>
            <input id="auth_code" name="auth_code" type="auth_code"></input>
            <input id="phone_code_btn" type="button" value="免费获取手机验证码"></input>
        </div>
        <div id="commit_area">
            <input id="register_btn" type="submit" value="注册"></input>
        </div>
    </form>
</div>
<script type="text/javascript">
    function change_code() {
        var code_img = document.getElementById('code_img');
        code_img.src = '?ct=register&ac=verifycode&seed=' + Math.random();
    }
    change_code();
    $('#birthday').combodate({minYear: 1960, maxYear: 2010});
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
    $("#phone_code_btn").click(function() {
        if ($("#account").val() == "") {
            // TODO:
            $("#account").focus();
        }
        else if($("#verify_code").val() == "") {
            // TODO:
            $("#verify_code").focus();
        }
        else {
            $.post({
            url: "/?ct=login&ac=get_mobile_key",
            data: {
                account: $("#account").val(),
                verify_code: $("#verify_code").val()
                }
            });
            time(this);
        }
    });
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
        if ($("#nickname").val() == "") {
            // TODO:
            $("#nickname").focus();
            return false;
        }
        if ($("#verify_code").val() == "") {
            // TODO:
            $("#verify_code").focus();
            return false;
        }
        if ($("#auth_code").val() == "") {
            // TODO:
            $("#auth_code").focus();
            return false;
        }
        $("#passwd").val(hex_sha1($("#passwd").val()));
        $("#passwd2").val(hex_sha1($("#passwd2").val()));
        return true;
    }
</script>
<{include file="footer.tpl"}>
