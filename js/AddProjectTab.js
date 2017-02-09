<!--
$(document).ready(function() {
  var tab_template = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>",
 	    tabs = $( "#tabs" ).tabs();
 // actual addTab function: adds new tab using php script for new search tag
        function add_tag_tab(str) {
          var label = str || "All",
	        	last_list = $('ul#tab_list li:last').attr('aria-controls'),
	          tab_counter = (parseInt(/tabs-(\d+)/.exec(last_list)[1],10)+1),
            id = "tabs-" + tab_counter,
            li = $( tab_template.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
		        sort_id = $( "#sort" ).val(),
		        time_id = $( "#within" ).val(),
	        	url    = 'ProjectTableGen.php',
		        tabs   = $("#tabs").tabs();
		        bool   = 1;

          tabs.find( ".ui-tabs-nav" ).append( li );
          $.get(url, { time: time_id, sort: sort_id, tag_str: str, insert_bool: bool },
			      function(data){
				      tabs.append( "<div id='" + id + "'>" + data + "</div>" );
				      $( "#tabs" ).tabs( "refresh" );
			    });
        }
	function sort_change(sort_str){//Replaces the project_feed with the newly sorted data.
		var time_id = $( '#within' ).val();
		var url = 'ProjectTableGen.php';
		$.get(url, { time: time_id, sort: sort_str },
				function(sorted_data){
					$( '#project_feed' ).html(sorted_data);
					$( "#tabs" ).tabs();
				});
	}
	function time_change(time_str){//Replaces the project_feed with the new time data.
		var sort_id = $( '#sort' ).val();
		var url = 'ProjectTableGen.php';
		$.get(url, { time: time_str, sort: sort_id },
				function(time_data){
					$( '#project_feed' ).html(time_data);
					$( "#tabs" ).tabs();
				});
	}


        // addTab button: uses value of tag search as input in php form for new project_feed creation.
        $( "#tag_button" ).live('click',function() {
                add_tag_tab($( '#tag_search' ).val());
            });

        // close icon: removing the tab on click
        $( "#tabs span.ui-icon-close" ).live( "click", function() {
            var panel_id = $( this ).closest( "li" ).remove().attr( "aria-controls" ),
		            index = panel_id.match(/[\d]+$/)-2;
            $( "#" + panel_id ).remove();
	          $.get('remove_sess.php', { project_tag_index: index });
            tabs.tabs( "refresh" );
        });

	$( "#sort" ).live('change',function() {
	    sort_change($( '#sort' ).val());
	});
	$( "#within" ).live('change',function() {
	    time_change($( '#within' ).val());
	});
    });
-->
