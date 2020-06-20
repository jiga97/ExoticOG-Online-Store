<!DOCTYPE html>
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
	
?>




<HTML>
<body>

<link rel="stylesheet" type="text/css" href="css/Table.css"/>
<link rel="stylesheet" href="php_styles.css" type="text/css" />

<?php
$userInfo = $Cart->isUserLogIn();
if (empty($userInfo) === false){

echo"<h4>Hello $userInfo[0] $userInfo[1]</h4>";
?>
<form method="post">
<input type="submit" name="Log_Out" id = "Log_Out" value="Log Out"><br/>
</form>
<?php
}else{
?>
<h4>Already have an account?</h4>
		<form method="post">
			Username: 
			<input type="text" name="username">
			Password
			<input type="password" name="password">
			<div class = .login_btn><input type="submit" name="Log_In" id = "Log_In" value="Login">
			<input style="margin-left:328px" type="submit" name="CreateAccount" id = "CreateAccount" value="Create Account">
			<input style="margin-left:390px" type="submit" name="Admin_Login" id = "Admin_Login" value="Admin Login">
			</div>
			</form>
	</body> 

	
	
</head>
<body>

<?php	
}
?>
<h1>Shoppers Shop</h1>

<?php
	
	$shoppingCart = $Cart->getProductList(); //Loads all the items for sale


//Admin login 
if(isset($_POST['Admin_Login']))
	{
	$_SESSION['guestCart'] = serialize($Cart);
	header("Location: Login_admin.php");	
	}

//Get The user to log In
//-----------------------------------------------------------------------------	
if(isset($_POST['Log_In']))
	{
		$myEmail =$_POST["username"];
		$myPassword = md5($_POST["password"]); 
		$Cart->setLogIn($myEmail, $myPassword);
		$_SESSION['guestCart'] = serialize($Cart);
	//if($boolean === true){
		//$_SESSION['userInfo'] = $userInfo;
		echo"<meta http-equiv='refresh' content='0'>";//Refresh page with User Logged in
	//}
	}	

//Get The user to log Out
//-----------------------------------------------------------------------------	
if(isset($_POST['Log_Out']))
	{
	Session_destroy(); //Drops all the sesions that was stored
	echo"<meta http-equiv='refresh' content='0'>";//Refresh page with User Logged in
	}

	
//Get Item to Add to cart
//-----------------------------------------------------------------------------

if(isset($_POST['Add_to_Cart'])){

$Cart->setGuestUserCart($_POST['Add_to_Cart']);
$_SESSION['guestCart'] = serialize($Cart);
}
//Get Item removed from the cart
//-----------------------------------------------------------------------------

if(isset($_POST['Remove_from_Cart'])){
$Cart->RemoveItemFromGuestCart($_POST['Remove_from_Cart']);
$_SESSION['guestCart'] = serialize($Cart);
}

//Register New User
//-----------------------------------------------------------------------------

if(isset($_POST['CreateAccount'])){
	$_SESSION['guestCart'] = serialize($Cart);
	header("Location: Register.php");
	
}

//CheckOut
//-----------------------------------------------------------------------------
if(isset($_POST['Check_out'])){


$userInfo = $Cart->isUserLogIn();
if (empty($userInfo) === false){

	$_SESSION['guestCart'] = serialize($Cart);
	header("Location: Checkout.php");	
}else{
	$_SESSION['guestCart'] = serialize($Cart);
	header("Location: Login.php");		
}
}

//Clear Cart
//-----------------------------------------------------------------------------
if(isset($_POST['Clear_Cart'])){
	$Cart->ClearUserCart();
$_SESSION['guestCart'] = serialize($Cart);
	
}	
	
$shoppingCart = $Cart->getProductList(); 
echo"<h3>Your shopping Cart</h3>";


