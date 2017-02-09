<!doctype html>
<html>
<head><meta charset="utf-8"><meta name ="author" content = "Blaine"><meta name = "revised" content = "2012/10/20"><meta name = "description" content = "Idea home page"><meta name = "keywords" content = "idea, sharing, network">
        <link rel="stylesheet" type="text/css" href="css/mango.css">
	<title>Idea sharing home page</title>
	<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
	<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>

</head>
<body>
<form method="post">
    <input id="email" value="1"  name="email" type="text">
    <input type="submit">
</form>

<?php
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
$num_entries = 2;
$table = 'ideas';
$sort = 'likes';
$num_days = 10;
$sql_query = "SELECT i.id, u.username, u.id as user_id, i.creation_date, i.title, i.short_desc, i.pic, i.likes, i.views, t.name as tag_name, ci.name as city_name, co.name as country_name, r.name as region_name, s.name as state_name, l.id as loc_id FROM
    FROM $table AS i
    JOIN users as u ON u.id = i.manager_id
    JOIN idea_tags as it ON it.idea_id = i.id
    JOIN tags as t ON t.id = it.tag_id
    WHERE i.creation_date > DATE_SUB(CURDATE(), INTERVAL $num_days DAY)
    ORDER BY i.$sort
    LIMIT $num_entries";
 try {
	  $conn = new PDO($dsn, $user, $pass);
	  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $data = $conn->query($sql_query);
		//echo '<ol>';
    print_r($data);
    /*foreach($data as $row){
			include('ideabar.php');
 }*/
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}
?>

</body>
</html>
