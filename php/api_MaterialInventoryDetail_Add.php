<?php

	include 'lib_link2DB.php';
	
	/** Add Material Inventory Detail **/
	$MaterialInventoryID = !empty($_POST['MaterialInventoryID']) ? $_POST['MaterialInventoryID'] : null;
	$MaterialID = !empty($_POST['MaterialID']) ? $_POST['MaterialID'] : null; 
	$Inventory = !empty($_POST['Inventory']) ? $_POST['Inventory'] : null; 
	
	$res = mysqli_query($lib_link, stripslashes("SELECT Inventory FROM Materials WHERE MaterialID = '$MaterialID'"));
	$Difference = $Inventory - mysqli_fetch_row($res)[0];
		
	$sql = "INSERT INTO MaterialInventoryDetails VALUES ";
	$sql .= "(NULL, '$MaterialInventoryID', '$MaterialID', '$Inventory', '$Difference', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$MaterialInventoryDetailID = mysqli_insert_id($lib_link);
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