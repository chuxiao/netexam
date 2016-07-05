<{include file="header.tpl"}>
<div id="basic">
    <p>考试时间: <{$exam.effect_time}></p>
    <p><a href="/admin/uploads/<{$exam.file_name}>">文件下载</a></p>
    <p>题目数量: <{$exam.question_count}></p>
    <p><a href="/admin/?ct=center">返回</a></p>
</div>
<h3>题目内容</h3>
<div>
<{foreach item=item from=$questions}>
<h5>第<{$item.id}>题</h5>
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
<p>正确答案:<{$item.answer}></p>
<p>本题分数:<{$item.score}> 答题时间:<{$item.timer}> 反馈时间:<{$item.keep_time}></p>
<{/foreach}>
</div>
<{include file="footer.tpl"}>
