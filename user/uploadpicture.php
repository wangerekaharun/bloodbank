

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../assets/images/logo.png" type="image/x-icon">
  <meta name="description" content="">
  <title>Update Profile Picture</title>
  
  

  <link rel="stylesheet" href="../assets/css/material.css">
  <link rel="stylesheet" href="../assets/css/tether.min.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/socicon.min.css">
  <link rel="stylesheet" href="../assets/css/animate.min.css">
  <link rel="stylesheet" href="../assets/dropdown/css/style.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/add.css" type="text/css">
  
  
  
</head>
<body>
<section id="ext_menu-s">

    <nav class="navbar navbar-dropdown navbar-fixed-top">
        <div class="container">

            <div class="mbr-table">
                <div class="mbr-table-cell">

                    <div class="navbar-brand">
                        <a href="index" class="navbar-logo"><img src="../assets/images/logo.png" alt="Mobirise"></a>
                        <a class="navbar-caption" href="index.html">BLOODBANK</a>
                    </div>

                </div>
                <div class="mbr-table-cell">

                    <button class="navbar-toggler pull-xs-right hidden-md-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                        <div class="hamburger-icon"></div>
                    </button>

                    <ul class="nav-dropdown collapse pull-xs-right nav navbar-nav navbar-toggleable-sm" id="exCollapsingNavbar">
					<li class="nav-item"><a class="nav-link link" href="index.html">HOME</a></li>
					<li class="nav-item"><a class="nav-link link" href="account">ACCOUNT</a></li>
					  <li class="nav-item"><a class="nav-link link" href="../camps">CAMPS</a></li>
					  <li class="nav-item"><a class="nav-link link" href="../search">SEARCH</a></li><li class="nav-item"><a class="nav-link link" href="../about">ABOUT</a></li><li class="nav-item dropdown"><a class="nav-link link dropdown-toggle" href="https://mobirise.com/" data-toggle="dropdown-submenu" aria-expanded="false">HELP</a><div class="dropdown-menu"><a class="dropdown-item" href="contact.html">CONTACT US</a><a class="dropdown-item" href="faqs.html">FAQS</a></div></li></ul>
                    <button hidden="" class="navbar-toggler navbar-close" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                        <div class="close-icon"></div>
                    </button>

                </div>
            </div>

        </div>
    </nav>

</section>

<section class="mbr-section mbr-after-navbar" id="uploadpicture" style="background-color: rgb(242, 242, 242); padding-top: 120px; padding-bottom: 120px;">
<?php

	error_reporting( ~E_NOTICE ); // avoid notice
	
	require_once 'db.php';
	include 'log.php';

	if(!isset($_SESSION['userSession']))
{
	header("Location: account");
}

$query = "SELECT * FROM donarregister WHERE email='$_SESSION[userSession]'";
$account1 = mysqli_query($MySQLi_CON,$query) or die (mysqli_error());
$account = mysqli_fetch_array($account1);


	$query = $MySQLi_CON->query("SELECT ppic FROM donarregister WHERE email='$_SESSION[userSession]'");
		$row=$query->fetch_array();
	
	if(isset($_POST['upload_pic']))
	{	
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
		
		if(empty($imgFile)){
			$errMSG = "<div class='alert bg-info'>
						<button class='close' data-dismiss='alert'>&times;</button> Please select image file!!!
					</div>";
		}
		 else{
			$upload_dir = '../assets/images/'; // upload directory
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
		
			// valid image extensions
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		
			// rename uploading image
			$userpic = rand(1000,1000000).".".$imgExt;
				
			// allow valid image file formats
			if(in_array($imgExt, $valid_extensions)){			
				// Check file size < 5MB'
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}else{
					$errMSG = "<div class='alert bg-info'>
						<button class='close' data-dismiss='alert'>&times;</button> Sorry, your file is too large!!!
					</div>";
					
				}
			}
			else{
				$errMSG = "<div class='alert bg-info'>
						<button class='close' data-dismiss='alert'>&times;</button> Sorry, only JPG, JPEG, PNG & GIF files are allowed.
					</div>";	
			}
		}
		
		

		
			// if no error occured, continue ....
		if(!isset($errMSG))
		{


		
		if (!$row['ppic']=="") {
				
			// select image from db to delete
		$stmt_select = $MySQLi_CON->query("SELECT ppic FROM donarregister WHERE email='$_SESSION[userSession]'");
	//	$stmt_select->execute(array('$_SESSION[userSession]'=>$_GET['upload_pic']));
		$imgRow=$stmt_select->fetch_array();
		unlink("../assets/images/".$imgRow['ppic']); }

			$stmt = $MySQLi_CON->prepare("UPDATE  donarregister SET ppic='$userpic' WHERE email='$_SESSION[userSession]'");
			//$stmt->bindParam(':uname',$username);
			//$stmt->bindParam(':ujob',$userjob);
			//$stmt->bindParam(':upic',$userpic);
			
			if($stmt->execute())
			{
				$successMSG ="<div class='alert bg-succeed'>
					<button class='close' data-dismiss='alert'>&times;</button>
					Photo Succesfully Uploaded 
			  	</div>";
				header("refresh:3;account"); // redirects image view page after 5 seconds.
			}
			else
			{
				$errMSG = "<div class='alert bg-info'>
						<button class='close' data-dismiss='alert'>&times;</button> Sorry, an error occured during uploading, 
						Please try again.
					</div>";
				
			}
		}


	}
