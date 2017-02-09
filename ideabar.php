<?php
if($row['i.has_location']){
  $sql_loc = "SELECT ci.name as city_name, co.name as country_name, r.name as region_name, s.name as state_name, l.id as loc_id FROM idea_locations as il
              JOIN locations as l ON l.id = il.location_id
              JOIN cities as ci on ci.id = l.city_id
              JOIN regions as r on r.id = l.region_id
              JOIN countries as co on co.id = l.country_id
              JOIN states as s on s.id = l.state_id
              WHERE il.idea_id = {$row['idea_id']}";

  $idea_loc = $conn->query($sql_loc);
  $i = 0;
  while(!empty($idea_loc[$i]['co.name'])){

    $loc_echo  = "<a href='http://localhost/locations/{$idea_loc[$i]['l.id']}'>";

    if(!is_null($idea_loc[$i]['ci.name'])){
      if(!is_null($idea_loc[$i]['s.name']))
        $loc_echo .= "{$idea_loc[$i]['ci.name']}, {$idea_loc[$i]['s.name']}";
      else
        $loc_echo .= "{$idea_loc[$i]['ci.name']}, {$idea_loc[$i]['co.name']}";
    } elseif(!is_null($idea_loc[$i]['s.name'])){
      $loc_echo .= "{$idea_loc[$i]['s.name']}, {$idea_loc[$i]['co.name']}";
    } elseif(!is_null($idea_loc[$i]['r.name'])){
      $loc_echo .= "{$idea_loc[$i]['r.name']}, {$idea_loc[$i]['co.name']}";
    } else{
      $loc_echo .= $idea_loc[$i]['co.name'];
    }
    $loc_echo .= "</a><br/>";
    $i++;
  }

}
else
  $loc_echo = "<a href='http://localhost/locations/anywhere'>Anywhere</a>";
?>

<div class="idea_feed" style='background-color:red'>
<span class="vote" style="display:inline-block;">
  <img id="up" onclick="vote_up()" onmouseover="hover_img(this)" onmouseout="normal_img(this)" src="arrow_up_off.png" width="28" height="28">
  <br>
  <?php echo $row['likes']; ?>
  <img id="down" onclick="vote_down()" onmouseover=hover_img(this) onmouseout="normal_img(this)" src="arrow_down_off.png" width="28" height="28">
</span>
<span class="idea_feed_pic" style="display:inline-block;">
<a href="http://localhost/ideas/<?php echo $row['idea_id'];?>">
    <img src="<?php echo $row['pic'];?>" alt="<?php echo $row['i.title'];?>" width="100" height="75"><br>
  </a>
  <a href="http://localhost/users/<?php echo $row['user_id'];?>" id="author_link"> <?php echo $row['username'];?></a>"
</span>
<div class = "idea_feed_content" style="display:inline-block;">
  <a href="http://localhost/ideas/<?php echo $row['idea_id'];?>" id="idea_link">
    <h1> <?php echo $row['title'];?> </h1>
    <p> <?php echo $row['short_desc'];?> </p>
  </a>
  <span class="idea_feed_locations"><?php echo $loc_echo;?></span>
<?php if(isset($_SESSION['user_id'])){?>
<span class="button" id="favorite_idea_button"><form action="add_favorites.php"><input type="hidden" name="idea_favorites" value="<?php echo $row['idea_id'];?>"><button type="submit" value="Add to favorite ideas"></form></span>
  <span class="button" id="into_project_button"><a href="<?php echo $add_prof?>" id="add_proj" >Turn Into Project </a></span>
<?php }?>
<span class="media_buttons" >

<a href="facebook.com" target="_blank" align="left"><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/facebook.png"></a><a href="twitter.com" target="_blank" align="left"><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/twitter.png"></a><a href="myspace.com"https://docs.google.com/viewer?a=v&pid=gmail&attid=0.1.1&thid=13b3b676111ab964&mt=application/vnd.ms-excel&url=https://mail.google.com/mail/u/0/?ui%3D2%26ik%3D994cbba218%26view%3Datt%26th%3D13b3b676111ab964%26attid%3D0.1.1%26disp%3Dsafe%26zw&sig=AHIEtbQX-6rQREDKaW9xSGPwsv21ufp4Sw target="_blank" align="left"><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/myspace.png"></a><a href="youtube.com" target="_blank" alig   n="left"><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/youtube.png"></a><a href="reddit.com" target="_blank" align="left"><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/reddit.png"></a><a href="digg.com" target="_blank" align="left"><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/digg.png"></a><a href="linkedin.com" target="_blank" align="left"><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/linkedin.png"></a><a href="flickr.com" target="_blank" align="left" ><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/flickr.png"></a><a href="rssfeed.com" target="_blank" align="left" ><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/rssfeed.png"></a><a href="stumbleupon.com" target="_blank" align="left" ><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/stumbleupon.png"></a><a href="delicious.com" target="_blank" align="left" ><img width="25" height="25" src="http://www.niftybuttons.com/webicons2/delicious.png"></a>


</span>
</div>
<hr>

</div>

