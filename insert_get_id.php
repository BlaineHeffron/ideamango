<?php
//insert_get_id() connects to the database using a PDO and inserts array of $values into their respective array of $columns
//using named prepared statements. The return value is the id of the inserted row (or the id of the existing row if the entry
//already exists). Returns a 0 if an error occurs. Note that the $columns array order must match its respective value in $values.
function insert_get_id($pdo,$table,$columns,$values){

  if(is_Array($columns))
  {
		$colstr1 = "";
		$colstr2 = "";
		$data = array();
		$i = 0;
    foreach($columns as $column){

			$colstr1 .= "$column, ";
			$colstr2 .= ":$column, ";
			$data[":$column"] = $values[$i];
			$i+=1;
		}
		$colstr1 = substr($colstr1,0,-2);
		$colstr2 = substr($colstr2,0,-2);
	}
  else
  {
		$colstr1 = $columns;
		$colstr2 = ":$columns";
		$data[":$columns"]=$values;
	}
  try
  {
		$str = "INSERT INTO $table ($colstr1) VALUES ($colstr2) ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";
		echo  "<p>$str </p>";
		$sth = $pdo->prepare($str);
		$sth->execute($data);
		return $pdo->lastInsertId();
  }catch(PDOException $e)
  {
		echo '<p>ERROR: ' . $e->getMessage() . '</p>';
		return 0;
	}
}?>

