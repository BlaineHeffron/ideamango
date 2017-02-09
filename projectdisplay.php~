<?php session_start();

include('mysql_connect.php');

//array_value_recursive will extract all of the values associated with a given $key in a multidimensional array and output them into an array of values or arrays.
function array_value_recursive($key, array $arr){
    $val = array();
    array_walk_recursive($arr, function($v, $k) use($key, &$val){
        if($k == $key) array_push($val, $v);
    });
    return count($val) > 1 ? $val : array_pop($val);
}

$proj_id = $_GET['proj_id'];
if(isset($_SESSION['user_id']))
  $user_id = $_SESSION['user_id'];
else
  $user_id = null;
$sql_retrieve = "SELECT p.date_changed, p.title, p.detailed_desc, p.short_desc, p.motivation, p.has_location, p.newsfeed, p.goals, p.obstacles, p.manager_id, p.bool_jobs, p.pic, p.likes, p.views, u.username, u.id FROM projects as p
  JOIN users as u on u.id = p.manager_id
  WHERE id = ?";
//Grab all project information to be displayed.
try{
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $q = $conn->prepare($sql_retrieve);
    $q->execute(array($proj_id));
    $proj_data = $q->fetchAll();
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

//Grab all the tags for the project.
try {
  $sql_tags = "SELECT t.name FROM projects_tags as pt
    JOIN tags as t ON t.id = pt.tag_id
    WHERE pt.project_id = ?";
  $q = $conn->prepare($sql_tags);
  $q->execute(array($proj_id);
  $tags = $q->fetchAll();
  foreach($tags as $tag){
    $tag_string .= "{$tag['t.name']}, ";
    $tag_links .= "<a href='http://localhost/tag/{$tag['t.id']}'>{$tag['t.name']}</a>, ";
  }
  $tag_string = substr($tag_string,0,-2);
  $tag_links = substr($tag_links,0,-2);
  } catch(PDOException $e) {
  echo 'ERROR: ' . $e->getMessage();
}

//Grab all the locations for the project.
if($proj_data['p.has_location']){
  try{
    $sql_loc = "SELECT ci.name, co.name, r.name, s.name, l.id FROM projects_locations as pl
    JOIN locations as l ON l.id = pl.location_id
    JOIN cities as ci on ci.id = l.city_id
    JOIN regions as r on r.id = l.region_id
    JOIN countries as co on co.id = l.country_id
    JOIN states as s on s.id = l.state_id
    WHERE pl.project_id = ?";
  $q = $conn->prepare($sql_loc);
  $q->execute(array($proj_id);
  $proj_loc = $q->fetchAll();

  } catch(PDOException $e) {
    echo 'ERROR: ' .$e->getMessage();
  }
}

//Grab all the job information for the project.
if($proj_data['p.has_jobs']){
  try{
    $sql_job = "SELECT j.title, j.bool_paid, u.username, u.first_name, u.last_name, j.contact_id, j.id, j.description
      FROM jobs as j
      JOIN users as u on u.id = j.contact_id
      WHERE j.proj_id = ?";
    $q = $conn->prepare($sql_job);
    $q->execute(array($proj_id);
    $proj_jobs = $q->fetchAll();
  } catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
  }
}

//Update the view count for the project page.
$view_count = $proj_data['p.views']+1;
try{
  $add_view_sql = "INSERT INTO projects (views) VALUES (?)";
  $q = $conn->prepare($add_view_sql);
  $q->execute(array($view_count));
} catch(PDOException $e) {
  echo 'ERROR: '. $e->getMessage();
}

//Get all the project's picture urls.
try{
  $sql_pics = "SELECT url FROM project_pics WHERE project_id = ?";
  $q = $conn->prepare($sql_pics);
  $q->execute(array($proj_id));
  $proj_pic_urls = $q->fetchAll();
} catch(PDOException $e) {
  echo 'ERROR: ' .$e->getMessage();
}

//Get all of the project's subscriber usernames and ids.
try{
  $sql_subscribers = "SELECT ps.user_id, u.username
                      FROM project_subscribers as ps
                      JOIN users as u on u.id = ps.user_id
                      WHERE ps.project_id = ?";
  $q = $conn->prepare($sql_subscribers);
  $q->execute(array($proj_id));
  $proj_subscribers = $q->fetchAll();
} catch(PDOException $e) {
  echo 'ERROR: ' .$e->getMessage();
}
//Get all of the project's member usernames and ids.
try{
  $sql_members = "SELECT pm.user_id, u.username, u.pic
                  FROM project_members as pm
                  JOIN users as u on u.id = pm.user_id
                  WHERE pm.project_id = ?";
  $q = $conn->prepare($sql_members);
  $q->execute(array($proj_id));
  $proj_members = $q->fetchAll();
} catch(PDOException $e) {
  echo 'ERROR: ' .$e->getMessage();
}


?>

<html>

<head><meta charset="utf-8"><meta name ="author" content = "Blaine"><meta name = "revised" content = "2012/11/26"><meta name = "description" content = "Project"><meta name = "keywords" content = "<?php echo $tag_string;?>">
        <link rel="stylesheet" type="text/css" href="css/mango.css">
        <title><?php echo $proj_data['title'];?></title>
        <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
        <script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
        <script src='js/projhover.js'></script>
        <script src="js/ProjCharCounter.js"></script>
</head>
<body>
  <?php include('header.php');
    if($proj_data['p.manager_id'] == $user_id)
      $edit_bool = true;
    else
      $edit_bool = false;
  ?>

  <div id="proj_top" >
    <span id="like_count" > <?php echo $proj_data['p.likes'];?></span>
    <h1 id="proj_title" contenteditable="<?php echo $edit_bool;?>">
      <?php echo $proj_data['p.title'];?>
    </h1>
    <span id="proj_tag_list" contenteditable="<?php echo $edit_bool;?>">
      <?php if($edit_bool)
              echo $tag_string;
            else
              echo $tag_links; ?></span>
    <p id="proj_manager"> <a href="http://localhost/user/<?php echo $proj_data['u.id']; ?>"><?php echo $proj_data['u.name']; ?></a>
    </p>
    <p id="proj_short_desc" contenteditable="<?php echo $edit_bool;?>"> <?php echo $proj_data['p.short_desc'];?></p>
    <?php if($edit_bool)
            echo '<div id="counter"></div>';?>
    <p id="proj_location"> Location:
      <?php
      if($proj_data['p.has_location']){
        $i = 0;
        while(!empty($proj_loc[$i]['co.name'])){
          echo "<a href='http://localhost/locations/{$proj_loc[$i]['l.id']}'>";
          $proj_loc_id[] = $proj_loc[$i]['l.id'];
          if(!is_null($proj_loc[$i]['ci.name'])){
            if(!is_null($proj_loc[$i]['s.name'])){
              $proj_loc_name[] = "{$proj_loc[$i]['ci.name']}, {$proj_loc[$i]['s.name']}";
              echo $proj_loc_name[$i];
            }
            else{
              $proj_loc_name[] = "{$proj_loc[$i]['ci.name']}, {$proj_loc[$i]['co.name']}";
              echo $proj_loc_name[$i];
            }
          } elseif(!is_null($proj_loc[$i]['s.name'])){
            $proj_loc_name[] =  "{$proj_loc[$i]['s.name']}, {$proj_loc[$i]['co.name']}";
            echo $proj_loc_name[$i];
          } elseif(!is_null($proj_loc[$i]['r.name'])){
            $proj_loc_name[] = "{$proj_loc[$i]['r.name']}, {$proj_loc[$i]['co.name']}";
            echo $proj_loc_name[$i];
          } else{
            $proj_loc_name[] = $proj_loc[$i]['co.name'];
            echo $proj_loc_name[$i];
          }
          echo "</a><br/>";
          $i++;
        }
      }
      else{
        $proj_loc_name[0] = null;
        $proj_loc_id[0] = null;
        echo "<a href='http://localhost/locations/none'>Anywhere</a>";
      }
      ?>
    </p>
    <span id="tag_list"><?php echo $tag_links;?></span>
  </div>
  <div id="proj_video">
    <?php if(!is_null($proj_data['video'])){
      echo '<video width="420" height="340" controls="controls" >
              <source src="'. $proj_data["video"] . '.ogg" type="video/ogg">
              <source src="'. $proj_data["video"] . '.mp4" type="video/mp4">
              <source src="'. $proj_data["video"] . '.webm" type="video/webm">
              <object data="'. $proj_data["video"] . '.mp4" width="320" height="240">
               <embed width="420" height="340" src="' . $proj_data["video"] . '.swf">
              </object>
              </video>';
      }?>
  </div>
  <div id="proj_content" >
    <h4 contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.heading_1']; ?></h4><p id="proj_description" contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.detailed_desc']; ?> </p>
    <?php if(!is_null($proj_data['p.motivation']))
    {?>
    <h4 contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.heading_2']; ?></h4><p id="proj_motivation" contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.motivation']; ?></p>
    <?php }?>
    <?php if(!is_null($proj_data['p.newsfeed']))
    {?>
   <h4 contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.heading_3']; ?></h4> <p id="proj_news" contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.newsfeed']; ?></p>
    <?php }?>
    <?php if(!is_null($proj_data['p.goals']))
    {?>
    <h4 contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.heading_4']; ?></h4><p id="proj_goals" contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.goals']; ?></p>
    <?php }?>
    <?php if(!is_null($proj_data['p.obstacles']))
    {?>
    <h4 contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.heading_5']; ?></h4><p id="proj_obstacles" contenteditable="<?php echo $edit_bool;?>"><?php echo $proj_data['p.obstacles']; ?></p>
    <?php }?>
  </div>
  <div id="proj_job_display" >
    <?php
    if($proj_data['p.has_jobs']){
      echo '<div id="proj_jobs"><h4>Help Out</h4>
        <form name="proj_job_edit_form" action="jobedit.php" method="post"><input name="job_contact" type="hidden" value="'.$proj_data["u.id"].'">';
      $j=0;
      foreach($proj_loc_id as $location_id){
        echo '<input name="proj_job_loc_id[]" type="hidden" value="'.$location_id.'"><input name="proj_job_loc_name[]" type="hidden" value="'.$proj_loc_name[$j].'">';
        $j++;
      }
        echo '<ul>';
      $i = 0;
      while(!is_null($proj_jobs[$i]['j.name'])){
        $sql_job_locs = "SELECT l.id FROM job_locations as jl
                    JOIN locations as l ON l.id = jl.location_id
                    WHERE jl.job_id = ?";
        try{
          $q = $conn->prepare($sql_job_locs);
          $q->execute(array($proj_jobs[$i]['j.id']));
          $proj_job_locs = $q->fetchAll();
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }


        echo '<input type="hidden" name="job_id[]" value="'.$proj_jobs[$i]['j.id'].'"><input type="hidden" name="job_title[]" value="'.$proj_jobs[$i]['j.title'].'"><input type="hidden" name="job_paid[]" value="'.$proj_jobs[$i]['j.bool_paid'].'"><input type="hidden" name="job_contact_id[]" value="'.$proj_jobs[$i]['j.contact_id'].'"><textarea name="job_desc[]" style="display:none;visibility:none">'.$proj_jobs[$i]['j.description'].'</textarea><input type="hidden" value="'.$proj_jobs[$i]["u.username"].'" name="job_contact_username[]">';
        foreach($proj_job_locs as $job_loc_id){
          echo '<input type="hidden" name="job_'.$i.'_loc_id[]" value="'.$job_loc_id.'">';
        }
        echo '<li><a href="http://localhost/jobs/'.$proj_jobs[$i]["j.id"].'">'. $proj_jobs[$i]["j.title"] . '</a></li>';
        $i++;
      }
      echo'</ul></form></div>';
      if($edit_bool)
        echo '<input type="hidden" name="proj_job_id" value="'.$proj_id.'"><input type="hidden" name="proj_has_job" value="true"><input type="submit" value="Edit Listings"></form>';
    }
    else{
      if($edit_bool)
        echo '<input type="hidden" name="proj_job_id" value="'.$proj_id.'"><input type="hidden" name="proj_has_job" value="false"><input type="submit" value="Add help needed listings"></form>';
    }
      ?>
  </div>
  <div id="proj_manager_box"> <?php include('user_display_box.php?id='.$proj_data["u.id"]);
    if($edit_bool)
      echo '<form name="proj_user_edit_form" action="useredit.php" method="post"><input type="hidden" name="proj_manager_id" value="'.$proj_data["p.manager_id"].'"><input type="submit" value="Edit profile"></form>';