//echo "<button type=submit name=Clear_Cart id=Clear_Cart value=Clear_Cart>Clear Cart</button>";
//Display Table
//-----------------------------------------------------------------------------
echo "<table style=width:100% class =blueTable>";
echo  "<tr>";
echo "<th>Image</th>";
echo "<th>DESCRIPTION</th>";
echo "<th>Price Each</th>" ;
echo "<th># in Cart</th>";
echo "<th>Sub Total</th>";
echo "<th><form method='post'>
<input type=submit name=Clear_Cart id=Clear_Cart value=Clear_Cart>
</form></th>" ;
echo  "</tr>";
echo "<form method=post>";
//-----------------------------------------------------------------------------

//Populate User Cart Table
//-----------------------------------------------------------------------------
$totalCost = 0; //resets the total cost
if (mysqli_num_rows($shoppingCart) > 0) {
			// output data of each row
	
		while($row = mysqli_fetch_assoc($shoppingCart)) {
$itemCartAmount =  $Cart->getGuestUserCartNumber($row['ITEMID']);
if($itemCartAmount>0){
$subTotal = $itemCartAmount*$row['SELL_PRICE'];
$totalCost = $totalCost + $subTotal; 				
echo  "<tr>";
echo "<th><img src=Images/$row[ITEMID].jpg alt=$row[DESCRIPTION] height=80 width=80></th>";
echo "<th>$row[DESCRIPTION]</th>";

echo "<th>R$row[SELL_PRICE]</th>" ;

echo "<th>$itemCartAmount</th>" ;

echo "<th>R$subTotal</th>" ;
echo "<th><button  type=submit name=Add_to_Cart = $row[ITEMID] value=$row[ITEMID]>Add To Cart</button><br/>
<button  type=submit name=Remove_from_Cart = $row[ITEMID] value=$row[ITEMID]>Remove From Cart</button><br/>
</th>";
echo  "</tr>";		
}
}
echo "<th colspan = 3>" ;
echo "SubTotal</th>";
echo "<th colspan = 2>" ;
echo "R$totalCost</th>";
echo "<th><button  type=submit name=Check_out id = Check_out value=Check_out>Check Out</button><br/>";
echo"</table>";	
}
	
	
//Display Inventory
//-----------------------------------------------------------------------------
echo "<table style=width:100% class =blueTable>";
echo  "<tr>";
echo "<th>Image</th>";
echo "<th>DESCRIPTION</th>";
echo "<th>Price Each</th>" ;
echo "<th>Inventory</th>";
//echo "<th>Sub Total</th>";
echo "<th>Add to Cart</th>" ;
echo  "</tr>";
echo "<form method=post>";
//-----------------------------------------------------------------------------
echo"<h3>Our Inventory</h3>";
//Populate Table
//-----------------------------------------------------------------------------
$shoppingCart = $Cart->getProductList(); 
$totalCost = 0; //resets the total cost
if (mysqli_num_rows($shoppingCart) > 0) {
			// output data of each row
	
while($row = mysqli_fetch_assoc($shoppingCart)) {
//$itemCartAmount =  $Cart->getGuestUserCartNumber($row['ITEMID']);
//$subTotal = $itemCartAmount*$row['SELL_PRICE'];
//$totalCost = $totalCost + $subTotal; 				
echo  "<tr>";
echo "<th><img src=Images/$row[ITEMID].jpg alt=$row[DESCRIPTION] height=80 width=80></th>";
echo "<th>$row[DESCRIPTION]</th>";

echo "<th>R$row[SELL_PRICE]</th>" ;

echo "<th>$row[QUANTITY]</th>" ;

//echo "<th>R$subTotal</th>" ;
echo "<th><button  type=submit name=Add_to_Cart = $row[ITEMID] value=$row[ITEMID]>Add To Cart</button><br/>
<button  type=submit name=Remove_from_Cart = $row[ITEMID] value=$row[ITEMID]>Remove From Cart</button><br/>
</th>";
echo  "</tr>";		

			}
//echo "<th colspan = 3>" ;
//echo "SubTotal</th>";
//echo "<th colspan = 2>" ;
//echo "R$totalCost</th>";
//echo "<th><button  type=submit name=Check_out id = Check_out value=Check_out>Check_out</button><br/>";

}
	
?>
</body>
</html>
