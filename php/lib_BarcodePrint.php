<?php
	function Printer_Cmd($Target,$Cmd) {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		$connection = socket_connect($socket,'122.116.105.248', 9527);

		socket_write($socket, "CMD@Dragonphenix\r\n", strlen("CMD@Dragonphenix\r\n"));
		socket_write($socket, "CMD@$Cmd\r\n", strlen("CMD@$Cmd\r\n"));
		socket_write($socket, "CLOSE\r\n", strlen("CLOSE\r\n"));

		socket_close($socket);
	}
?>