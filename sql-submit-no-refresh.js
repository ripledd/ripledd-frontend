// Comment submission 
$(document).ready(function() {
 $(".id_comment").click(function(e){
   e.preventDefault();
   var post_id = "<?=$post_id?>";
   var comment_data_raw = $("#comment_data").val();
   var comment_data = comment_data_raw.replace(/&/g, "*and*");
     $.ajax({
     type: "POST",
     url: "../comment-server.php",
     data: "post_id="+post_id+"&comment_data="+comment_data,
     success: function(data){
     setTimeout(function() {
      $(".message_box").html(data);
      document.getElementById("comment_data").value = "";
      var cmntHolder = document.getElementById("rec_comment_holder");
      cmntHolder.style.display = "block";
      document.getElementById('rec_comment_msg').innerHTML = comment_data_raw;
      });
     }
    });
   });
  });

// Like (Lit) submission
$(document).ready(function() {
 $(".like_post").click(function(e){
   e.preventDefault();
   var post_id = "<?=$post_id?>";
    $.ajax({
    type: "POST",
    url: "../like-count.php",
    data: "post_id="+post_id,
    success: function(data){
     setTimeout(function() {
     $(".message_box").html(data);
    });
   }
  });
 });
 });
