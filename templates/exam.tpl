<{include file="header.tpl"}>
<div id="basic">
    <p>当前题号: <span id="current_num"><{$question.qid}></span></p>
    <p>总题数: <{$total_count}></p>
    <p>本题分值: <{$question.score}></p>
    <p><input type="button" onClick="next_question()">下一题</input>
</div>
<div>
<p>剩余时间:<span id="timer"><{$question.timer}></span></p>
<p><{$item.question}><p>
<p>A: <{$item.A}></p>
<p>B: <{$item.B}></p>
<{if $item.C}>
<p>C: <{$item.C}></p>
<{/if}>
<{if $item.D}>
<p>D: <{$item.D}></p>
<{/if}>
<{if $item.E}>
<p>E: <{$item.E}></p>
<{/if}>
<{if $item.F}>
<p>F: <{$item.F}></p>
<{/if}>
<{if $item.G}>
<p>G: <{$item.G}></p>
<{/if}>
<{if $item.H}>
<p>H: <{$item.H}></p>
<{/if}>
<{if $item.I}>
<p>I: <{$item.I}></p>
<{/if}>
<{if $item.J}>
<p>J: <{$item.J}></p>
<{/if}>
<{/foreach}>
<script type="text/javascript">
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
    var answer = "";
    var score = 0;
    var phase = 1;
    function next_question()
    {
        // 提交结果
        // 获取下一道题目信息
    }
    function timer()
    {
        if (phase == 1)
        {
            // 答题倒计时
            if (time_left == 0)
            {
                // 提交结果
                // 获取下一道题目信息
                phase = 2;
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
</script>
<{include file="footer.tpl"}>
