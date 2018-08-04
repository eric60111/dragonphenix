<?php

	include 'lib_link2DB.php';
	
	/** Add Company **/
	$CompanyName = !empty($_POST['CompanyName']) ? $_POST['CompanyName'] : null;
	$ContactName = !empty($_POST['ContactName']) ? $_POST['ContactName'] : null; 
	$Address = !empty($_POST['Address']) ? $_POST['Address'] : null; 
	$Phone = !empty($_POST['Phone']) ? $_POST['Phone'] : null; 
	$TaxID = !empty($_POST['TaxID']) ? $_POST['TaxID'] : null; 
	$Remark = !empty($_POST['Remark']) ? $_POST['Remark'] : null; 
	
	
	$sql = "INSERT INTO Companies VALUES ";
	$sql .= "(NULL, '$CompanyName', '$ContactName', '$Address', '$Phone', '$TaxID', '$Remark', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$CompanyID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'CompanyID' => $CompanyID
    );
	
	print(json_encode($resp));
?>