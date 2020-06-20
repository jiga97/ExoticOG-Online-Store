<!DOCTYPE HTML>
 
 	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
 
 <link rel="stylesheet" href="css/LoginCss.css">
  <link rel="stylesheet" type="text/css" href="css/Login.css">

 <HTML>
<H1>LogIn</H1>
	<body>
	<div class = "loginbox">
	<img src="Images/logo.jpeg" class = "avatar">
	<h1></h1>
	
	
	</HTML>	

<?php
require_once("aShopCart.php"); 
session_start();

if (isset($_SESSION['guestCart'])){
	
		$Cart = unserialize($_SESSION['guestCart']);
		//echo('<p>Cart Exists</p>');
		//Session_destroy();
	
	}else
	{
		if (class_exists('ShoppingCart')){
			$Cart = new ShoppingCart();
			
			//$_SESSION['guestCart'] = $Cart;
				
		//echo('<p>Created new shopping cart</p>');
		}else{
			//echo('<p>The SHOPPING cart class not available!</p>');
		}
	}
	
	
	//Get The user to log In
//-----------------------------------------------------------------------------	

?>


<?php

	if(isset($_POST['submit']))
	{
		$myEmail =$_POST["username"];
		$myPassword = md5($_POST["password"]); 
		$user_exist = $Cart->setLogInBoolean($myEmail, $myPassword);
		
		if($user_exist === true){
		$_SESSION['guestCart'] = serialize($Cart);
		header("Location: myShop.php");	
		}else{
			echo "Username and password do not match";
		}
	}
	if(isset($_POST['CreateAccount']))
	{
		$_SESSION['guestCart'] = serialize($Cart);
		header("Location: Register.php");
		
	}
	
	//mysqli_close($conn);
?>
 <HTML>

		<form method="post">
			Username:<br/>
			<input type="text" name="username" value = <?php echo @$_SESSION["myEmail"];?>><br/>
			Password<br/>
			<input type="password" name="password"  > <br/>
			<div class = .login_btn><input type="submit" name="submit" id = "submit" value="Login"></div><br/>
			<input type="submit" name="CreateAccount" id = "CreateAccount" value="Create Account"><br/>
			</form>
			</div>
	</body>
</HTML>