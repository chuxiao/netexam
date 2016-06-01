<{include file="header.tpl"}>
<script type="text/javascript" src="static/js/moment.min.js"></script>
<script type="text/javascript" src="static/js/combodate.js"></script>
<div id="center_form">
    <form method="post" enctype="multipart/form-data" action="/admin/?ct=center&ac=upload">
        <div id="datearea">
            <label for="date">日期: </label>
            <input id="date" data-format="YYYY-MM-DD" data-template="YYYY年MM月DD日" name="date" value="<{$date}>" type="text" />
        </div>
        <div id="time_area">
            <label for="time">开启时间: </label>
            <input id="time" data-format="HH:mm" data-template="HH:mm" name="time" value="<{$time}>" type="text" />
        </div>
        <div id="file_area">
            <label for="file">请上传文件: </label>
            <input id="file" name="file" type="file"></input>
        </div>
        <div id="commit_area">
            <input id="upload_btn" type="submit" value="提交"></input>
        </div>
    </form>
</div>
<div id="datelist">
    <table border="1">
        <tr>
            <th>日期</th>
            <th>开启时间</th>
            <th>操作</th>
        </tr>
        <{foreach item=item from=$datelist}>
        <tr>
            <td><{$item.date}></td>
            <td><{$item.time}></td>
            <td><a href="/admin/?ct=center?ac=show?date=<{$item.date}>">查看</a><a href="/admin/uploads/<{$item.date}>.xlsx">下载</a><a href="/admin/?ct=center?ac=remove?date=<{$item.date}>">删除</a></td>
        </tr>
        <{/foreach}>
    </table>
</div>
<script>
    $('#date').combodate({minYear: 2016, maxYear: 2020});
    $('#time').combodate({minuteStep: 10});
</script>
<a href="/admin/?ct=login&ac=logout">注销</a>
<{include file="footer.tpl"}>
