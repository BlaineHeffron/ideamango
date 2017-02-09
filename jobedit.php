<?php include('mysql_connect.php');
$proj_id = $_POST['proj_job_id'];
$contact = $_POST['job_contact'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name ="author" content = "Blaine"><meta name = "revised" content = "2012/12/05"><meta name = "description" content = "job create and edit form"><meta name = "keywords" content = "project, help, job">
        <link rel="stylesheet" type="text/css" href="css/mango.css">
	<title>Help wanted post form</title>
	<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
	<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
	<script src="js/jobform.js"></script>
</head>
<body>

<form name="job_create_form" id="job_create_form" action="jobformsubmit.php" onsubmit="return validateForm();" method="post">
Need help on the project? Fill out this form to post help wanted listings for your project.
<input type="hidden" name="job_contact" value="<?php echo $contact;?>">
<input type="hidden" name="proj_id" value="<?php echo $proj_id;?>">

<?php
if(is_null($_POST['proj_job_loc_id']))
  $loc_ids = $loc_names = null;

else{
  $loc_ids = $_POST['proj_job_loc_id'];
  $loc_names = $_POST['proj_job_loc_name'];
}
if($_POST['proj_has_job']){
  $sql_job_tags = "SELECT t.name FROM jobs_tags as jt JOIN tags as t on t.id = jt.tag_id WHERE jt.job_id = ?";

  $job_ids = $_POST['job_id'];
  $job_titles = $_POST['job_title'];
  $job_paid_bools = $_POST['job_paid'];
  $job_contact_ids = $_POST['job_contact_id'];
  $job_descriptions = $_POST['job_desc'];
  $job_contact_names = $_POST['job_contact_username'];

  $j=0;//job counter
  foreach($job_ids as $job_id){

    $job_locs = $_POST["job_{$j}_loc_id"];


    echo '<div id="job_'.($j+1).'"><input type="hidden" id="job_'.($j+1).'_id" name="job_id[]" value="'.$job_id.'">
      <label>Job Title<input type="text" name="job_title[]" class="job_form_title" value="'.$job_titles[$j].'"></label><br/>
      <label>Job Description<textarea name="job_desc[]" class="job_form_desc" rows="4" cols="40">'.$job_descriptions[$j].'</textarea></label><br/>';
    try{
      $q = $conn->prepare($sql_job_tags);
      $q->execute(array($job['j.id']));
      $job_tags = $q->fetchAll();
      $tag_list = implode(', ', $job_tags);
    } catch(PDOException $e) {
      echo 'ERROR: ' . $e->getMessage();
    }

		echo '<label>Tags<input type="text" name="job_tags[]" class="job_form_tags" value="'.$tag_list.'"></label><br/>
			Hint: put words that are descriptive of either the qualifications or the type of job. Separate by commas i.e. software, engineer, website, programming<br/>
			<label><input type="checkbox" name="money_job_'.$j.'" class="money_job_form" value="'.$j.'">Check this box if the position is paid.</label><br/>
      Select location(s) for the position (leave blank if job does not require a physical location):<br/>
      <div class="job_locs">';
    $i=0; //location counter
    if(!is_null($loc_ids)){
      foreach($loc_ids as $loc_id){
        if(in_array($loc_id,$job_locs))
          echo '<input type="checkbox" name="job_loc['.($j+1).'][]" value="'.$loc_id.'" id="job_'.($j+1).'_loc_'.($i+1).'" checked="checked"><label for="job_'.($j+1).'_loc_'.($i+1).'">'.$loc_names[$i].'</label>';
        else
          echo '<input type="checkbox" name="job_loc['.($j+1).'][]" id="job_'.($j+1).'_loc_'.($i+1).'" value="'.$loc_id.'"><label for="job_'.($j+1).'_loc_'.($i+1).'">'.$loc_names[$i].'</label>';
        $i++;
      }
    }
    else
      echo 'There are no locations listed for your project. If you would like to list a location, click "edit my locations"';
    echo '</div><a href="#" id="rem_job">Remove job</a></div>';
    $j++;
  }
}
else{?>
  <div id="job_1">
   <label>Job Title<input type="text" name="job_title[]" class="job_form_title"></label><br/>
   <label>Job Description<textarea name="job_desc[]" class="job_form_desc" rows="4" cols="40"></textarea></label><br/>
   <label>Tags<input type="text" name="job_tags[]" class="job_form_tags"></label><br/>
			Hint: put words that are descriptive of either the qualifications or the type of job. Separate by commas i.e. software, engineer, website, programming<br/>
   <label><input type="checkbox" name="money_job_0" class="money_job_form" value="0">Check this box if the position is paid.</label><br/>
   Select location(s) for the position (leave blank if job does not require a physical location):<br/>
   <div class="job_locs">
<?php $i=0;
  if(!is_null($loc_ids)){
    foreach($loc_ids as $loc_id){
      //the name of the array is job_loc, first index denotes job number, second index are the possible checked locations
      echo '<input type="checkbox" name="job_loc[1][]" id="job_1_loc_'.($i+1).'" value="'.$loc_id.'"><label for="job_1_loc_'.($i+1)'.">'.$loc_names[$i].'</label>';
      $i++;
    }
  }
  else
    echo 'There are no locations listed for your project. If you would like to list a location, click "edit my locations"';
  echo '</div><a href="#" id="rem_job">Remove job</a></div>';
}?>
</form>

<a href="#" onclick="return false;" id="add_job">Add another job</a>

<script>
  function validateForm()
  {
    var job_titles = document.forms["job_create_form"].elements['job_title[]'];
    var job_descriptions = document.forms["job_create_form"].elements['job_desc[]'];
    var job_tags = document.forms["job_create_form"].elements['job_tags[]'];
    if (job_titles[0]==null || job_titles[0]=="")
      {
        alert("Job title needs to be filled out for job "+(i+1));
        return false;
      }
      if (job_descriptions[0]==null || job_descriptions[0]=="")
      {
        alert("Job description needs to be filled out for job "+(i+1));
        return false;
      }
      if (job_tags[0]==null || job_tags[0]=="")
      {
        alert("You need to add tags for job "+(i+1));
        return false;
      }

    for(i = 1; i < job_titles.length; i++){
			if (!((job_titles[i]==null || job_titles[i]=="") && (job_descriptions[i]==null || job_descriptions[i]=="") && (job_tags[i]==null || job_tags[i]=="")))
			{
				if (job_titles[i]==null || job_titles[i]=="")
				{
					alert("Job title needs to be filled out for job "+(i+1));
					return false;
				}
				if (job_descriptions[i]==null || job_descriptions[i]=="")
				{
					alert("Job description needs to be filled out for job "+(i+1));
					return false;
				}
				if (job_tags[i]==null || job_tags[i]=="")
				{
					alert("You need to add tags for job "+(i+1));
					return false;
				}
			}

    }
  }
</script>
</body>
</html>


