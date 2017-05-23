<?php
date_default_timezone_set("Africa/Nairobi");
// CONNECTION TO DATABASE:PDO

$servername = 'localhost';
$username = 'root';
$password = '25812345Dan';
$dbname = 'dmclient';

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// successfully connected

} catch (PDOException $e) {

	// an error occured
	echo "Error occured " . $e->getMessage();

}
?>