<{include file="header.tpl"}>
<div id="center_form">
    <form method="post" enctype="multipart/form-data" action="/admin/?ct=center&ac=upload">
        <div id="datearea">
            <label for="date">日期: </label>
            <input id="date" name="date" type="text"></input>
        </div>
        <div id="time_area">
            <label for="time">开启时间: </label>
            <input id="time" name="time" type="text"></input>
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
<{include file="footer.tpl"}>
