$(document).ready(function(){
  var limit = 5;
  var start = 0;
  var action = 'inactive';
  function load_data(limit, start){
    $.ajax({
     url:"posts-fetch.php",
     method:"POST",
     data:{limit:limit, start:start},
     cache:false,
     success:function(data){
     $('#load_data').append(data);
       if(data == ''){
         $('#load_data_message').html("<p></p>");
         action = 'active';
         }else{
         $('#load_data_message').html("<p></p>");
           action = "inactive";
        }
       }
      });
     }
     if(action == 'inactive'){
      action = 'active';
      load_data(limit, start);
     }
     $(window).scroll(function(){
      if($(window).scrollTop() + $(window).height() > $("#load_data").height() - 2000 && action == 'inactive'){
       action = 'active';
       start = start + limit;
        setTimeout(function(){
         load_data(limit, start);
        }, 1);
       }
      });
     });
