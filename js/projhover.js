<!--
$(document).ready(function() {
  var offsetX = 20;
  var offsetY = 10;

  $('#project_subscribers_display a').hover(function(e) {
    var label = $(this).attr('data-label');
    $('<span id="pic_popup_label">'+label+'</span>')
    .css('top', e.pageY + offsetY)
    .css('left', e.pageX + offsetX)
    .appendTo('body');
  }, function() {
      $('#pic_popup_label').remove();
  });

  $('#project_subscribers_display a').mousemove(function(e) {
    $("#pic_popup_label").css('top', e.pageY + offsetY).css('left', e.pageX + offsetX);
  });
  $('#project_members_display a').hover(function(e) {
    var label = $(this).attr('data-label');
    $('<span id="pic_popup_label">'+label+'</span>')
    .css('top', e.pageY + offsetY)
    .css('left', e.pageX + offsetX)
    .appendTo('body');
  }, function() {
      $('#pic_popup_label').remove();
  });

  $('#project_members_display a').mousemove(function(e) {
    $("#pic_popup_label").css('top', e.pageY + offsetY).css('left', e.pageX + offsetX);
  });
});
