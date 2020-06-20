<?php
class ShoppingCart{
 private $TableName = "";
 private $Count = 0;
 private $DBConnect;
 private $Orders = array();
 private $guestUserCart =array();
 private $OrderTable = array();
 private $ProdID="";
 private $ShoppingCart;
 private $inventory;
 private $userInfo = array();

 public function __construct()
 {
	include'DatabaseConnection.php';
	$this->DBConnect = $Connection;
		
}
public function addProductQuantity($itemID){

		 $quantity;
		 $sql  = "Select QUANTITY from tbl_item where ITEMID = $itemID";
		 $result  = mysqli_query($this->DBConnect,$sql);
		 if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)){
				$quantity = $row["QUANTITY"];
				
			}
		 $quantity++;
		 
		 $sql  = "UPDATE tbl_item SET QUANTITY = $quantity where ITEMID = $itemID";
		 mysqli_query($this->DBConnect,$sql);
		 
		 }
	
}
 	
	public function removeProductQuantity($itemID){

		 $quantity;
		 $sql  = "Select QUANTITY from tbl_item where ITEMID = $itemID";
		 $result  = mysqli_query($this->DBConnect,$sql);
		 if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)){
				$quantity = $row["QUANTITY"];
				
			}
			if($quantity >0){
				$quantity--;
			}
		 $sql  = "UPDATE tbl_item SET QUANTITY = $quantity where ITEMID = $itemID";
		 mysqli_query($this->DBConnect,$sql);
		 
		 }
	
}

	
public function getProductList()
	{	
		$sql = "SELECT * From TBL_ITEM";
		$result  = mysqli_query($this->DBConnect,$sql);
		
	
		return($result);

	}
	
public function getProduct($itemID)
	{
		$sql = "SELECT * From TBL_ITEM where ITEMID = $itemID";
		$result  = mysqli_query($this->DBConnect,$sql);
		return($result);
	}
	
	public function createNewUser($FNAME, $LNAME, $myEmail, $password)
	{
			
		$sql = "INSERT INTO tbl_customer (FNAME,LNAME,EMAIL,PASSWORD)
				VALUES('$FNAME','$LNAME',
				'$myEmail','$password');";
	
		mysqli_query($this->DBConnect,$sql);//Gets the primary Key from the insert 
		
		
	$sql = "SELECT ID,FNAME,LNAME,EMAIL,PASSWORD FROM tbl_customer WHERE EMAIL = '$myEmail' and PASSWORD = '$password'";
		
		$result  = mysqli_query($this->DBConnect,$sql);
	 
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				
				$this->userInfo[0] = $row["ID"];
				$this->userInfo[1] = $row["FNAME"];
				$this->userInfo[2] = $row["LNAME"];
				$this->userInfo[3] = $row["EMAIL"];
				
				
				
			}
		}
	}
		
	public function isUserLogIn()
	{
		
	
		$info = array();
		if(empty($this->userInfo) === false){
			
			$info[0] = $this->userInfo[1];
			$info[1] = $this->userInfo[2];
		
		}
		return($info);
	}
	
	public function isUserAdmin()
	{
		$ID = $this->userInfo[0];
		
	$sql = "SELECT ID,isAdmin FROM tbl_customer WHERE ID = '$ID'";
		
		
		$result  = mysqli_query($this->DBConnect,$sql);
	 
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				
				$isAdmin = $row["isAdmin"];
				
				if($isAdmin == 1){
					
					return(true);
				}else{
					
					return(false);
				}
				
			}
			
		}
		return(false);
	}
	
	public function setLogInBoolean($myEmail, $myPassword)
	{
		
		$sql = "SELECT ID,FNAME,LNAME,EMAIL,PASSWORD FROM tbl_customer WHERE EMAIL = '$myEmail' and PASSWORD = '$myPassword'";
		
		$result  = mysqli_query($this->DBConnect,$sql);
	 
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				
				$this->userInfo[0] = $row["ID"];
				$this->userInfo[1] = $row["FNAME"];
				$this->userInfo[2] = $row["LNAME"];
				$this->userInfo[3] = $row["EMAIL"];
				
				
			}
			return(true);
		}else{
		return(false);
		}
	
	}
	
	public function setLogIn($myEmail, $myPassword)
	{
		
		$sql = "SELECT ID,FNAME,LNAME,EMAIL,PASSWORD FROM tbl_customer WHERE EMAIL = '$myEmail' and PASSWORD = '$myPassword'";
		
		$result  = mysqli_query($this->DBConnect,$sql);
	 
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				
				$this->userInfo[0] = $row["ID"];
				$this->userInfo[1] = $row["FNAME"];
				$this->userInfo[2] = $row["LNAME"];
				$this->userInfo[3] = $row["EMAIL"];
				
				
			}
		}
	
	}
	
	function getCartSize(){
	$cart =	$this->guestUserCart;
	return(count($cart));
	}
	
 function getGuestUserCartNumber($itemID)
	{
		$amount = 0;
		
		if(empty($this->guestUserCart))
		{
			return($amount);
		}else{
	
		for($i = 0; $i < count($this->guestUserCart); $i++) {
		
			
		if($this->guestUserCart[$i][0] == $itemID){ //If the item already exists, adds another quantity in
	
		$amount = $this->guestUserCart[$i][1];		
		return($amount);
		}
		}
	
		
		}
			
	}
	
	public function ClearUserCart(){
		$this->guestUserCart = array();
	}
	
