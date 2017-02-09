<!--
$(document).ready(function(){
  job_number = $("#job_create_form > div").size()+1;
  $("#add_job").live("click", function() {
    var location_list = '';
    $("#job_1 .job_locs.eq(0)").each(function(i){
      var label = $("label[for='"+$(this).attr('id')+"']");
    location_list.append('<input type="checkbox" name="job_loc['+job_number+'][]" id="job_'+job_number+'_loc_'+(i+1)+'" value="'+$(this).val()+'"><label for="job_'+job_number+'_loc_'+(i+1)'">'+label.val()+'</label>');
      });

    $("#job_create_form").append('<div id="job_'+job_number+'"><label>Job Title<input type="text" name="job_title[]" class="job_form_title"></label><br/><label>Job Description<textarea name="job_desc[]" class="job_form_desc" rows="4" cols="40"></textarea></label><br/><label>Tags<input type="text" name="job_tags[]" class="job_form_tags"></label><br/><label><input type="checkbox" name="money_job_'+(job_number-1)+'" class="money_job_form" value="'+(job_number-1)+'">Check this box if the position is paid.</label><br/>Select location(s) for the position (leave blank if job does not require a physical location):<br/><div class="job_locs">'+location_list+'</div><a href="#" id="rem_job">Remove job</a></div>');
    $job_number = job_number+1;
  });
	$('#rem_job').live('click', function() {
    var div_id = $(this).parents('div').attr("id"),
        i = (parseInt(/job_(\d+)/.exec(div_id)[1],10)+1),
        url = 'RemoveJob.php',
        id_check = div_id.concat('_id');

    //If the job already exists in the database the user will be asked to confirm the remove.
    if( $('#'+id_check).length ){
      var r=confirm("Are you sure you would like to remove this job? This will delete the job from your project.");
      if(r){
        $.post(url, { job_id: $('#'+id_check).attr('id') });
        $(this).parent('div').remove();
        job_number--;
        while(i<=job_number){
          $("#job_"+i).attr('id', "job_"+(i-1));
          var loc_num = $("#job_1 .job_locs.eq(0)").size();
          for(var j=1; j<=loc_number; j++){
            $("#job_"+i+"_loc_"+j).attr({name: "job_loc["+(i-1)+"][]", id: "job_"+(i-1)+"_loc_"+j});
            $("label[for='job_"+i+"_loc_"+j+"']").attr('for','job_'+(i-1)+'_loc_'+j);
          }
          i++;
        }
        return false;
      }
      else
        return false;
    }
    else{
      $(this).parent('div').remove();
      job_number--;
      while(i<=job_number){
        $("#job_"+i).attr('id', "job_"+(i-1));
        var loc_num = $("#job_1 .job_locs.eq(0)").size();
        for(var j=1; j<=loc_number; j++){
          $("#job_"+i+"_loc_"+j).attr({name: "job_loc["+(i-1)+"][]", id: "job_"+(i-1)+"_loc_"+j});
          $("label[for='job_"+i+"_loc_"+j+"']").attr('for','job_'+(i-1)+'_loc_'+j);
        }
        i++;
      }
      return false;
    }
  });

});
-->
