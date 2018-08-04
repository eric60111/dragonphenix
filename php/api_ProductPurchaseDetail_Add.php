<?php

	include 'lib_link2DB.php';
	
	/** Add Product Purchase Detail **/
	$ProductPurchaseID = !empty($_POST['ProductPurchaseID']) ? $_POST['ProductPurchaseID'] : null;
	$ProductID = !empty($_POST['ProductID']) ? $_POST['ProductID'] : null; 
	$UnitPrice = !empty($_POST['UnitPrice']) ? $_POST['UnitPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
		
	$sql = "INSERT INTO ProductPurchaseDetails VALUES ";
	$sql .= "(NULL, '$ProductPurchaseID', '$ProductID', '$UnitPrice', '$Quantity', '$Quantity', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$ProductPurchaseDetailID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	$sql = 
	"UPDATE Products SET ".
	"UnitPrice = '$UnitPrice' ".
	"WHERE ProductID = '$ProductID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductPurchaseDetailID' => $ProductPurchaseDetailID
    );
	
	print(json_encode($resp));
?>