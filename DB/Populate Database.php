<?php include_once'DBConn.php';
	
	
	//Creating new table User
	//************************************************************************************************
	
//Drops all the tables
	$sql = "DROP TABLE tbl_orderitem;";
	mysqli_query($Connection,$sql);
	$sql = "DROP TABLE tbl_order;";
	mysqli_query($Connection,$sql);
	$sql = "DROP TABLE tbl_item;";
	mysqli_query($Connection,$sql);
	$sql = "DROP TABLE tbl_customer;";
	
	//commits the quarries to the database
	mysqli_query($Connection,$sql);
	//Creating the tables to be inserted in the database
	$sql = "CREATE TABLE tbl_customer( 
			ID int AUTO_INCREMENT PRIMARY KEY,
			FNAME VARCHAR(255) NOT NULL,
			LNAME VARCHAR(255) NOT NULL,
			EMAIL VARCHAR(255) NOT NULL,
			PASSWORD VARCHAR(255) NOT NULL,
			isAdmin Boolean NOT NULL)
			ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	mysqli_query($Connection,$sql);
	
	
	
	
	
	$sql = "CREATE TABLE IF NOT EXISTS tbl_item (
	ITEMID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	DESCRIPTION varchar(255) NOT NULL,
	COST_PRICE decimal(15,2) NOT NULL,
	QUANTITY INT NOT NULL,
	SELL_PRICE decimal(15,2) NOT NULL) 
	ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	mysqli_query($Connection,$sql);
	
	
	
	
	
	
	$sql = "CREATE TABLE IF NOT EXISTS tbl_order (
			Order_ID INT PRIMARY KEY AUTO_INCREMENT,
			Customer_ID INT NOT NULL,
			CONSTRAINT FK_tbl_order FOREIGN KEY (Customer_ID) REFERENCES tbl_customer(ID)) 
			ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	
	mysqli_query($Connection,$sql);
	
	
	$sql = "CREATE TABLE IF NOT EXISTS tbl_orderitem (
			Order_ID INT NOT NULL,
			ITEMID INT NOT NULL,
			Amount INT NOT NULL,
			CONSTRAINT FK_tbl_order1 FOREIGN KEY (Order_ID) REFERENCES tbl_order(Order_ID), 
			CONSTRAINT FK_tbl_item1 FOREIGN KEY (ITEMID) REFERENCES tbl_item(ITEMID))  
			ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	mysqli_query($Connection,$sql);
	
	
	//End of createing tables
	
	// Open the file and load to array
	
	$lines = file('Item.txt', FILE_IGNORE_NEW_LINES);
	foreach($lines as $sread){
		//Splits the line into varibles
		$VaribleArray = explode(',', $sread);	
		$sql = "INSERT INTO TBL_ITEM (DESCRIPTION,COST_PRICE,QUANTITY,SELL_PRICE)
				VALUES('$VaribleArray[0]','$VaribleArray[1]',
				'$VaribleArray[2]','$VaribleArray[3]');";
		
		mysqli_query($Connection,$sql);
	}
	
	
	$lines = file('UserData.txt', FILE_IGNORE_NEW_LINES);
	foreach($lines as $sread){
		//Splits the line into varibles
		$VaribleArray = explode(',', $sread);	
		$sql = "INSERT INTO tbl_customer (FNAME,LNAME,EMAIL,PASSWORD,isAdmin)
				VALUES('$VaribleArray[0]','$VaribleArray[1]',
				'$VaribleArray[2]','$VaribleArray[3]','$VaribleArray[4]');";
		echo $sql."</br>";
		mysqli_query($Connection,$sql);
	}
	
	
	$lines = file('OrderID.txt', FILE_IGNORE_NEW_LINES);
	foreach($lines as $sread){
		//Splits the line into varibles
		$VaribleArray = explode(',', $sread);	
		
		$sql = "INSERT INTO tbl_order (Customer_ID) VALUES('$VaribleArray[0]');";
		echo $sql."</br>";
		mysqli_query($Connection,$sql);
	}
	
	$lines = file('OrderItem.txt', FILE_IGNORE_NEW_LINES);
	foreach($lines as $sread){
		//Splits the line into varibles
		$VaribleArray = explode(',', $sread);	
		
		$sql = "INSERT INTO tbl_orderItem (`Order_ID`, `ITEMID`, `Amount`)
				VALUES('$VaribleArray[0]','$VaribleArray[1]',
				'$VaribleArray[2]');";
		
		echo $sql."</br>"; 
		mysqli_query($Connection,$sql);
	}
	

?>

