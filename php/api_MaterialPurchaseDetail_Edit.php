<?php

	include 'lib_link2DB.php';
	
	/** Edit Purchase Detail **/
	$MaterialPurchaseDetailID = !empty($_POST['MaterialPurchaseDetailID']) ? $_POST['MaterialPurchaseDetailID'] : null; 
	$UnitPrice = !empty($_POST['UnitPrice']) ? $_POST['UnitPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
	$ActualQuantity = !empty($_POST['ActualQuantity']) ? $_POST['ActualQuantity'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null; 
	
	
	$sql = 
	"UPDATE MaterialPurchaseDetails SET ".
	"UnitPrice = '$UnitPrice' ".
	"WHERE MaterialPurchaseDetailID = '$MaterialPurchaseDetailID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	$res = mysqli_query($lib_link, stripslashes("SELECT MaterialID FROM MaterialPurchaseDetails WHERE MaterialPurchaseDetailID = '$MaterialPurchaseDetailID'"));
	$MaterialID = mysqli_fetch_row($res)[0];
	
		
	$res = mysqli_query($lib_link, stripslashes("SELECT Inventory FROM Materials WHERE MaterialID = '$MaterialID'"));
	$sum = mysqli_fetch_row($res)[0];
	
	$Cost = 0;	
	$calCostSql =
	"SELECT MaterialPurchaseDetailID, ActualQuantity, UnitPrice ".
	"FROM MaterialPurchaseDetails ".
	"WHERE MaterialID = '$MaterialID' AND ".
	"State = 1 ".
	"ORDER BY MaterialPurchaseDetailID DESC ;";
	
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
	"UPDATE Materials SET ".
	"Cost = '$Cost'".
	"WHERE MaterialID = '$MaterialID'";
	mysqli_query($lib_link, stripslashes($editsql));
	LogSql($lib_link, $editsql);
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialPurchaseDetailID' => $MaterialPurchaseDetailID
    );
	
	print(json_encode($resp));
?>