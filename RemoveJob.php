<?php include('mysql_connect.php');

$index = $_POST['job_id'];
try {
	  $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $remove_job = "DELETE FROM jobs WHERE id=?;DELETE FROM job_locations WHERE job_id=?;DELETE FROM job_tags WHERE job_id=?";
    if (strpos($conn->getAttribute(PDO::ATTR_CLIENT_VERSION), 'mysqlnd') !== false) {
      echo 'PDO MySQLnd enabled!';
    }
    $q = $conn->prepare($remove_job);
    $q->execute(array($index,$index,$index));
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}
$conn = null;
?>
