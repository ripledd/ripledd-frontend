<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Notifications - Speecher</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/notifi-n.css">
    <link rel="stylesheet" href="css_mobile/m-header.css">
    <link rel="stylesheet" href="css_mobile/m-notifi-n.css">
    <link rel="stylesheet" href="css/dark.css">
  </head>
  <body>
    <!-- HEADER ------------------------------------------------------------------------------------------------------>
    <!-- function for user's new notifications ---->
    <?php
      session_start();
        $secure_id = $_SESSION['secure_id'];
        if ($secure_id == ""){header("location: login");}
          //get user data
            $sql = "SELECT * FROM users WHERE secure_id = '$secure_id'";
               $rs = mysqli_query($dbconn, $sql);
                  //get row
                    $fetchRow = mysqli_fetch_assoc($rs);
                       $avatar = $fetchRow['avatar'];

    ?>
    <div class="header">
      <div class="header_content_holder">
        <div class="m_elements_h">
          <!-- Elements for mobile START ---------------------------------------------------------------------->
          <img class="logo_main_m" onclick="window.location.href='../'" src="img/m_logo.png" alt="speecher_logo">
          <?php if ($secure_id == ""):echo "<style>.my_profile_bar_m{display:none;}  </style>";?>
          <?php endif; ?>
          <img onclick="openAccountMenu()" class="my_profile_bar_m" src="profile/<?=$avatar?>" alt="">
        </div>
        <!-- Elements for mobile END ---------------------------------------------------------------------->
        <picture><source srcset="img/speecher_logo_light.png" media="(prefers-color-scheme: dark)"><img class="logo_main" onclick="window.location.href='../'" src="img/speecher_logo.png" alt="speecher_logo"></picture>
          <form class="search_box" action="search.php" method="post">
            <input id="live_search" class="search_input" type="text" autocomplete="off" placeholder="Find content..."  name="input_value" value="">

            <!-- Scripts for search ---------------------------------------------------------------------->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script type="text/javascript">
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
            </script>

            <svg class="search_icon" viewBox="0 0 20 20">
             <path fill="none" d="M19.129,18.164l-4.518-4.52c1.152-1.373,1.852-3.143,1.852-5.077c0-4.361-3.535-7.896-7.896-7.896c-4.361,0-7.896,3.535-7.896,7.896s3.535,7.896,7.896,7.896c1.934,0,3.705-0.698,5.078-1.853l4.52,4.519c0.266,0.268,0.699,0.268,0.965,0C19.396,18.863,19.396,18.431,19.129,18.164z M8.567,15.028c-3.568,0-6.461-2.893-6.461-6.461s2.893-6.461,6.461-6.461c3.568,0,6.46,2.893,6.46,6.461S12.135,15.028,8.567,15.028z">
                </path>
                  </svg>
                     </input>
                        <input type="submit" style="display:none;"  name="search_value" value="">
                           </form>
                              <div class="search_result_holder">
                                <div id="search_result"></div>
                                  </div>

        <?php if ($secure_id == ""):
                echo "<style>
                .nav_div{display:none;}
                .my_account{display:none;}
                </style>";?>
        <?php else: echo "<style>
        .join_us_holder{display:none;}
        </style>";?>
        <?php endif; ?>

        <div class="nav_div">
          <button  onclick="window.location.href='../'" class="nav_btn" type="button" name="button">
            <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          </button>
          <button onclick="window.location.href='explore'" class="nav_btn" type="button" name="button">
            <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><circle cx="12" cy="12" r="10"/><path d="M16.2 7.8l-2 6.3-6.4 2.1 2-6.3z"/></svg>
          </button>
          <button onclick="openPostMenu()" class="nav_btn" type="button" name="button">
            <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
          </button>
        </div>

        <div id="post_selection_menu" class="add_post_menu">
          <button onclick="window.location.href='create'" class="add_post_menu_btn" type="button" ><span class="add_post_menu_btn_txt" >Create new post</span>
            <svg class="svg_add_post_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"></polygon></svg>
          </button>
          <br>
          <button onclick="window.location.href='account/manage?add_status'" class="add_post_menu_btn" type="button" ><span class="add_post_menu_btn_txt">Add new status</span>
            <svg class="svg_add_post_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><path d="M5.52 19c.64-2.2 1.84-3 3.22-3h6.52c1.38 0 2.58.8 3.22 3"/><circle cx="12" cy="10" r="3"/><circle cx="12" cy="12" r="10"/></svg>
          </button>
        </div>

        <div id="account_selection_menu" class="account_menu">
          <button onclick="window.location.href='edit'" class="account_menu_btn" type="button" ><span class="account_menu_btn_txt" >Edit account</span>
            <svg class="svg_account_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="14 2 18 6 7 17 3 17 3 13 14 2"></polygon><line x1="3" y1="22" x2="21" y2="22"></line></svg>
          </button>
          <br>
          <button onclick="window.location.href='saved'" class="account_menu_btn" type="button" ><span class="account_menu_btn_txt">Liked content</span>
            <svg class="svg_account_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
          </button>
          <br>
          <button onclick="window.location.href='comments'" class="account_menu_btn" type="button" ><span class="account_menu_btn_txt">My comments</span>
            <svg class="svg_account_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
          </button>
          <br>
          <button onclick="window.location.href='logout.php'" class="account_menu_btn" type="submit" ><span class="account_menu_btn_txt">Logout</span>
            <svg class="svg_account_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line>
            </svg>
          </button>
        </div>

        <div class="join_us_holder">
          <button onclick="window.location.href='login'" class="login_btn" type="button" name="button">Log In</button>
          <button onclick="window.location.href='signup'" class="signup_btn" type="button" name="button">Sign Up</button>
        </div>


        <div class="my_account">
          <img onclick="openAccountMenu()" class="my_profile_bar" src="profile/<?=$avatar?>" alt="">
        </div>
        <!-- Script for post selection menu ----------->
        <script type="text/javascript">
          document.addEventListener('mouseup', function(e) {
            var container = document.getElementById('post_selection_menu');
              if (!container.contains(e.target)) {
                  container.style.display = 'none';
                }
              });

          function openPostMenu() {
            var x = document.getElementById("post_selection_menu");
              if (x.style.display === "block") {
                x.style.display = "none";
              } else {
                x.style.display = "block";
              }
            }
        </script>
        <!-- Script for account selection menu ----------->
        <script type="text/javascript">
          document.addEventListener('mouseup', function(e) {
            var container = document.getElementById('account_selection_menu');
              if (!container.contains(e.target)) {
                  container.style.display = 'none';
                }
              });

          function openAccountMenu() {
            var x = document.getElementById("account_selection_menu");
              if (x.style.display === "block") {
                x.style.display = "none";
              } else {
                x.style.display = "block";
              }
            }
        </script>
      </div>
    </div>

     <!-- Notifi content ------------------------------------------------------------->
     <div class="notifi_box">

       <div class="notifi_head_holder">
         <h1 class="notifi_head">Notifications<svg class="notifi_head_svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3zm-8.27 4a2 2 0 0 1-3.46 0"></path></svg></h1>
       </div>

       <?php
        $sql = "SELECT * FROM notifications_data WHERE user_s_id = '$secure_id' AND from_id NOT LIKE '%$secure_id%' ORDER BY id DESC LIMIT 45";
          $rs = mysqli_query($dbconn, $sql);
            while($notifi_row = mysqli_fetch_array($rs)){
              $head_notifi = $notifi_row['head_txt'];
                $content_notifi = $notifi_row['content_txt'];
                $content_notifi = preg_replace("/[,]/","'",$content_notifi);
                  $status_notifi = $notifi_row['status'];
                    $date_notifi = $notifi_row['notifi_date'];
                      $link_notifi = $notifi_row['link'];
                        $from_notifi = $notifi_row['from_id'];
                          $type_notifi = $notifi_row['type'];
                            $time = strtotime("$date_notifi");
                              $time_from = humanTiming($time);
                    // get notifi author data
                    $sql_user = "SELECT * FROM users WHERE secure_id = '$from_notifi'";
                      $origin_results = mysqli_query($dbconn, $sql_user);
                        $origin_data = mysqli_fetch_assoc($origin_results);
                          $origin_avatar = $origin_data['avatar'];
                            $origin_url = $origin_data['user_url'];
                              $sql = "UPDATE notifications_data SET status='old' WHERE user_s_id='$secure_id'";
                                $results = mysqli_query($dbconn, $sql);

                          if ($status_notifi == "new") {
                            $status = "new";
                          }else {
                            $status = "old";
                          }

                          echo "<div class='notifi_holder'>";
                          if ($type_notifi == "like") {
                            echo "<svg class='notifi_type_icon' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='#f06a1d' stroke-width='2.9' stroke-linecap='square' stroke-linejoin='arcs'><path d=\"M8.5 14.5A2.5 2.5 0 0011 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 11-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 002.5 2.5z\"></path></svg>";
                          }
                          if ($type_notifi == "boost") {
                            echo "<svg class='notifi_type_icon' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='#7b03fc' stroke-width='2.9' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='10'/><path d='M16 12l-4-4-4 4M12 16V9'/></svg>";
                          }
                          if ($type_notifi == "comment") {
                            echo "<svg class='notifi_type_icon' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='#009605' stroke-width='2.9' stroke-linecap='round' stroke-linejoin='arcs'><path d='M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z'></path></svg>";
                          }
                          if ($type_notifi == "follow") {
                            echo "<svg class='notifi_type_icon' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='#1d95f0' stroke-width='2.9' stroke-linecap='round' stroke-linejoin='round'><path d='M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2'></path><circle cx='8.5' cy='7' r='4'></circle><line x1='20' y1='8' x2='20' y2='14'></line><line x1='23' y1='11' x2='17' y2='11'></line></svg>";
                          }
                          if ($type_notifi == "") {
                            echo "<svg class='notifi_type_icon' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='#5877FF' stroke-width='2.9' stroke-linecap='round' stroke-linejoin='round'><path d='M21.5 12H16c-.7 2-2 3-4 3s-3.3-1-4-3H2.5'/><path d='M5.5 5.1L2 12v6c0 1.1.9 2 2 2h16a2 2 0 002-2v-6l-3.4-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.8 1.1z'/></svg>";
                          }
                          echo "<a onclick=\"window.open(this.href,'_blank');return false;\" href='../c/$origin_url'><img alt='avatar' class='origin_avatar' src='profile/$origin_avatar' onerror=\"this.onerror=null; this.src='img/speecher_bot.jpg'\" ></img></a>";
                          echo "<p onclick=\"window.location.href='$link_notifi'\" class='head_notifi $status'><b>$head_notifi</b> • $time_from ago</p>";
                          echo "<p onclick=\"window.location.href='$link_notifi'\" class='content_notifi $status'>$content_notifi</p>";
                          echo "</div>";

          }


        ?>
     </div>
     <!-- Scripts ------------------------------------------------------------------->
     <?php
     function humanTiming ($time){
       $time = time() - $time; // time since that moment
        $time = ($time<1)? 1 : $time;
          $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'min',
            1 => 'sec');

            foreach ($tokens as $unit => $text) {
              if ($time < $unit) continue;
                $numberOfUnits = floor($time / $unit);
                  return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
            }
     }?>


  </body>
</html>
