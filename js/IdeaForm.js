<!--
$(document).ready(function(){
	idea_loc_number = $("#idea_location_form > div").size()+1;
	$("#add_idea_location").live("click", function() {
		$("#idea_location_form").append('<div id="locd'+idea_loc_number+'"><label for="city'+idea_loc_number+'">City</label><input type="text" name="city'+idea_loc_number+'" id="city'+idea_loc_number+'"><label for="region'+idea_loc_number+'">State/Province/Region</label><input type="text" name="region'+idea_loc_number+'" id="region'+idea_loc_number+'"><label for="country'+idea_loc_number+'">Country</label><input type="text" name="country'+idea_loc_number+'" id="country'+idea_loc_number+'"><a href="#" id="rem_loc">Remove location</a></div>');
		for(var i=1; i<idea_job_number; i++){
			$('#job_'+i+'_locs ul').append('<li><input type="checkbox" name="job_'+i+'_loc_'+idea_loc_number+'" id="job_'+i+'_loc_'+idea_loc_number+'" value="0"><label for="job_'+i+'_loc_'+idea_loc_number+'">Location '+idea_loc_number+'</label></li>');
		}

		idea_loc_number = idea_loc_number+1;
	});

	$('#rem_loc').live('click', function() {
    if( idea_loc_number > 2 ) {
      var input_select = $(this).parents('div').attr("id");
			var i = (parseInt(/locd(\d+)/.exec(input_select)[1],10)+1);
			$(this).parent('div').remove();
			for(var k=1; k<idea_job_number; k++){
				$("#job_"+k+"_loc_"+(i-1)).parent('li').remove();
			}
                        idea_loc_number--;
			while(i<=idea_loc_number){
				$("#locd"+i).attr('id', "locd"+(i-1));
				$("input[name=city"+i+"]").attr({name: "city"+(i-1), id: "city"+(i-1)});
				$("label[for='city"+i+"']").attr('for','city'+(i-1));
				$("input[name=region"+i+"]").attr({name: "region"+(i-1), id: "region"+(i-1)});
				$("label[for='region"+i+"']").attr('for','region'+(i-1));
				$("input[name=country"+i+"]").attr({name: "country"+(i-1), id:"country"+(i-1)});
				$("label[for='country"+i+"']").attr('for','country'+(i-1));
				i++;
			}
    }
    return false;
  });


	$("input[name^=city]").live("change", function(){
    var input_name_str = $(this).attr("id");
		var i = parseInt(/city(\d+)/.exec(input_name_str)[1],10);
		var region_name = $('#region'+i).val();
		var city_name = $(this).val();
  		for(var j=1; j<idea_job_number; j++){
			$('label[for="job_'+j+'_loc_'+i+'"]').html(city_name+' , '+region_name).change();
		}
	});
	$("input[name^=region]").live("change", function(){
		var input_name_str = $(this).attr("id");
		var i = parseInt(/region(\d+)/.exec(input_name_str)[1],10);
		var city_name = $('#city'+i).val();
		var region_name = $(this).val();
	});

	var characters = 255;
	$("#counter").append("You have  <strong>"+ characters+"</strong> characters remaining");
	$("#short_desc").keyup(function(){
    if($(this).val().length > characters){
      $(this).val($(this).val().substr(0, characters));
    }
    var remaining = characters -  $(this).val().length;
    $("#counter").html("You have <strong>"+  remaining+"</strong> characters remaining");
    if(remaining <= 10)
    {
      $("#counter").css("color","red");
    }
    else
    {
      $("#counter").css("color","black");
    }
  });
});
-->
