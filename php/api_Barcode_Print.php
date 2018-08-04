<?php
	include 'lib_BarcodePrint.php';
	$ID = !empty($_POST['ID']) ? $_POST['ID'] : "%"; 
	$Type = !empty($_POST['Type']) ? $_POST['Type'] : "%"; 
	$Name = !empty($_POST['Name']) ? $_POST['Name'] : "%";
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : "1";

	$Cmd =
	'CLS&DIRECTION 1&'.
	'SIZE 32 mm, 25 mm&'.
	'GAP 2mm, 0&'.
	'TEXT 30,5,"TST24.BF2",0,1,1,"'.$Name.'"&'.
	'BARCODE 30,40,"128",100,1,0,2,2,"'.date("ymd").$Type.sprintf("%03d", $ID).'"&'.
	'PRINT '.$Quantity;
	
	//echo $Cmd;
	Printer_Cmd('dragonphenix', $Cmd);
?>





