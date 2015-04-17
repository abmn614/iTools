<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>文件上传类</title>
</head>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file[]">
    <input type="file" name="file[]">
    <br />
    <input type="submit" value="上传">
</form>

</body>
</html>