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
	

?>
 

<HTML>
<form method="post">
			<div class = .login_btn><input type="submit" name="Lets_Go_Back" id = "Lets_Go_Back" value="Lets Go Back">
			<input type="submit" name="Log_Out" id = "Log_Out" value="Log Out"><br/>
			</div>
			</form>
<link rel="stylesheet" type="text/css" href="css/Table.css"/>
<link rel="stylesheet" href="php_styles.css" type="text/css" />
	<body>
	<h2>Hey! <?php
$userInfo = $Cart->isUserLogIn();
if (empty($userInfo) === false){
echo"$userInfo[0] $userInfo[1]";
}
?> Your order has been proccessed</h2>
	<h3>Check Out</h3>
	
<?php

if(isset($_POST['Lets_Go_Back'])){
	$_SESSION['guestCart'] = serialize($Cart);
	header("Location: myShop.php");
	
}


if(isset($_POST['Log_Out'])){
	Session_destroy(); //Drops all the sesions that was stored
	header("Location: myShop.php");
}


//User places order
if(isset($_POST['Place_Order'])){
	
$size = $Cart->getCartSize();
if($size>0){
$Cart->InsertItemOrderToDatabase();
$Cart->ClearUserCart();
$_SESSION['guestCart'] = serialize($Cart);
}
}


$shoppingCart = $Cart->getProductList(); 


//Display Table
//-----------------------------------------------------------------------------
$totalCost = 0; //resets the total cost
if (mysqli_num_rows($shoppingCart) > 0) {

echo "<table style=width:100% class =blueTable>";
echo  "<tr>";
echo "<th>Image</th>";
echo "<th>DESCRIPTION</th>";
echo "<th>Price Each</th>" ;
echo "<th># in Cart</th>";
echo "<th>Sub Total</th>";
echo  "</tr>";
echo "<form method=post>";
//-----------------------------------------------------------------------------

//Populate Table
//-----------------------------------------------------------------------------
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

		echo  "</tr>";		
	}
}
echo "<th colspan = 2>" ;
echo "SubTotal</th>";
echo "<th colspan = 2>" ;
echo "R$totalCost</th>";
echo "<th><button type=submit name=Place_Order id = Place_Order value=Place_Order>Place Order</button></th>";
echo"</table>";	

echo"<h3>Your Purchase History</h3>";//Uses loops to load all the users ordered history

$orderID = array();
$orderID = $Cart->getUserOrderHistory();
if(empty($orderID)){
	
}else{
//Order History	
$totalHistoryCost = 0; 
	for ($i = 0; $i < count($orderID); $i++) {
		
		$totalCost = 0; //resets the total cost
	$HistoryOrder = array();	
		echo"<h3>reference number $orderID[$i]<h3>";
	$HistoryOrder = $Cart->getUserOrderItemHistory($orderID[$i]);
	//-----------------------------------------------------------------------------
echo "<table style=width:100% class =blueTable>";
echo  "<tr>";
echo "<th>Image</th>";
echo "<th>DESCRIPTION</th>";
echo "<th>Price Each</th>" ;
echo "<th># in Cart</th>";
echo "<th>Sub Total</th>";
echo  "</tr>";
echo "<form method=post>";
//-----------------------------------------------------------------------------

	for ($j = 0; $j < count($HistoryOrder); $j++) {
	 //gets all the items in the order
	
$shoppingCart = $Cart->getProduct($HistoryOrder[$j][0]); //loads all the inventory items 

 

//Populate Table
//-----------------------------------------------------------------------------

if (mysqli_num_rows($shoppingCart) > 0) {
			// output data of each row
	
while($row = mysqli_fetch_assoc($shoppingCart)) {
	


if($HistoryOrder[$j][0]===$row['ITEMID']){	
$subTotal = $HistoryOrder[$j][1]*$row['SELL_PRICE'];
$totalCost = $totalCost + $subTotal; 
$totalHistoryCost = $totalHistoryCost + $totalCost;				
echo  "<tr>";
echo "<th><img src=Images/$row[ITEMID].jpg alt=$row[DESCRIPTION] height=80 width=80></th>";
echo "<th>$row[DESCRIPTION]</th>";
echo "<th>R$row[SELL_PRICE]</th>" ;
$amount = $HistoryOrder[$j][1];
echo "<th>$amount</th>";
echo "<th>R$subTotal</th>" ;
echo  "</tr>";
		
}

}
}
}
echo "<th colspan = 3>" ;
echo "SubTotal</th>";
echo "<th colspan = 2>" ;
echo "R$totalCost</th>";
echo"</table>";	
}

//Create Total Price Table
echo "</br>";
echo "<table style=width:100% class =blueTable>";
echo  "<tr>";
echo "<th>Total History Price</th>";
echo "<form method=post>";
echo  "</tr>";
echo "<th>R$totalHistoryCost</th>";

}
}
?>
	</body>
</HTML>