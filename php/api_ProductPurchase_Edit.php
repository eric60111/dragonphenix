<?php

	include 'lib_link2DB.php';
	include 'lib_BarcodePrint.php';
	/** Edit Product Purchase**/
	$ProductPurchaseID = !empty($_POST['ProductPurchaseID']) ? $_POST['ProductPurchaseID'] : null; 
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : 0; 
	$VerifyState = !empty($_POST['VerifyState']) ? $_POST['VerifyState'] : null;
	$VerifyAction = !empty($_POST['VerifyAction']) ? $_POST['VerifyAction'] : 0;
	$State = !empty($_POST['State']) ? $_POST['State'] : null;
	$ProductPurchaseDetail = !empty($_POST['ProductPurchaseDetail']) ? $_POST['ProductPurchaseDetail'] : 'undefined';
	
	if($CompanyID != 0) {
		$sql = 
		"UPDATE ProductPurchases SET ".
		"CompanyID = '$CompanyID' ".
		"WHERE ProductPurchaseID = '$ProductPurchaseID'";
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
	}
	if($State == 0) {
		$sql = 
		"UPDATE ProductPurchases SET ".
		"State = '0' ".
		"WHERE ProductPurchaseID = '$ProductPurchaseID'";
		
		mysqli_query($lib_link, stripslashes($sql));
		LogSql($lib_link, $sql);
		$sql = 
		"UPDATE ProductPurchaseDetails SET ".
		"State = '0' ".
		"WHERE ProductPurchaseID = '$ProductPurchaseID'";
		
		mysqli_query($lib_link, stripslashes($sql));	
		LogSql($lib_link, $sql);
		if($VerifyState == 1) {
			$sql = 
			"SELECT P.ProductID, PPD.ActualQuantity, P.Inventory ".
			"FROM ProductPurchaseDetails PPD, Products P ".
			"WHERE PPD.ProductPurchaseID = '$ProductPurchaseID' AND ".
			"PPD.ProductID = P.ProductID ".
			"ORDER BY P.ProductID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$sum = $value['Inventory'] - $value['ActualQuantity'];
				$ProductID = $value['ProductID'];
				
				$editsql = 
				"UPDATE Products SET ".
				"Inventory = '$sum'".
				"WHERE ProductID = '$ProductID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
			}
		}
	} else if ($VerifyAction == 1) {
		$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
		$VerifyDate = date("Y-m-d"); 
		$IsPrintBarcode = !empty($_POST['IsPrintBarcode']) ? $_POST['IsPrintBarcode'] : false; 
		
		$sql = "SELECT VerifyState FROM ProductPurchases WHERE ProductPurchaseID = '$ProductPurchaseID'";
		$res = mysqli_query($lib_link, stripslashes($sql));
		$nowverifystate = mysqli_fetch_assoc($res)['VerifyState'];
		
		if($nowverifystate == 0) {
			if($ProductPurchaseDetail != 'undefined') {
				$data = json_decode($ProductPurchaseDetail);
				foreach($data as $value) {
					if($value != null) {
						$ProductPurchaseDetailID = $value->{'ProductPurchaseDetailID'};
						$ActualQuantity = $value->{'ActualQuantity'};
						$UnitPrice = $value->{'UnitPrice'};
						$editsql = 
						"UPDATE ProductPurchaseDetails SET ".
						"ActualQuantity = '$ActualQuantity', ".
						"UnitPrice = '$UnitPrice' ".
						"WHERE ProductPurchaseDetailID = '$ProductPurchaseDetailID'";
						mysqli_query($lib_link, stripslashes($editsql));
						LogSql($lib_link, $editsql);
					}
				}
				
			}
			
			
			$sql = 
			"UPDATE ProductPurchases SET ".
			"VerifyState = '1', ".
			"VerifyEmployeeID = '$EmployeeID', ".
			"VerifyDate = '$VerifyDate'".
			"WHERE ProductPurchaseID = '$ProductPurchaseID'";
			
			mysqli_query($lib_link, stripslashes($sql));	
			LogSql($lib_link, $sql);
			$sql = 
			"SELECT P.ProductID, PPD.ActualQuantity, P.Inventory, P.Name ".
			"FROM ProductPurchaseDetails PPD, Products P ".
			"WHERE PPD.ProductPurchaseID = '$ProductPurchaseID' AND ".
			"PPD.ProductID = P.ProductID AND ".
			"PPD.State = 1 ".
			"ORDER BY P.ProductID ASC ;";
			
			$res = mysqli_query($lib_link, stripslashes($sql));
			while($result = mysqli_fetch_assoc($res) ) {
				$resp[] = $result;
			}
			
			foreach ($resp as $value) {
				$Inventory = $value['Inventory'];
				$ActualQuantity = $value['ActualQuantity'];
				$sum = $Inventory + $ActualQuantity;
				$ProductID = $value['ProductID'];
				$Name = $value['Name'];
				
				$editsql = 
				"UPDATE Products SET ".
				"Inventory = '$sum'".
				"WHERE ProductID = '$ProductID'";
				mysqli_query($lib_link, stripslashes($editsql));
				LogSql($lib_link, $editsql);
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
				if($IsPrintBarcode) {
					$Cmd =
					'CLS&DIRECTION 1&'.
					'SIZE 32 mm, 25 mm&'.
					'GAP 2mm, 0&'.
					'TEXT 30,5,"TST24.BF2",0,1,1,"'.$Name.'"&'.
					'BARCODE 30,40,"128",100,1,0,2,2,"'.date("ymd").'3'.sprintf("%03d", $ProductID).'"&'.
					'PRINT '.$ActualQuantity;
					Printer_Cmd('dragonphenix', $Cmd);
				}
			}
		}
	}
	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductPurchaseID' => $ProductPurchaseID
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
	

?>