<?php

	include 'lib_link2DB.php';
	
	/** Edit Purchase Detail **/
	$ProductPurchaseDetailID = !empty($_POST['ProductPurchaseDetailID']) ? $_POST['ProductPurchaseDetailID'] : null; 
	$UnitPrice = !empty($_POST['UnitPrice']) ? $_POST['UnitPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
	$ActualQuantity = !empty($_POST['ActualQuantity']) ? $_POST['ActualQuantity'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null; 
	
	
	$sql = 
	"UPDATE ProductPurchaseDetails SET ".
	"UnitPrice = '$UnitPrice' ".
	"WHERE ProductPurchaseDetailID = '$ProductPurchaseDetailID'";
	mysqli_query($lib_link, stripslashes($sql));	
	LogSql($lib_link, $sql);
	
	$res = mysqli_query($lib_link, stripslashes("SELECT ProductID FROM ProductPurchaseDetails WHERE ProductPurchaseDetailID = '$ProductPurchaseDetailID'"));
	$ProductID = mysqli_fetch_row($res)[0];
	
	$res = mysqli_query($lib_link, stripslashes("SELECT Inventory FROM Products WHERE ProductID = '$ProductID'"));
	$sum = mysqli_fetch_row($res)[0];
	
	$Cost = 0;	
	$calCostSql =
	"SELECT ProductPurchaseDetailID, ActualQuantity, UnitPrice ".
	"FROM ProductPurchaseDetails ".
	"WHERE ProductID = '$ProductID' AND ".
	"State = 1 ".
	"ORDER BY ProductPurchaseDetailID DESC ;";
	
	$calCostRes = mysqli_query($lib_link, stripslashes($calCostSql));
	while($calCostResult = mysqli_fetch_assoc($calCostRes) ) {
		$Quantity =  $calCostResult['ActualQuantity'];
		$UnitPrice =  $calCostResult['UnitPrice'];
		if($sum - $Quantity > 0) {
			$Cost += ($Quantity * $UnitPrice);
			$sum -= $Quantity;
		} else {
			$Cost += ($sum * $UnitPrice);
			break;
		}
	}
	
	$editsql = 
	"UPDATE Products SET ".
	"Cost = '$Cost'".
	"WHERE ProductID = '$ProductID'";
	mysqli_query($lib_link, stripslashes($editsql));
	LogSql($lib_link, $editsql);
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductPurchaseDetailID' => $ProductPurchaseDetailID
    );
	
	print(json_encode($resp));
?>