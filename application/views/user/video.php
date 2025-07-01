<form action="<?php echo base_url('Aws_notify/upload_video'); ?>" method="post" enctype= "multipart/form-data">
    Select image to upload:
    <input type="file" name="userfile" id="fileToUpload">
    <input type="submit">
</form>

<html>
<body>
<form action="<?php echo base_url('Aws_notify/upload_video'); ?>" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="userfile" id="file" size="20" /><br />
<input type="submit" name="submit" value="Submit" />
</form>
</body>
</html>
