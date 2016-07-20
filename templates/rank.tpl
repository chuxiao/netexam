<{include file="header.tpl"}>
<{if $prev_eid ne 0}>
    <a href="/?ct=rank&eid=<{$prev_eid}>">查看上一期排行榜</a>
<{/if}>
<table border="1">
<tr>
    <th>用户名</th>
    <th>分数</th>
    <th>名次</th>
</tr>
<{if $rank_list}>
    <{foreach item=item from=$rank_list}>
    <tr>
        <td><{$item.user_name}></td>
        <td><{$item.score}></td>
        <td><{$item.rank}></td>
    </tr>
    <{/foreach}>
<{/if}>
<{include file="footer.tpl"}>
