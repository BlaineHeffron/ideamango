<!--
$(document).ready(function(){
  //the following makes contenteditable respond to onchange events
  /*$('[contenteditable]').live('focus', function() {
    var $this = $(this);
    $this.data('before', $this.html());
    return $this;
  }).live('blur keyup paste', function() {
    var $this = $(this);
    if ($this.data('before') !== $this.html()) {
        $this.data('before', $this.html());
        $this.trigger('change');
    }
    return $this;
  });*/
    var characters = 255;
  $("#counter").append("You have  <strong>"+ characters+"</strong> characters remaining");
  //$("li#project_job_form").live(
  $("#proj_short_desc").keyup(function(){
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