?> </div>

  <div id="proj_members_display">
    <?php $member_count = count($proj_members);
    ?>
    <span id="member_count"><?php echo $member_count;?> members </span>
    <?php
    $i = 0; //member counter
    shuffle($proj_members);
    foreach($proj_members as $member){
      echo "<a href='http://localhost/user/{$member['pm.user_id']}' data-label='{$member['u.username']}' class='member_pic' id='member_$i'>
        <img class='member_pic' src='{$member['u.pic']}'></a>";
      $i++;
      if($i>7)
        break;
    }?>
  </div>
  <div id="proj_subscribers_display"><?php include('subscriberdisplay.php?subs_proj_id='.$proj_data["p.id"].'&subs_proj_title='.$proj_data["p.title"].'&display=part');?></div>

<form name="project_edit_form" action="projectformedit.php" method="post" onsubmit="return validateForm();">
  <input type="hidden" name="title_edit">
  <input type="hidden" name="tag_edit">
  <textarea name="short_desc_edit" cols="20" rows="20" style="display:none;visibility:none"></textarea>
  <input type="hidden" name="heading_1_edit">
  <textarea name="detailed_desc_edit" cols="20" rows="20" style="display:none;visibility:none"></textarea>
  <input type="hidden" name="heading_2_edit">
  <textarea name="motivation_edit" cols="20" rows="20" style="display:none;visibility:none"></textarea>
  <input type="hidden" name="heading_3_edit">
  <textarea name="news_edit" cols="20" rows="20" style="display:none;visibility:none"></textarea>
  <input type="hidden" name="heading_4_edit">
  <textarea name="goals_edit" cols="20" rows="20" style="display:none;visibility:none"></textarea>
  <input type="hidden" name="heading_5_edit">
  <textarea name="obstacles_edit" cols="20" rows="20" style="display:none;visibility:none"></textarea>
  <input type="hidden" name="proj_edit_id" value="<?php echo $proj_id;?>">
  <input type="submit" value="Save changes">
