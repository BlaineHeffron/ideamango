<?php
$user_id = $_GET['id'];
include('mysql_connect.php');
$sql_user = "SELECT u.username, u.first_name, u.last_name, u.pic, u.profession, ci.name, co.name, s.name, r.name
  FROM users as u
  JOIN locations as l on l.id = u.location_id
  JOIN cities as ci on ci.id = l.city_id
  JOIN regions as r on r.id = l.region_id
  JOIN countries as co on co.id = l.country_id
  JOIN states as s on s.id = l.state_id
  WHERE u.id = ?";
try {
  $dbh = new PDO($dsn, $user, $pass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $q = $dbh->prepare($sql_user);
  $q->execute(array($user_id));
  $user_data = $q->fetchAll();
} catch(PDOException $e) {
  echo 'ERROR: ' . $e->getMessage();
}
?>

<div class='user_box'>
  <span class='user_prof_pic'><img src="<?php echo $user_data['u.pic']; ?>" alt="<?php echo $user_data['u.username']?> profile pic"></span>
  <div class="user_info">
    <h3><a href="http://localhost/users/<?php echo $user_id;?>"><?php
      if(!is_null($user_data['first_name'])){
        if(!is_null($user_data['last_name']))
          echo "{$user_data['u.first_name']} {$user_data['u.last_name']}";
        else
          echo $user_data['u.first_name'];
      }
      else
        echo $user_data['u.username'];
      ?>
      </a>
    </h3>
    <p><?php echo $user_data['u.profession'];?></p>
    <p id="user_location">
      <?php
      if($user_data['p.has_location']){
        $i = 0;
        while(!empty($user_data['co.name'])){
          echo "<a href='http://localhost/locations/{$user_data['l.id']}'>";

          if(!is_null($user_data['ci.name'])){
            if(!is_null($user_data['s.name']))
              echo "{$user_data['ci.name']}, {$user_data['s.name']}";
            else
              echo "{$user_data['ci.name']}, {$user_data['co.name']}";
          } elseif(!is_null($user_data['s.name'])){
            echo "{$user_data['s.name']}, {$user_data['co.name']}";
          } elseif(!is_null($user_data['r.name'])){
            echo "{$user_data['r.name']}, {$user_data['co.name']}";
          } else{
            echo $user_data['co.name'];
          }
          echo "</a>";
          $i++;
        }
      }
      ?>
    </p>
  </div>
</div>


