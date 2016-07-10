<{include file="header.tpl"}>
<div id="basic">
    <p>当前题号: <span id="current_no"><{$question.qid}></span></p>
    <p>总题数: <{$total_count}></p>
    <p>本题分值: <span id="current_socre"><{$question.score}></span></p>
    <p><input type="button" onClick="next_question()">下一题</input>
</div>
<div>
<p>剩余时间:<span id="timer"><{$question.timer}></span></p>
<p><{$question.question}><p>
<p id="A"><input id="C_A" name="choice" type="checkbox" value="A" />A: <span class="option"><{$question.A}></span></p>
<p id="B"><input id="C_B" name="choice" type="checkbox" value="B" />B: <span class="option"><{$question.B}></span></p>

<{if $question.C}>
<p id="C"><input id="C_C" name="choice" type="checkbox" value="C" />C: <span class="option"><{$question.C}></span></p>
<{else}>
<p id="C" style="display:none;"><input id="C_C" name="choice" type="checkbox" value="C" />C: <span class="option"></span></p>
<{/if}>

<{if $question.D}>
<p id="D"><input id="C_D" name="choice" type="checkbox" value="D" />D: <span class="option"><{$question.D}></span></p>
<{else}>
<p id="D" style="display:none;"><input id="C_D" name="choice" type="checkbox" value="D" />D: <span class="option"></span></p>
<{/if}>

<{if $question.E}>
<p id="E"><input id="C_E" name="choice" type="checkbox" value="E" />E: <span class="option"><{$question.E}></span></p>
<{else}>
<p id="E" style="display:none;"><input id="C_E" name="choice" type="checkbox" value="E" />E: <span class="option"></span></p>
<{/if}>

<{if $question.F}>
<p id="F"><input id="C_F" name="choice" type="checkbox" value="F" />F: <span class="option"><{$question.F}></span></p>
<{else}>
<p id="F" style="display:none;"><input id="C_F" name="choice" type="checkbox" value="F" />F: <span class="option"></span></p>
<{/if}>

<{if $question.G}>
<p id="G"><input id="C_G" name="choice" type="checkbox" value="G" />G: <span class="option"><{$question.G}></span></p>
<{else}>
<p id="G" style="display:none;"><input id="C_G" name="choice" type="checkbox" value="G" />G: <span class="option"></span></p>
<{/if}>

<{if $question.H}>
<p id="H"><input id="C_H" name="choice" type="checkbox" value="H" />H: <span class="option"><{$question.H}></span></p>
<{else}>
<p id="H" style="display:none;"><input id="C_H" name="choice" type="checkbox" value="H" />H: <span class="option"></span></p>
<{/if}>

<{if $question.I}>
<p id="I"><input id="C_I" name="choice" type="checkbox" value="I" />I: <span class="option"><{$question.I}></span></p>
<{else}>
<p id="I" style="display:none;"><input id="C_I" name="choice" type="checkbox" value="I" />I: <span class="option"></span></p>
<{/if}>

<{if $question.J}>
<p id="J"><input id="C_J" name="choice" type="checkbox" value="J" />J: <span class="option"><{$question.J}></span></p>
<{else}>
<p id="J" style="display:none;"><input id="C_J" name="choice" type="checkbox" value="J" />J: <span class="option"></span></p>
<{/if}>

<p id="show_answer" style="display:none;">正确答案: <span id="answer"><{$question.answer}></span></p>