</form>
  <script>
    function validateForm()
	  {
      var title     = document.forms["project_edit_form"]["title_edit"].value;
      var proj_tags  = document.forms["project_edit_form"]["project_tags_edit"].value;
      var short_desc = document.forms["project_edit_form"]["short_desc_edit"].value;
      var head_1    = document.forms["project_edit_form"]["heading_1_edit"].value;
      var head_2    = document.forms["project_edit_form"]["heading_2_edit"].value;
      var head_3    = document.forms["project_edit_form"]["heading_3_edit"].value;
      var head_4    = document.forms["project_edit_form"]["heading_4_edit"].value;
      var head_5    = document.forms["project_edit_form"]["heading_5_edit"].value;

      if (title==null || title=="")
      {
              alert("Title needs to be filled out");
        return false;
        }
      if (proj_tags==null || proj_tags=="")
      {
        alert("Tags field needs to be filled out");
        return false;
      }
      if (short_desc==null || short_desc=="")
      {
        alert("You need to fill out a short description of your project");
        return false;
      }
      if (title.length > 50)
      {
        alert("Your title can be no longer than 50 characters.");
        return false;
      }
      if (short_desc.length > 255)
      {
        alert("Your short description can be no longer than 255 characters.");
        return false;
      }
     if (head_1.length > 60)
      {
        alert("Your first header can be no longer than 60 characters.");
        return false;
      }
     if (head_2.length > 60)
      {
        alert("Your second header can be no longer than 60 characters.");
        return false;
      }
     if (head_3.length > 60)
      {
        alert("Your third header can be no longer than 60 characters.");
        return false;
      }
     if (head_4.length > 60)
      {
        alert("Your fourth header can be no longer than 60 characters.");
        return false;
      }
     if (head_5.length > 60)
      {
        alert("Your fifth header can be no longer than 60 characters.");
        return false;
      }
    }

  <!--
  var editable = document.querySelectorAll('[contenteditable=true]');

  for (var i=0, len = editable.length; i<len; i++){
    editable[i].setAttribute('data-orig',editable[i].innerHTML);

    editable[i].onblur = function(){
        if (this.innerHTML == this.getAttribute('data-orig')) {
            // no change
        }
        else {
            // change has happened, store new value
            this.setAttribute('data-orig',this.innerHTML);
            var hidden_form = document.getElementById("project_edit_form");
            hidden_form.elements[i].value = this.innerHTML;
        }
    };
  }
  -->
</script>

</body>
</html>


