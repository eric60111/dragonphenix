<?php

	include 'lib_link2DB.php';
	
	/** Add Material Purchase Detail **/
	$MaterialPurchaseID = !empty($_POST['MaterialPurchaseID']) ? $_POST['MaterialPurchaseID'] : null;
	$MaterialID = !empty($_POST['MaterialID']) ? $_POST['MaterialID'] : null; 
	$UnitPrice = !empty($_POST['UnitPrice']) ? $_POST['UnitPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
		
	$sql = "INSERT INTO MaterialPurchaseDetails VALUES ";
	$sql .= "(NULL, '$MaterialPurchaseID', '$MaterialID', '$UnitPrice', '$Quantity', '$Quantity', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$MaterialPurchaseDetailID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	$sql = 
	"UPDATE Materials SET ".
	"UnitPrice = '$UnitPrice' ".
	"WHERE MaterialID = '$MaterialID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialPurchaseDetailID' => $MaterialPurchaseDetailID
    );
	
	print(json_encode($resp));
?>