public function RemoveItemFromGuestCart($itemID)
	{
	if(empty($this->guestUserCart)) //dont do anything if the shopping cart is empty
		{
		}
		else{
			$size = count($this->guestUserCart);		
			for ($i = 0; $i < $size; $i++) {
				if($this->guestUserCart[$i][0] === $itemID && $this->guestUserCart[$i][1]>0){ //If the item already exists, Removes one from it
					$guestQuantity = $this->guestUserCart[$i][1];
					$guestQuantity--;
					$this->guestUserCart[$i][1] =$guestQuantity;
				}
			}
			
		}
	}
	
public function setGuestUserCart($itemID){
		
		
		if(empty($this->guestUserCart))
		{
		$this->guestUserCart[0][0] = $itemID;
		$this->guestUserCart[0][1] = 1;
		}
		else{
				
		$found = false;
		$size = count($this->guestUserCart);
		
		for ($i = 0; $i < $size; $i++) {
			if($this->guestUserCart[$i][0] === $itemID){ //If the item already exists, adds another quantity in

				$guestQuantity = $this->guestUserCart[$i][1];
				$guestQuantity++;
				$this->guestUserCart[$i][1] =$guestQuantity;
				$found = true;
			}
		}
		if ($found === false){ //adds a new elemint to the quest array
			
			
			$this->guestUserCart[$size][0] = $itemID;
			$this->guestUserCart[$size][1] = 1;
		//echo"</br> Cart size: ".count($this->guestUserCart);
		}
		}
}
	
 function getShopList(){ //Dont use this; Dont Need It
		$retval = FALSE;
		$subtotal = 0;
	if(count($this->inventory) > 0)
	{
		echo"<table width= '100%'>\n";
		echo"<tr><th>Product</th><th>Description</th>"."<th>Price Each</th><th># in Cart</th>" ."<th>Total Price</th><th>&nbsp;</th></tr>\n";
	foreach($this->inventory as $ID => $Info)
	{
		if ($this->inventory[$ID] !== 0)
		{
		echo "<tr><td>".htmlentities($Info['name'])."</td>\n";
		echo "<td>".htmlentities($Info['description'])."</td>\n";
		printf("<td class= 'currency'>R%.2f</td>\n", $Info['price']);
		echo "<td class= 'currency'>".$this->ShoppingCart[$ID]."</td>\n";
		printf("<td class= 'currency'>R%.2f</td>\n", $Info['price'] * $this->ShoppingCart[$ID]);
		echo "<td><a href='" .$_SERVER['SCRIPT_NAME'] ."?PHPSESSID=" . session_id() ."&ItemToAdd=$ID'>Add " ." Item</a><br />\n";
		
		echo "<a href='" . $_SERVER['SCRIPT_NAME']."?PHPSESSID=" . session_id() ."&ItemToRemove=$ID'>Remove " ." Item</a></td>\n";
	$subtotal += ($Info['price'] * $this->ShoppingCart[$ID]);
	}
		echo "<tr><td colspan= '4'>Subtotal</td>\n";
		printf("<td class= 'currency'>$%.2f</td>\n", $subtotal);
		
		echo"</table>";
	$retval = TRUE; 
	}
		return($retval);
	}
	}
	 
 function getCustomerCart(){
	
		$retval = FALSE;
		$subtotal = 0;
		
		
		$sql = "SELECT * From TBL_ITEM";
		$result  = mysqli_query($this->DBConnect,$sql);
		
	
		return($result);
	}
	
