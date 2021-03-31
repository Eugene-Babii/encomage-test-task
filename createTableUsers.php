<!-- script for creating table users -->
<?php
$dbHost = 'localhost';
$dbName = 'test_encomage_db';
$dbUser = 'root';
$dbPass = 'root';
$dbTable = 'users';

$conn = mysqli_connect($dbHost, $dbUser, $dbPass) or die('Error db connection' . mysqli_error($conn));
echo "Connected successfully\n";
mysqli_select_db($conn, $dbName) or die('DB open error');
echo "DB open successfully\n";
mysqli_query($conn, 'set names "utf8"');

$query = "CREATE TABLE IF NOT EXISTS $dbTable( " .
   "id INT NOT NULL AUTO_INCREMENT, " .
   "first_name VARCHAR(64) NOT NULL, " .
   "last_name VARCHAR(64) NOT NULL, " .
   "email VARCHAR(64) NOT NULL, " .
   "create_date DATETIME NOT NULL, " .
   "update_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
   "PRIMARY KEY ( id )); ";
mysqli_query($conn, $query);

$err = mysqli_errno($conn);
if ($err) {
   echo 'Сreate table error:' . $err . '<br/>';
}
echo "Table created successfully\n";

mysqli_close($conn);
