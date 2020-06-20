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


	
	
</head>
<body>

<?php	
}
?>
<h1>ADMIN PAGE</h1>

<?php
	
	$shoppingCart = $Cart->getProductList(); //Loads all the items for sale


//Get The user to log Out
//-----------------------------------------------------------------------------	
if(isset($_POST['Log_Out']))
	{
	Session_destroy(); //Drops all the sesions that was stored
	header("Location: myShop.php");
	}

	
//Get Item to Add to Inventory
//-----------------------------------------------------------------------------

if(isset($_POST['Add_to_Stock'])){

$Cart->addProductQuantity($_POST['Add_to_Stock']);
$_SESSION['guestCart'] = serialize($Cart);
}
//Get Item removed from the Inventory
//-----------------------------------------------------------------------------

if(isset($_POST['Remove_from_Stock'])){
$Cart->removeProductQuantity($_POST['Remove_from_Stock']);
$_SESSION['guestCart'] = serialize($Cart);
}
	
$shoppingCart = $Cart->getProductList(); 
	
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
echo "<th><button  type=submit name=Add_to_Stock = $row[ITEMID] value=$row[ITEMID]>Add To Stock</button><br/>
<button  type=submit name=Remove_from_Stock = $row[ITEMID] value=$row[ITEMID]>Remove From Stock</button><br/>
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