public function getUserOrderHistory(){
	$orderID = array();
	$userID = $this->userInfo[0];
	$sql = "SELECT Order_ID FROM `tbl_order` WHERE Customer_ID = '$userID'";
	$result  = mysqli_query($this->DBConnect,$sql);
	$i = 0;
		while($row = mysqli_fetch_assoc($result)) {
			$orderID[$i] = $row['Order_ID'];
			$i++;
		}
	return $orderID;
		
}
	 
	 public function getUserOrderItemHistory($OrderID){
	
	$HistoryOrder = array();//loads the orders history in this array
	$sql  = "SELECT ITEMID, Amount from tbl_orderitem where Order_ID = $OrderID";
	$result  = mysqli_query($this->DBConnect,$sql);
	$i = 0;
	while($row = mysqli_fetch_assoc($result)) {
	$HistoryOrder[$i][0]=$row['ITEMID'];
	$HistoryOrder[$i][1]=$row['Amount'];
	//echo "SELECT ITEMID, Amount from tbl_orderitem where Order_ID = $OrderID";
	//echo $OrderID." While Loop Length ".$i;
	$i++;

	
	}
	return($HistoryOrder);
	 }
	 
	 public function InsertItemOrderToDatabase(){
		$usersCart = $this->guestUserCart;
		$DataOrderID = $this->InsertOrderToDatabase();
		for($i = 0; $i < count($usersCart); $i++) {
		$ItemID = $usersCart[$i][0];	
		$amount = $usersCart[$i][1];
		
	$sql  = "INSERT INTO tbl_orderitem (Order_ID, ITEMID, Amount) VALUES ($DataOrderID,$ItemID,$amount)";
	$result  = mysqli_query($this->DBConnect,$sql);
		}
	return($DataOrderID);//returns the order ID for the user to use as a referance
	
	}
	 
	 public function InsertOrderToDatabase(){
		 $userID = $this->userInfo[0];
		 $sql  = "INSERT INTO tbl_order (Customer_ID) 
		 VALUES ($userID)";
	$result  = mysqli_query($this->DBConnect,$sql);
	
	$OrderID =  mysqli_insert_id($this->DBConnect); //gets the primary key of the inserted statment
	 return($OrderID);
	 }
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 function setDatabase($Database,$TB)
	{
		$this->DBName = $Database;
		$this->TableName=$TB;
	}
	 function setCount($cnt)
	{
		$this->Count+=$cnt;
	}
	 
	 function getDatabase()
	{
		return $this->DBName;
	}
	 function getCount()
	{
		return $this->Count;
	}
	 function __wakeup()
	{
		//echo "<p> I am in wakeup CONSTRUCT </p>";
	include_once("DatabaseConnection.php");
	$this->DBConnect = $Connection;
	}

	function __destruct()
	{
		$this->DBConnect->close(); //Close handles of open database connections
	}
	/* function __sleep()
	{
		echo "I am inside Sleep function";
		echo "I assume that I serialised now";
		$SerialVars = array("Balance");
		return $SerialVars;
	}
 */
  function processUserInput() 
 {  
	if(!empty($_GET['DispCart']))   
	 $this->getShopList();  
	if(!empty($_GET['ItemToAdd']))   
	 $this->addItem();  
	if(!empty($_GET['AddOne']))   
	 $this->addOne();  
	if(!empty($_GET['ItemToRemove']))   
	 $this->removeItem();  
	if(!empty($_GET['EmptyCart']))   
	 $this->emptyCart();  
	if(!empty($_GET['RemoveAll']))   
	 $this->removeAll(); 
	} 
 
 function addItem() 
{		
	$ProdID = $_GET['ItemToAdd'];  
	if(array_key_exists($ProdID, $this->ShoppingCart)) {       
		$this->ShoppingCart[$ProdID] += 1; 
	}  
		else     
			echo"<h2>Item already exists</h2>"; 
} 
/*private function emptyCart() 
	{     
	foreach ($this->ShoppingCart as $key => $value)$this->ShoppingCart[$key] = 0; 
	} 
	*/

}

?>
