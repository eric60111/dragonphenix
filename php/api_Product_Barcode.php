<?php
	include 'lib_BarcodePrint.php';
	$ProductID = !empty($_POST['ProductID']) ? $_POST['ProductID'] : "%"; 
	$Name = !empty($_POST['Name']) ? $_POST['Name'] : "%";
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : "1";

	$Cmd =
	'CLS&DIRECTION 1&'.
	'SIZE 32 mm, 25 mm&'.
	'GAP 2mm, 0&'.
	'TEXT 30,5,"TST24.BF2",0,1,1,"'.$Name.'"&'.
	'BARCODE 30,40,"128",100,1,0,2,2,"'.date("ymd").'3'.sprintf("%03d", $ProductID).'"&'.
	'PRINT '.$Quantity;
	
	//echo $Cmd;
	Printer_Cmd('dragonphenix', $Cmd);
	

?>





