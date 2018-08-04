<?php

	include 'lib_link2DB.php';
	
	/** Edit Inventory Detail **/
	$MaterialInventoryDetailID = !empty($_POST['MaterialInventoryDetailID']) ? $_POST['MaterialInventoryDetailID'] : null; 
	$Inventory = !empty($_POST['Inventory']) ? $_POST['Inventory'] : null; 
	
	$res = mysqli_query($lib_link, stripslashes("SELECT Inventory, Difference, MaterialID FROM MaterialInventoryDetails WHERE MaterialInventoryDetailID = '$MaterialInventoryDetailID'"));
	$result = mysqli_fetch_row($res);
	$InventoryOld = $result[0];
	$Difference = $result[1] + $Inventory - $InventoryOld;
	$MaterialID = $result[2];
	
	
	$sql = 
	"UPDATE MaterialInventoryDetails SET ".
	"Inventory = '$Inventory', ".
	"Difference = '$Difference' ".
	"WHERE MaterialInventoryDetailID = '$MaterialInventoryDetailID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	$sql = 
	"UPDATE Materials SET ".
	"Inventory = '$Inventory' ".
	"WHERE MaterialID = '$MaterialID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	$sum = $Inventory;
	
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
        'MaterialInventoryDetailID' => $MaterialInventoryDetailID
    );
	
	print(json_encode($resp));
?>