<script type="text/javascript">
    var total = <{$total_count}>;
    var answer = <{$question.answer}>;
    var time_left = <{$question.timer}>;
    var time_keep = <{$question.keep_time}>;
    var eid = <{$question.eid}>;
    var qid = <{$question.qid}>;
    var question = "";
    var A = "";
    var B = "";
    var C = "";
    var D = "";
    var E = "";
    var F = "";
    var G = "";
    var H = "";
    var I = "";
    var J = "";
    var score = 0;
    var phase = 1;
    function next_question()
    {
        // 显示正确答案
        $("#show_answer").show();
        phase = 2;
        // 提交结果并获取下一道题目信息
        commit_and_next();
    }
    function timer()
    {
        if (phase == 1)
        {
            // 答题倒计时
            if (time_left == 0)
            {
                // 显示正确答案
                $("#show_answer").show();
                phase = 2;
                // 提交结果并获取下一道题目信息
                commit_and_next();
            }
            else
            {
                $("#timer").val(time_left);
                --time_left;
            }
        }
        else if (phase == 2)
        {
            // 反馈倒计时
            if (time_keep == 0)
            {
                // 更新题目
                update_question();
                phase = 1;
            }
            else
            {
                --time_keep;
            }
        }
        setTimeout("timer()", 1000);
    };
    timer();
    function commit_and_next()
    {
        var result = "";
        if ($("#C_A").prop("checked") == true)
        {
            result += "A";
        }

        if ($("#C_B").prop("checked") == true)
        {
            result += "B";
        }

        if ($("#C_C").prop("checked") == true)
        {
            result += "C";
        }

        if ($("#C_D").prop("checked") == true)
        {
            result += "D";
        }

        if ($("#C_E").prop("checked") == true)
        {
            result += "E";
        }

        if ($("#C_F").prop("checked") == true)
        {
            result += "F";
        }

        if ($("#C_G").prop("checked") == true)
        {
            result += "G";
        }

        if ($("#C_H").prop("checked") == true)
        {
            result += "H";
        }

        if ($("#C_I").prop("checked") == true)
        {
            result += "I";
        }

        if ($("#C_J").prop("checked") == true)
        {
            result += "J";
        }
        .getJSON("/?ct=exam&ac=next_q&result=" + result + "&eid=" + eid + "&qid=" + qid,
            function(data)
            {
                if (date.ret == 2)
                {
                    document.location.href = "/?ct=exam&ac=over&$eid=" + eid;
                    return;
                }
                eid = data.eid;
                qid = data.qid;
                question = data.question;
                A = data.A;
                B = data.B;
                C = data.C;
                D = data.D;
                E = data.E;
                F = data.F;
                G = data.G;
                H = data.H;
                I = data.I;
                J = data.J;
                answer = data.answer;
                score = data.score;
                time_left = data.timer;
                time_keep = data.keep_time;
            }
        );
    }

    function update_question()
    {
        $("#C_A").prop("checked", false);
        $("#C_B").prop("checked", false);
        $("#C_C").prop("checked", false);
        $("#C_D").prop("checked", false);
        $("#C_E").prop("checked", false);
        $("#C_F").prop("checked", false);
        $("#C_G").prop("checked", false);
        $("#C_H").prop("checked", false);
        $("#C_I").prop("checked", false);
        $("#C_J").prop("checked", false);
        $("#C_A span").val(A);
        $("#C_B span").val(B);
        if (C != null)
        {
            $("#C_C span").val(C);
            $("#C_C").show();
        }
        else
        {
            $("#C_C").hide();
        }

        if (D != null)
        {
            $("#C_D span").val(D);
            $("#C_D").show();
        }
        else
        {
            $("#C_D").hide();
        }

        if (E != null)
        {
            $("#C_E span").val(E);
            $("#C_E").show();
        }
        else
        {
            $("#C_E").hide();
        }

        if (F != null)
        {
            $("#C_F span").val(F);
            $("#C_F").show();
        }
        else
        {
            $("#C_F").hide();
        }

        if (G != null)
        {
            $("#C_G span").val(G);
            $("#C_G").show();
        }
        else
        {
            $("#C_G").hide();
        }

        if (H != null)
        {
            $("#C_H span").val(H);
            $("#C_H").show();
        }
        else
        {
            $("#C_H").hide();
        }

        if (I != null)
        {
            $("#C_I span").val(I);
            $("#C_I").show();
        }
        else
        {
            $("#C_I").hide();
        }

        if (J != null)
        {
            $("#C_J span").val(J);
            $("#C_J").show();
        }
        else
        {
            $("#C_J").hide();
        }
        $("#show_answer").hide();
        $("#current_no").val(qid);
        $("#current_score").val(score);
    }
</script>
<{include file="footer.tpl"}>
