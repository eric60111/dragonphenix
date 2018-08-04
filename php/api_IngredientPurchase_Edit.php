<?php

	include 'lib_link2DB.php';
	include 'lib_BarcodePrint.php';
	/** Edit Ingredient Purchase**/
	$IngredientPurchaseID = !empty($_POST['IngredientPurchaseID']) ? $_POST['IngredientPurchaseID'] : null; 
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : 0; 
	$VerifyState = !empty($_POST['VerifyState']) ? $_POST['VerifyState'] : null;
	$VerifyAction = !empty($_POST['VerifyAction']) ? $_POST['VerifyAction'] : 0;
	$State = !empty($_POST['State']) ? $_POST['State'] : null;
	$IngredientPurchaseDetail = !empty($_POST['IngredientPurchaseDetail']) ? $_POST['IngredientPurchaseDetail'] : 'undefined';

	if($CompanyID != 0) {
		$sql = 
		"UPDATE IngredientPurchases SET ".
		"CompanyID = '$CompanyID' ".
		"WHERE IngredientPurchaseID = '$IngredientPurchaseID'";
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
	}
	if($State == 0) {
		$sql = 
		"UPDATE IngredientPurchases SET ".
		"State = '0' ".
		"WHERE IngredientPurchaseID = '$IngredientPurchaseID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE IngredientPurchaseDetails SET ".
		"State = '0' ".
		"WHERE IngredientPurchaseID = '$IngredientPurchaseID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		if($VerifyState == 1) {
			$sql = 
			"SELECT I.IngredientID, IPD.ActualQuantity, I.Inventory ".
			"FROM IngredientPurchaseDetails IPD, Ingredients I ".
			"WHERE IPD.IngredientPurchaseID = '$IngredientPurchaseID' AND ".
			"IPD.IngredientID = I.IngredientID ".
			"ORDER BY I.IngredientID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$sum = $value['Inventory'] - $value['ActualQuantity'];
				$IngredientID = $value['IngredientID'];
				
				$editsql = 
				"UPDATE Ingredients SET ".
				"Inventory = '$sum'".
				"WHERE IngredientID = '$IngredientID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
			}
		}
	} else if ($VerifyAction == 1) {
		$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
		$VerifyDate = date("Y-m-d"); 
		$IsPrintBarcode = !empty($_POST['IsPrintBarcode']) ? $_POST['IsPrintBarcode'] : false; 
		
		$sql = "SELECT VerifyState FROM IngredientPurchases WHERE IngredientPurchaseID = '$IngredientPurchaseID'";
		$res = mysqli_query($lib_link, stripslashes($sql));
		$nowverifystate = mysqli_fetch_assoc($res)['VerifyState'];
		
		if($nowverifystate == 0) {
			if($IngredientPurchaseDetail != 'undefined') {
				$data = json_decode($IngredientPurchaseDetail);
				foreach($data as $value) {
					if($value != null) {
						$IngredientPurchaseDetailID = $value->{'IngredientPurchaseDetailID'};
						$ActualQuantity = $value->{'ActualQuantity'};
						$UnitPrice = $value->{'UnitPrice'};
						$editsql = 
						"UPDATE IngredientPurchaseDetails SET ".
						"ActualQuantity = '$ActualQuantity', ".
						"UnitPrice = '$UnitPrice' ".
						"WHERE IngredientPurchaseDetailID = '$IngredientPurchaseDetailID'";
						mysqli_query($lib_link, stripslashes($editsql));
						LogSql($lib_link, $editsql);
					}
				}
				
			}
			
			
			$sql = 
			"UPDATE IngredientPurchases SET ".
			"VerifyState = '1', ".
			"VerifyEmployeeID = '$EmployeeID', ".
			"VerifyDate = '$VerifyDate'".
			"WHERE IngredientPurchaseID = '$IngredientPurchaseID'";
			
			mysqli_query($lib_link, stripslashes($sql));	
			LogSql($lib_link, $sql);
			$sql = 
			"SELECT I.IngredientID, IPD.ActualQuantity, I.Inventory, I.Name ".
			"FROM IngredientPurchaseDetails IPD, Ingredients I ".
			"WHERE IPD.IngredientPurchaseID = '$IngredientPurchaseID' AND ".
			"IPD.IngredientID = I.IngredientID AND ".
			"IPD.State = 1 ".
			"ORDER BY I.IngredientID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$Inventory = $value['Inventory'];
				$ActualQuantity = $value['ActualQuantity'];
				$sum = $Inventory + $ActualQuantity;
				$IngredientID = $value['IngredientID'];
				$Name = $value['Name'];
				
				$editsql = 
				"UPDATE Ingredients SET ".
				"Inventory = '$sum'".
				"WHERE IngredientID = '$IngredientID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
				$Cost = 0;	
				$calCostSql =
				"SELECT IngredientPurchaseDetailID, ActualQuantity, UnitPrice ".
				"FROM IngredientPurchaseDetails ".
				"WHERE IngredientID = '$IngredientID' AND ".
				"State = 1 ".
				"ORDER BY IngredientPurchaseDetailID DESC ;";
				
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
				"UPDATE Ingredients SET ".
				"Cost = '$Cost'".
				"WHERE IngredientID = '$IngredientID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
				if($IsPrintBarcode) {
					$Cmd =
					'CLS&DIRECTION 1&'.
					'SIZE 32 mm, 25 mm&'.
					'GAP 2mm, 0&'.
					'TEXT 30,5,"TST24.BF2",0,1,1,"'.$Name.'"&'.
					'BARCODE 30,40,"128",100,1,0,2,2,"'.date("ymd").'1'.sprintf("%03d", $IngredientID).'"&'.
					'PRINT '.$ActualQuantity;
					Printer_Cmd('dragonphenix', $Cmd);
				}
			}
		}
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientPurchaseID' => $IngredientPurchaseID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
	
?>