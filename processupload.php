<noscript>
<div align="center"><a href="index.php">Go Back To Upload Form</a></div><!-- If javascript is disabled -->
</noscript>
<?php
if(isset($_POST))
{
	 //Some Settings
	$ThumbMaxWidth 			= 200; //Thumbnail width
	$ThumbMaxHeight 		= 200; //Thumbnail Height
	$BigImageMaxWidth 		= 500; //Resize Image width to
	$BigImageMaxHeight 		= 500; //Resize Image height to
	$ThumbPrefix			= "thumb_"; //Normal thumb Prefix
	$DestinationDirectory	= 'uploads/'; //Upload Directory
	$jpg_quality 			= 90;

	// check if file upload went ok
	if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name']))
	{
			die('Something went wrong with Upload, May be File too Big?'); //output error
	}

	$RandomNumber 	= rand(0, 9999999999); // We need same random name for both files.
	
	// some information about image we need later.
	$ImageName 		= strtolower($_FILES['ImageFile']['name']);
	$ImageSize 		= $_FILES['ImageFile']['size']; 
	$TempSrc	 	= $_FILES['ImageFile']['tmp_name'];
	$ImageType	 	= $_FILES['ImageFile']['type'];
	$process 		= true;
	
	//Validate file + create image from uploaded file.
	switch(strtolower($ImageType))
	{
		case 'image/png':
			$CreatedImage = imagecreatefrompng($_FILES['ImageFile']['tmp_name']);
			break;		
		case 'image/gif':
			$CreatedImage = imagecreatefromgif($_FILES['ImageFile']['tmp_name']);
			break;
		case 'image/jpeg':
			$CreatedImage = imagecreatefromjpeg($_FILES['ImageFile']['tmp_name']);
			break;
		default:
			die('Unsupported File!'); //output error
	}

	//get Image Size
	list($CurWidth,$CurHeight)=getimagesize($TempSrc);
	
	//get file extension, this will be added after random name
	$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
  	$ImageExt = str_replace('.','',$ImageExt);
	
	//Set the Destination Image path with Random Name
	$thumb_DestRandImageName 	= $DestinationDirectory.$ThumbPrefix.$RandomNumber.'.'.$ImageExt; //Thumb name
	$DestRandImageName 			= $DestinationDirectory.$RandomNumber.'.'.$ImageExt; //Name for Big Image
	
	//Resize image to our Specified Size by calling our resizeImage function.
	if(resizeImage($CurWidth,$CurHeight,$BigImageMaxWidth,$BigImageMaxHeight,$DestRandImageName,$CreatedImage))
	{
		//Create Thumbnail for the Image
		resizeImage($CurWidth,$CurHeight,$ThumbMaxWidth,$ThumbMaxHeight,$thumb_DestRandImageName,$CreatedImage);
		
		//respond with our images
		echo '<table width="100%" border="0" cellpadding="4" cellspacing="0">
			<tr><td align="center"><img src="uploads/'.$ThumbPrefix.$RandomNumber.'.'.$ImageExt.'" alt="Thumbnail"></td></tr><tr>
			<td align="center"><img src="uploads/'.$RandomNumber.'.'.$ImageExt.'" alt="Resized Image"></td></tr></table>';
		
		/*
			// Insert info into database table.. do w.e!
			mysql_query("INSERT INTO myImageTable (ImageName, ThumbName, ImgPath)
			VALUES ($DestRandImageName, $thumb_DestRandImageName, 'uploads/')");
		*/
	}else{
		die('Resize Error'); //output error
	}

}

function resizeImage($CurWidth,$CurHeight,$MaxWidth,$MaxHeight,$DestFolder,$SrcImage)
{
	$ImageScale      	= min($MaxWidth/$CurWidth, $MaxHeight/$CurHeight);
	$NewWidth  			= ceil($ImageScale*$CurWidth);
	$NewHeight 			= ceil($ImageScale*$CurHeight);
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
	// Resize Image
	if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
	{
		// copy file
		if(imagejpeg($NewCanves,$DestFolder,100))
		{
			imagedestroy($NewCanves);
			return true;
		}
	}
}