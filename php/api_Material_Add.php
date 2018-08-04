<?php

	include 'lib_link2DB.php';
	
	/** Add Material **/
	$Name = isset($_POST['Name']) ? $_POST['Name'] : null; 
	$Unit = isset($_POST['Unit']) ? $_POST['Unit'] : null; 
	$Number = isset($_POST['Number']) ? $_POST['Number'] : null; 
	$Color = isset($_POST['Color']) ? $_POST['Color'] : null;  
		
	$sql = "INSERT INTO Materials VALUES ";
	$sql .= "(NULL, '$Name', '$Unit', 0, 0, 0, 0, '$Number', '$Color', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$MaterialID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialID' => $MaterialID
    );
	
	print(json_encode($resp));

?>