<!DOCTYPE HTML>
<?php require_once("aShopCart.php"); 
session_start();

//Constructor
if (isset($_SESSION['guestCart'])){
		$Cart = unserialize($_SESSION['guestCart']);
		//Session_destroy();
	}else
	{
		if (class_exists('ShoppingCart')){
			$Cart = new ShoppingCart();

		}else{
		}
	}
	
 if(isset($_POST['submit']))
	{
		$Cart->createNewUser($_POST["FNAME"], $_POST["LNAME"], $_POST["username"], md5($_POST["password"]));
		$_SESSION['guestCart'] = serialize($Cart);
		header("Location: myShop.php");
	}
	//<link rel="stylesheet" href="css/LoginCss.css">
	//<link rel="stylesheet" type="text/css" href="css/Register.css">
?>
 

<HTML>
  
<div class = "loginbox">
	<img src="Images/logo.jpeg" class = "avatar">
	<h1></h1>
	</body>
	

<H1>REGISTER</H1>
	<body>
		<form method="post">
		First Name:<br/>
			<input type="text" name="FNAME"><br/>
			Last Name<br/>
			<input type="text" name="LNAME"><br/>
			Username:<br/>
			<input type="text" name="username"><br/>
			Password<br/>
			<input type="password" name="password"><br/>
			<input type="submit" name="submit" id = "submit" value="Register"><br/>
			</form>
	</body>
	</div>
</HTML>