?>

<!--*************PHP PROFILE PHOTO******-->
<?php

include_once 'db.php';

if ($account['ppic']=="") {
	$msg4 = "<img src='../assets/images/profile.jpg' style='border-radius:6px; border:8px solid #fff; padding:0px;' height=150px />";
}//else {
	//$msg4 = "";
//}
	?>


 <div class="container">


	<div class="col-md-8 col-md-offset-2 text-xs-center">
    	                <h3 class="mbr-section-title display-2">Update Your Profile Picture</h3>
   
    

	<?php
	if(isset($errMSG)){
			?>
            <div >
            	 <?php echo $errMSG; ?>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div >
              <?php echo $successMSG; ?>
        </div>
        <?php
	}
	?>   
</div>
<form method="post" enctype="multipart/form-data" class="form-horizontal">
	   <div class="col-md-6" >
	
  
    
    
    	<label class="control-label">Profile Photo</label><br>
        <input class="input-group" type="file" name="user_image" accept="image/*" /><br><br>
    
    <br><br>
   
        <button type="submit" name="upload_pic" class="btn btn-lg btn-success">
         Upload
        </button>
       
    <br>
    
    
    </div>
</form>

<!--<div class="alert alert-info">
    <strong>Back</strong> <a href="account.thml">Home</a>!
</div>-->

    

</div>  
    

</section>

<section class="mbr-section mbr-section-md-padding mbr-footer footer1" id="contacts1-r" style="background-color: rgb(190, 22, 22); padding-top: 60px; padding-bottom: 30px;">
    
    <div class="container">
        <div class="row">
            <div class="mbr-footer-content col-xs-12 col-md-3">
                <div><img src="../assets/images/logo.png"></div>
            </div>
            <div class="mbr-footer-content col-xs-12 col-md-3">
                <p><strong>Address</strong><br>30300<br>Moi University, Eldoret</p>
            </div>
            <div class="mbr-footer-content col-xs-12 col-md-3">
                <p><strong>Contacts</strong><br>
Email: support@bloodbank.appslab.co.ke<br>
Phone: +245 719 772 274<br></p>
            </div>
            <div class="mbr-footer-content col-xs-12 col-md-3">
                <p></p><p><strong>Links</strong><br>
<a href="bloodrequest.php" class="text-white">Send Request</a><br><a href="viewrequest" class="text-white">View Request</a><br><a href="../camps" class="text-white">Camps</a><br><a href="../about" class="text-white">About</a><br><a href="../contact" class="text-white">Contact us</a><br></p><p></p>
            </div>

        </div>
    
</section>

<footer class="mbr-small-footer mbr-section mbr-section-nopadding" id="footer1-9" style="background-color: rgb(190, 22, 22); padding-top: 1.75rem; padding-bottom: 1.75rem;">
    
    <div class="container">
        <p class="text-xs-center">Copyright (c) 2016 Bloodbank.</p>
    </div>
</footer>


  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/tether.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/SmoothScroll.js"></script>
  <script src="../assets/js/jquery.viewportchecker.js"></script>
  <script src="../assets/dropdown/js/script.min.js"></script>
  <script src="../assets/js/jquery.touchSwipe.min.js"></script>
  <script src="../assets/js/script.js"></script>
  
  
  <input name="animation" type="hidden">
   <div id="scrollToTop" class="scrollToTop mbr-arrow-up"><a style="text-align: center;"><i class="mbr-arrow-up-icon"></i></a></div>
  </body>
</html>