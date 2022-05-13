//get live data _________________________________________________________________________
$(document).ready(function () {$("#live_search").keyup(function () {var query = $(this).val();if (query != "") {$.ajax({url: 'task/ajax-live-search.php',method: 'POST',data: {query: query},
success: function (data) {$('#search_result').html(data);$('#search_result').css('display', 'block');  $("#live_search").focusout(function () {$('#search_result').css('display', 'block');});$("#live_search").focusin(function () {$('#search_result').css('display', 'block');});}
});} else {$('#search_result').css('display', 'block');}});});

//show/hide results box _______________________________________
$('#live_search').keyup(function() {
if ($(this).val().length == 0) {
  $('.search_result_holder').hide();
  } else {
    $('.search_result_holder').show();
  }
}).keyup();
