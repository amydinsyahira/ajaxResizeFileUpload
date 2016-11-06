<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ajax Upload and Resize with jQuery and PHP</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
 <script> 
        $(document).ready(function() { 
			$('#UploadForm').on('submit', function(e) {
				e.preventDefault();
				$('#SubmitButton').attr('disabled', ''); // disable upload button
				//show uploading message
				$("#output").html('<div style="padding:10px"><img src="images/ajax-loader.gif" alt="Please Wait"/> <span>Uploading...</span></div>');
				$(this).ajaxSubmit({
					target: '#output',
					success:  afterSuccess //call function after success
				});
			});
        }); 

		function afterSuccess()  { 
			$('#UploadForm').resetForm();  // reset form
			$('#SubmitButton').removeAttr('disabled'); //enable submit button

		} 
    </script> 

 <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="Wrapper">

<div align="center">
<form action="processupload.php" method="post" enctype="multipart/form-data" id="UploadForm">
<input name="ImageFile" type="file" />
<input type="submit"  id="SubmitButton" value="Upload" />
</form>

<div id="output"></div>
</div>
</div>
</body>
</html>

