<?php
session_start();
  $logged_user_secure_id = $_SESSION["secure_id"];
  if ($logged_user_secure_id == "") {
    $login_redirect = "onclick=\"window.location.href='../login'\"";
  }

$parts = parse_url($url);
parse_str($parts['query'], $query);
$content_url = $_GET['c'];

      // get data from post, get how many views are there
      $sql = "SELECT views FROM post_data WHERE id='$content_url'";
        $results = mysqli_query($dbconn, $sql);
          $current_views = mysqli_fetch_assoc($results)['views'];
            $sum = array($current_views,'1');
              $plus_view = array_sum($sum);
                // update post_data with plus view
                $sql = "UPDATE post_data SET views='$plus_view' WHERE id='$content_url'";
                  $results = mysqli_query($dbconn, $sql);

                    // function for user"s new notifications
                      $sql = "SELECT * FROM notifications_data WHERE user_s_id = '$logged_user_secure_id' AND status = 'new'";
                        $rs = mysqli_query($dbconn, $sql);
                          //get row
                            $fetchRow = mysqli_fetch_assoc($rs);
                              $notification_status = $fetchRow["status"];

                                if ($notification_status == "new") {
                                  $notifi_red_dot_style = "block";
                                    }else {
                                      $notifi_red_dot_style = "none";
                                    }

                                    //get user data
                                    $sql = "SELECT * FROM users WHERE secure_id = '$logged_user_secure_id'";
                                      $rs = mysqli_query($dbconn, $sql);
                                        //get row
                                          $fetchRow = mysqli_fetch_assoc($rs);
                                            $this_profile_avatar = $fetchRow["avatar"];
                                                $current_user = $fetchRow["uname"];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<!-- Post content data ------------------------------------------>
<?php
 $query = "SELECT * FROM post_data WHERE id='$content_url'";
 $result = mysqli_query($dbconn, $query);
 $post_data = mysqli_fetch_assoc($result);
  // Post data list from db
     $post_id = $post_data["id"];
        $content_raw = $post_data["content"];
          $content_title = mb_substr($content_raw, 0, 150, "UTF-8");
           // Detect urls
              $take_url = strip_tags($content_raw);
                $content_with_links = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" rel="nofollow">$1</a>', $take_url);
                  $content_with_hashtags = preg_replace('/#(\w+)/', '<a href="https://ripledd.com/search?term=$1" target="_blank" rel="nofollow">#$1</a>', $content_with_links);
                    $content = preg_replace('/@(\w+)/', '<a style="background:#d7dffc;" href="https://ripledd.com/user/$1" target="_blank" rel="nofollow">@$1</a>', $content_with_hashtags);

                   $c_type_raw = $post_data["c_type"];
                     if ($c_type_raw != ""){$c_type = "<span class='c_type'> $c_type_raw </span> ";}
                     $poster = $post_data["poster"];
                       $posted_by_id = $post_data["user_id"];
                         $post_date = $post_data["post_date"];
                           $post_date_two = $post_data["post_date_two"];
                             $likes = $post_data["likes"];
                               $views = $post_data["views"];
                                 $comments = $post_data["comments"];
                                   $time = strtotime("$post_date_two");
                                     $time_from = humanTiming($time);
                                       $file = $post_data["file"];
                                        $file_type_raw = pathinfo($file, PATHINFO_EXTENSION);
                                        $file_type = strtolower($file_type_raw);
                                          // User data list from db
                                           $sql = "SELECT * FROM users WHERE secure_id = '$posted_by_id'";
                                             $rs = mysqli_query($dbconn, $sql);
                                               $fetchRow = mysqli_fetch_assoc($rs);
                                                $posted_by = $fetchRow['uname'];
                                                  $secure_id = $fetchRow["secure_id"];
                                                    $avatar = $fetchRow["avatar"];
                                                      $u_url = $fetchRow["user_url"];
                                                        $status = $fetchRow['status'];
                                                        // Likes data list from db
                                                          $sql = "SELECT * FROM likes_data WHERE post_id = '$post_id' AND user='$logged_user_secure_id'";
                                                            $rs = mysqli_query($dbconn, $sql);
                                                              $fetchRow = mysqli_fetch_assoc($rs);
                                                                $user_liked_post = $fetchRow["user"];

                                             // Post if conditions ------------>
                                             if ($post_id == '') {
                                               header("location: ../error404");
                                             }
                                             if ($secure_id == "$logged_user_secure_id") {
                                               $content_owner = 'block';
                                             }else {
                                               $content_owner = 'none';
                                             }

                                             if ($poster != "") {
                                               $c_poster = "poster='../$poster'";
                                             }

                                             if ($file == "null") {
                                                $padding_style = 'padding-bottom: 30px';
                                             }else{
                                                $padding_style = 'padding-bottom: 1px';
                                             }

                                             if ($user_liked_post == "") {
                                               $like_status = 'none';
                                               $like_color = 'black';
                                               echo "
                                               <script>
                                                 function like() {
                                                   var x = document.getElementById(\"like_svg\");
                                                     if (x.style.stroke === \"black\") {
                                                       x.style.fill = \"url(#hot-grad)\";
                                                         x.style.stroke = \"url(#hot-grad)\";
                                                           } else {
                                                             x.style.fill = \"none\";
                                                               x.style.stroke = \"black\";
                                                   }
                                                 }
                                               </script>
                                               ";
                                             }else {
                                               $like_status = "url(#hot-grad)";
                                               $like_color = "url(#hot-grad)";
                                               echo "
                                               <script>
                                                 function like() {
                                                   var x = document.getElementById(\"like_svg\");
                                                     if (x.style.fill === \"none\") {
                                                       x.style.fill = \"url(#hot-grad)\";
                                                         x.style.stroke = \"url(#hot-grad)\";
                                                           } else {
                                                             x.style.fill = \"none\";
                                                               x.style.stroke = \"black\";
                                                   }
                                                 }
                                               </script>
                                               ";
                                             }

                                             if ($file_type != 'mp3' || $file_type != 'wav' || $file_type != 'flac' || $file_type != 'mp4' || $file_type != 'mov' || $file_type != 'avi' || $file_type != 'mpeg'){
                                               $type = 'content';
                                               $img_visibility = 'block';
                                             }

                                             if ($file_type == "mp4" || $file_type == "mov" || $file_type == "avi" || $file_type == "mpeg"){
                                               $video_visibility = "block";
                                               $img_visibility = 'none';
                                               echo "<script src=\"../js/video.min.js\"></script>";
                                             }else {
                                               $video_visibility = "none";
                                             }

                                             if ($file_type == "mp3" || $file_type == "wav" || $file_type == "flac" || $file_type == 'm4a' || $file_type == 'aac'){
                                               $audio_visibility = "block";
                                               $img_visibility = 'none';
                                               echo "<script src=\"../js/audio.min.js\"></script>";
                                             }else {
                                               $audio_visibility = "none";
                                             }

                                             if ($file_type == '') {
                                               echo "<style> #post_content$post_id{padding-top:30px;} </style>";
                                             }

                                             if ($c_type_raw != "") {
                                               $type = $c_type_raw;
                                             }else {
                                               $type = "content";
                                             }
                                             ?>
  <head>
    <meta charset="utf-8">
    <title><?=$content_title?></title>
    <meta name="description" content="By:<?=$posted_by?>, <?=$content_title?>, Created:<?=$post_date_two?>, Likes:<?=$likes?>, Views:<?=$views?>, Comments:<?=$comments?>, View this content in Ripledd!">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/post.css">
    <link rel="stylesheet" href="../css/videoplayer.css">
    <link rel="stylesheet" href="../css/audioplayer.css">
    <link rel="stylesheet" href="../css_mobile/m-header.css">
    <link rel="stylesheet" href="../css_mobile/m-post.css">
    <link rel="stylesheet" href="../css_mobile/m-videoplayer.css">
    <link rel="stylesheet" href="../css_mobile/m-audioplayer.css">
    <link rel="stylesheet" href="../css/dark.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
  <!-- HEADER ------------------------------------------------------------------------------------------------------>
    <div class="header">
      <div class="header_content_holder">
        <div class="m_elements_h">
          <!-- Elements for mobile START ---------------------------------------------------------------------->
          <picture><source srcset="../img/m_logo_col.png" media="(prefers-color-scheme: dark)"><img class="logo_main_m" onclick="window.location.href='../'" src="../img/m_logo.png" alt="ripledd_logo"></picture>
          <?php if ($logged_user_secure_id == ""):echo "<style>.my_profile_bar_m{display:none;}  </style>";?>
	        <?php elseif ($logged_user_secure_id != ""):echo "<style>.my_profile_bar_m{display:block;}  </style>";?>
          <?php endif; ?>
          <img onclick="openAccountMenu()" class="my_profile_bar_m" src="../profile/<?=$avatar?>" alt="">
        </div>
        <!-- Elements for mobile END ---------------------------------------------------------------------->
        <picture><source srcset="../img/ripledd_logo_light.png" media="(prefers-color-scheme: dark)"><img class="logo_main" onclick="window.location.href='../'" src="../img/ripledd_logo.png" alt="ripledd_logo"></picture>
          <form class="search_box" action="../search.php" method="post">
            <input id="live_search" class="search_input" type="text" autocomplete="off" placeholder="Find content..."  name="input_value" value="">

            <!-- Scripts for search ---------------------------------------------------------------------->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script type="text/javascript">
            //get live data _________________________________________________________________________
            $(document).ready(function () {$("#live_search").keyup(function () {var query = $(this).val();if (query != "") {$.ajax({url: '../task/ajax-live-search.php',method: 'POST',data: {query: query},
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

        <?php if ($logged_user_secure_id == ""):
                echo "<style>
                .nav_div{display:none;}
                .my_account{display:none;}
                </style>";?>
        <?php else: echo "<style>
        .join_us_holder{display:none;}
        </style>";?>
        <?php endif; ?>

        <div class="nav_div">
          <button onclick="window.location.href='../notifications'" class="nav_btn" type="button" name="button">
            <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3zm-8.27 4a2 2 0 0 1-3.46 0"></path></svg>
            <svg class='notifi_red_dot' style="display:<?=$notifi_red_dot_style?>;" height="10" width="10"> <circle cx="5" cy="5" r="3" stroke="red" stroke-width="3" fill="red"/></svg>
          </button>
          <button  onclick="window.location.href='../'" class="nav_btn" type="button" name="button">
            <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          </button>
          <button onclick="openPostMenu()" class="nav_btn" type="button" name="button">
            <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
          </button>
        </div>

        <div id="post_selection_menu" class="add_post_menu">
          <button onclick="window.location.href='../create'" class="add_post_menu_btn" type="button" ><span class="add_post_menu_btn_txt" >Create new post</span>
            <svg class="svg_add_post_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"></polygon></svg>
          </button>
          <br>
          <button onclick="window.location.href='../account/manage?add_status'" class="add_post_menu_btn" type="button" ><span class="add_post_menu_btn_txt">Update status</span>
            <svg class="svg_add_post_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><path d="M5.52 19c.64-2.2 1.84-3 3.22-3h6.52c1.38 0 2.58.8 3.22 3"/><circle cx="12" cy="10" r="3"/><circle cx="12" cy="12" r="10"/></svg>
          </button>
        </div>

        <div id="account_selection_menu" class="account_menu">
          <button onclick="window.location.href='../edit'" class="account_menu_btn" type="button" ><span class="account_menu_btn_txt" >Edit account</span>
            <svg class="svg_account_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="14 2 18 6 7 17 3 17 3 13 14 2"></polygon><line x1="3" y1="22" x2="21" y2="22"></line></svg>
          </button>
          <br>
          <button onclick="window.location.href='../saved'" class="account_menu_btn" type="button" ><span class="account_menu_btn_txt">Liked content</span>
            <svg class="svg_account_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
          </button>
          <br>
          <button onclick="window.location.href='../comments'" class="account_menu_btn" type="button" ><span class="account_menu_btn_txt">My comments</span>
            <svg class="svg_account_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
          </button>
          <br>
          <button onclick="window.location.href='../logout.php'" class="account_menu_btn" type="submit" ><span class="account_menu_btn_txt">Logout</span>
            <svg class="svg_account_menu" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line>
            </svg>
          </button>
        </div>

        <div class="join_us_holder">
          <button onclick="window.location.href='../login'" class="login_btn" type="button" name="button">Log In</button>
          <button onclick="window.location.href='../signup'" class="signup_btn" type="button" name="button">Sign Up</button>
        </div>


        <div class="my_account">
          <img onclick="openAccountMenu()" class="my_profile_bar" src="../profile/<?=$this_profile_avatar?>" alt="">
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

    <div class="page_content">
      <div class="post">
       	<p style="display:none;" class="comment_msg" id="comment_del_msg"> Comment has been removed! <a href='<?=$content_url?>'>Refresh to see changes</a> </p>

          <!-- Post viewBox  -------------------------------------------------------------------->
             <div class="post_box">
               <div style="<?=$padding_style?>" class="upper_bar">
             <!-- Post actions menu --------------->
               <div>
                 <button onclick='openContentMenu()' class='open_content_menu_btn'>
                   <svg class='svg_open_content_menu' width='23' height='23' viewBox='0 0 24 24' fill='black' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><circle cx='12' cy='12' r='1'></circle><circle cx='12' cy='5' r='1'></circle><circle cx='12' cy='19' r='1'></circle></svg>
                 </button>
                  <div id='content_selection_menu' class='content_menu'>
                    <button onclick='openShareMenu()' class='content_menu_btn' type='button' ><span class='post_menu_btn_txt' >Share</span>
                      <svg class='svg_content_menu' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'><circle cx='18' cy='5' r='3'></circle><circle cx='6' cy='12' r='3'></circle><circle cx='18' cy='19' r='3'></circle><line x1='8.59' y1='13.51' x2='15.42' y2='17.49'></line><line x1='15.41' y1='6.51' x2='8.59' y2='10.49'></line></svg>
                    </button>
                    <br>
                    <a onclick="window.open(this.href,'_blank');return false;" href='../report?https://ripledd.com/content/<?=$post_id?>'>
                    <button class='content_menu_btn' type='button' ><span class='post_menu_btn_txt'>Report</span>
                      <svg class='svg_content_menu' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'><polygon points='7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2'></polygon><line x1='12' y1='8' x2='12' y2='12'></line><line x1='12' y1='16' x2='12.01' y2='16'></line></svg>
                    </button>
                    </a>
                    <br>
                    <button onclick="window.location.href='../'" style='display:<?=$content_owner?>;' id='to_del_post' class='content_menu_btn' type='submit' ><span class='post_menu_btn_txt'>Delete</span>
                      <svg class='svg_content_menu' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg>
                    </button>
                  </div>
                </div>
              <!-- Post information  -------------------------------------------------------------------->
               <img class="avatar_post" src="../profile/<?=$avatar?>" onerror="this.onerror=null;this.src='../img/default-avatar.png'"></img>
               <a href="../user/<?=$u_url?>" class="post_author"><?=$posted_by?><?php if($status == "verified"){echo "<img class='verified' src='../img/verified.png'></img>";}?></a>
               <p class="when_posted" > Posted: <?=$time_from?> ago <?=$c_type?></p>
               </div>
              <!-- Post  -------------------------------------------------------------------->
               <br>
               <img style="display:<?=$img_visibility?>;" class="post_file" src="../<?=$file?>" onerror="this.style.display='none'"> </img>
               <?php
                if ($file_type == "mp4" || $file_type == "mov" || $file_type == "avi" || $file_type == "ogg") {
                  echo "
                  <div style='display:$video_visibility;' class='player_holder'>
                    <video $c_poster playsinline class='video-js vjs-theme-default vjs-big-play-centered-video' autoplay controls preload='none' width='600' height='340' data-setup='{}' >
                      <source src='../$file'/>
                    </video>
                  </div>";
                }

                if ($file_type == 'mp3' || $file_type == 'wav' || $file_type == 'flac' || $file_type == 'm4a' || $file_type == 'aac') {
                  echo "
                  <div style='display:$audio_visibility;' class='player_holder'>
                    <img class='bg_audio_layer1' src='../$poster' onerror=\"this.onerror=null;this.src='../img/default-audio.jpg'\">
                      <div class='bg_audio_layer1_blur'></div>
                    </img>
                    <img class='bg_audio_layer2' src='../$poster' onerror=\"this.onerror=null;this.src='../img/default-audio.jpg'\"></img>
                    <audio class='audio-js vjs-theme-default vjs-audio-big-play-centered-audio' autoplay controls preload='none' width='600' height='80' data-setup='{}' >
                      <source src='../$file'/>
                    </audio>
                  </div>";
                }
               ?>
               <br>
               <?php if ($type == "content"):echo "<style>@media only screen and (max-device-width: 500px) and (max-device-height: 1000px){.post_content{margin-top: 50px;}}</style>";?>
               <?php endif; ?>
               <p id="post_content<?=$post_id?>" class="post_content"><?=$content?></p>
               <hr></hr>
               <div class="post_actions">
              <!--Like system  -------------------------------------------------------------------->
               <form <?=$login_redirect?> method="post" action="">
               <input id="post_id" name="post_id" type="text" style="display:none;" value="<?=$post_id?>" readonly class="like_button"></input>
               <input name="like_trigger" type="submit" onclick="like()" value="&nbsp&nbsp&nbsp&nbsp" id="like_button" class="like_post">
               <svg class="like_svg" id="like_svg" width="27" height="27" viewBox="0 0 24 24" style="fill:<?=$like_status?>; stroke:<?=$like_color?>;" stroke-width="1.2" stroke-linecap="square" stroke-linejoin="arcs"><path d="M8.5 14.5A2.5 2.5 0 0011 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 11-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 002.5 2.5z"></path>
                 <linearGradient id="hot-grad" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:rgb(255,0,115);stop-opacity:1" /><stop offset="100%" style="stop-color:rgb(255,157,0);stop-opacity:1" /></linearGradient>
               </svg>
               </input>
               </form>
              <!-- Actions panel -------------------------------------------------------------------->
               <p class="number_of_likes"><?=$likes?></p>
               <svg class="eye_svg" width="27" height="27" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1" stroke-linecap="square" stroke-linejoin="arcs"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
               <p class="number_of_views"><?=$views?></p>
               <input hidden type="button" value="&nbsp&nbsp&nbsp&nbsp" id="comments_button"></input>
               <svg class="comments_svg" width="27" height="27" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1" stroke-linecap="square" stroke-linejoin="arcs"><path d='M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z'></path></svg>
               <p class="number_of_comments"><?=$comments?></p>
               </div>
               <div>
              <!-- Comment system -------------------------------------------------------------------->
               <form <?=$login_redirect?> method="post" action="">
               <input style="display:none;" value="<?=$post_id?>"></input>
               <textarea id="comment_data" class="add_comment_box" onkeyup="textAreaAdjust(this)" rows="1" cols="57" placeholder="Share your thoughts about this <?=$type?>" style="overflow:hidden"></textarea>
               <input onclick="post()" id="send_comment_button" class="id_comment" type="submit" value="&nbsp&nbsp&nbsp&nbsp"></input>
               <svg class="send_icon" viewBox="0 0 20 20"><path d='M17.218,2.268L2.477,8.388C2.13,8.535,2.164,9.05,2.542,9.134L9.33,10.67l1.535,6.787c0.083,0.377,0.602,0.415,0.745,0.065l6.123-14.74C17.866,2.46,17.539,2.134,17.218,2.268 M3.92,8.641l11.772-4.89L9.535,9.909L3.92,8.641z M11.358,16.078l-1.268-5.613l6.157-6.157L11.358,16.078z'></path></svg>
               </form>
               <!-- Comment msg --------------->
               <p style="display:none;" id="posting_msg" class="posting_cmnt_msg">Posting...</p>
               <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
               <script type="text/javascript">
               function post() {
                 var x = document.getElementById("posting_msg");
                   if (x.style.display === "block") {
                     x.style.display = "none";
                   } else {
                     x.style.display = "block";
                     setTimeout(function() {
                       $('#posting_msg').fadeOut('fast');
                     }, 2500);

                   }
                 }
               </script>
               <div style="display:none; cursor:pointer;" id="rec_comment_holder" onclick="window.location.href='../comments'">
                 <img  class='rec_comment_avtr' src='../profile/<?=$this_profile_avatar?>' id='rec_comment_msg_av'></img>
                 <p class="rec_comment_info" id='rec_comment_msg_info'> By: <?=$current_user?> • just now • (+0)</p>
                 <svg class="rec_comment_svg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                 <p  class='rec_comment' id='rec_comment_msg'></p>
                 <br>
                 <br>
                 <br>
                 <br class="rec_br_mobile">
                 <br class="rec_br_mobile">
                 <br class="rec_br_mobile">
               </div>
 	             <!-- Post share --------------->
               <div style="display:none;" id="share_menu_<?=$post_id?>" class="share_c_box" >
                <p class="share_c_txt">Share content</p>
                <hr class="share_hr"></hr>
                <input class="share_input" id="c_id<?=$post_id?>" value="https://ripledd.com/content/<?=$post_id?>"></input>
                <div class="share_btn_holder">
                <input type="button" onclick="copyUrl<?=$post_id?>()" id="share_copy_btn" class="copy_link_btn<?=$post_id?>" value="Copy link"><svg class="share_svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#121212" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></input>
                <br>
                <input type="button" onclick="openEmbed<?=$post_id?>()" id="share_copy_btn" value="Embed media"><svg class="share_svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#121212" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg></input>
                <br>
                <div style="display:none;" id="embed_copy_holder<?=$post_id?>" class="embed_copy_holder">
                <textarea id="embed_area<?=$post_id?>" class="embed_area" rows="8" cols="37"><iframe width="560" height="315" src="https://ripledd.com/embed/post?p=<?=$post_id?>" scrolling="no" title="Ripledd media player" frameborder="0" allow="accelerometer; autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe></textarea>
                <input type="button" onclick="copyEmbed<?=$post_id?>()" id="embed_copy_btn" class="embed_copy_btn<?=$post_id?>" value="Copy embed code"></input>
                </div>
                <a onclick="window.open(this.href,"_blank");return false;" href="https://twitter.com/intent/tweet?text=ripledd%20content%20by%20%2F<?=$user_url?>%20in%20Ripledd.&url=https%3A%2F%2Fripledd.com%2Fcontent%2Fcontent%3Fc=<?=$post_id?>">
                <input type="button" class="share_c_btn" value="Twitter"><svg class="share_svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#121212" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg></input>
                </a>
                <br>
                <a onclick="window.open(this.href,"_blank");return false;" href="https://www.facebook.com/dialog/share?app_id=87741124305&href=https%3A%2F%2Fripledd.com%2Fcontent%2F<?=$post_id?>%26feature%3Dshare&display=popup">
                <input type="button" class="share_c_btn" value="Facebook"><svg class="share_svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#121212" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></input>
                </a>
                <br>
                <button class='share_via_c_btn' id="share-content">Share via</button>
                </div>
                <script>
                  function copyUrl<?=$post_id?>() {
                    var copyText = document.getElementById("c_id<?=$post_id?>");
                      copyText.select();
                        copyText.setSelectionRange(0, 99999)
                          document.execCommand("copy");
                            $(".copy_link_btn<?=$post_id?>").attr("value", "Copied");
                              }

                  function openEmbed<?=$post_id?>() {
                    var embed_copy_holder = document.getElementById("embed_copy_holder<?=$post_id?>");
                     if (embed_copy_holder.style.display === "block") {
                      embed_copy_holder.style.display = "none";
                      } else {
                      embed_copy_holder.style.display = "block";
                    }
                  }

                  function copyEmbed<?=$post_id?>() {
                      var copyText = document.getElementById("embed_area<?=$post_id?>");
                        copyText.select();
                          copyText.setSelectionRange(0, 99999)
                            document.execCommand("copy");
                              $(".embed_copy_btn<?=$post_id?>").attr("value", "Copied!");
                               }
                </script>
                </div>
               <!-- Open share -------------->
               <script>
                document.addEventListener('mouseup', function(e) {
                  var container = document.getElementById('share_menu_<?=$post_id?>');
                    if (!container.contains(e.target)) {
                        container.style.display = 'none';
                      }
                    });

                    function openShareMenu() {
                      var x = document.getElementById("share_menu_<?=$post_id?>");
                        if (x.style.display === "block") {
                          x.style.display = "none";
                            } else {
                              x.style.display = "block";
                            }
                          }
               </script>
               <!-- Posts comments -------------------------------------------------------------------->
               <div class="comments_holder">
                 <?php
                  $sql = "SELECT * FROM comments_data WHERE post_id = '$post_id' ORDER BY boost DESC ";
                    $rs = mysqli_query($dbconn, $sql);
                      while($comment_row = mysqli_fetch_array($rs)){
                        $comment_content = $comment_row['content'];
                          $commenter_s_id = $comment_row['user'];
                            $comment_id = $comment_row['id'];
                              $boosts = $comment_row['boost'];
                                $comment_time = $comment_row["comment_time"];
                                  $time = strtotime("$comment_time");
                                    $time_from = humanTiming($time);

                              // get comment author data
                              $sql_user = "SELECT * FROM users WHERE secure_id = '$commenter_s_id'";
                                $author_results = mysqli_query($dbconn, $sql_user);
                                  $commenter_data = mysqli_fetch_assoc($author_results);
                                    $commenter_avatar = $commenter_data['avatar'];
                                      $author = $commenter_data['uname'];
                                        $commenter_p_url = $commenter_data['user_url'];

                                          // Check how many characters in comment
                                          $comment_content = mb_substr($comment_content, 0, 270, "UTF-8");
                                            $count_comment_characters = strlen($comment_content);
                                              if ($count_comment_characters > '269') {
                                                $three_dots = "...";
                                                  $comment_content = "$comment_content $three_dots";
                                                }

                                                      // Check for replies
                                                      $sql_reply = "SELECT * FROM comment_reply WHERE location = '$comment_id'";
                                                        $reply_results = mysqli_query($dbconn, $sql_reply);
                                                          $reply_data = mysqli_fetch_assoc($reply_results);
                                                            $reply_id = $reply_data['id'];
                                                            if ($reply_id != "") {
                                                              $is_reply = "yes";
                                                            }else {
                                                              $is_reply = "no";
                                                            }


                              // Boosts data list from db
                               $sql = "SELECT * FROM who_boosted_data WHERE what_comment = '$comment_id' AND who_did='$logged_user_secure_id'";
                                  $b_rs = mysqli_query($dbconn, $sql);
                                     $boost_row = mysqli_fetch_assoc($b_rs);
                                       $user_boosted_comment = $boost_row['who_did'];
                                    // If conditions ____________________________________
                                    if ($user_boosted_comment == '') {
                                      $boost_status = 'none';
                                      $boost_color = 'black';
                                      echo "
                                      <script>
                                        function boost$comment_id() {
                                          var x = document.getElementById(\"svg$comment_id\");
                                            if (x.style.stroke === \"black\") {
                                              x.style.fill = \"url(#hot-grad)\";
                                                x.style.stroke = \"white\";
                                                  } else {
                                                    x.style.fill = \"none\";
                                                      x.style.stroke = \"black\";
                                          }
                                        }
                                      </script>
                                      ";
                                      }else {
                                      $boost_status = "url(#hot-grad)";
                                      $boost_color = "white";
                                      echo "
                                      <script>
                                        function boost$comment_id() {
                                          var x = document.getElementById(\"svg$comment_id\");
                                            if (x.style.fill === \"none\") {
                                              x.style.fill = \"url(#hot-grad)\";
                                                x.style.stroke = \"white\";
                                                  } else {
                                                    x.style.fill = \"none\";
                                                      x.style.stroke = \"black\";
                                          }
                                        }
                                      </script>
                                      ";
                                    }


                                    echo "<div id='$comment_id' class='comment_holder'>";
                                    echo "<img  onclick=\"window.location.href='../user/$commenter_p_url'\" class='author_avatar' src='../profile/$commenter_avatar'></img>";
                                    echo "<p class='comment_info'>By: <a style='text-decoration:none; color:gray;' href='../user/$commenter_p_url'>$author</a> • $time_from ago • (+$boosts) </p>";
                                    echo "<p onclick=\"window.location.href='../thread/$comment_id'\" class='comment_content'>$comment_content</p>";
                                    // Boost system
                                    echo "<form class='boost_form' $login_redirect method='post' action=''>";
                                    echo "<input style='color:$booster_color;' name='boost_trigger' type='submit' onclick=\"boost$comment_id()\" value='boost' id='boost_button' class='id$comment_id'>&nbsp&nbsp<svg class='boost_svg' id='svg$comment_id' width='23' height='23' viewBox='0 0 24 24' style='fill:$boost_status; stroke:$boost_color;' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='arcs'><circle cx='12' cy='12' r='10'></circle><path d='M16 12l-4-4-4 4M12 16V9'></path></svg></input>";
                                    echo "</form>";
                                    echo "<input type='button' onclick=\"window.location.href='../thread/$comment_id'\" value='reply' class='reply_link'><svg class='reply_svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='gray' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'><path d='M14 9l6 6-6 6'/><path d='M4 4v7a4 4 0 0 0 4 4h11'/></svg></input>";
                                    // If conditions _____
                                    if ($is_reply == "yes") {
                                      echo "<input type='button' onclick=\"window.location.href='../thread/$comment_id'\" value='view replies' class='open_link'><svg class='open_svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='gray' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'><path d='M7 17l9.2-9.2M17 17V7H7'/></svg></input>";
                                    }
                                    if ($commenter_s_id == "$logged_user_secure_id") {
                                      echo "<form class='only_cmnt_owner' method='post' action=''>";
                                      echo "<input onclick=\"remove_$comment_id()\" id='del_cmnt_btn' name='delete_trigger' type='submit' value='delete' class='del_cmnt$comment_id'><svg class='del_svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='gray' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></input>";
                                      echo "</form>";
                                    }
                                    if ($secure_id == "$logged_user_secure_id") {
                                      echo "<style>.only_cmnt_owner{display:none;}</style>";
                                      echo "<form method='post' action=''>";
                                      echo "<input onclick=\"remove_$comment_id()\" id='del_cmnt_btn' name='delete_trigger' type='submit' value='delete' class='del_all_cmnt$comment_id'><svg class='del_svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='gray' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg></input>";
                                      echo "</form>";
                                    }
                                    // hide this id  ___
                                    echo "
                                    <script>
                                    function remove_$comment_id() {
                                      var x = document.getElementById(\"$comment_id\");
                                        if (x.style.display === \"none\") {
                                          x.style.display = \"block\";
                                        } else {
                                          x.style.display = \"none\";
                                        }
                                      }
                                    </script>
                                    ";
                                    // Report system
                                    echo "</div>";
                                    // SCRIPTS in echo --------------------------------------------------------------------
                                    echo "<script src=\"../js/jquery.min.js\"></script>";
                                    // For like status submission --------------------------------------------------------------------
                                    echo "
                                    <script>
                                    $(document).ready(function() {
                                    		$('.id$comment_id').click(function(e){
                                    			e.preventDefault();
                                    				var comment_id = $comment_id;
                                    					$.ajax({
                                              	type: \"POST\",
                                    			 			url: \"boost-count.php\",
                                                data: \"comment_id=\"+comment_id,
                                    						success: function(data){
                                    				 			setTimeout(function() {
                                                  	$('.message_box').html(data);
                                              	});
                                    					}
                                    			 });
                                    		 });
                                    	 });
                                    </script>
                                    ";
                                    // For comment deletion --------------------------------------------------------------------
                                    // Only comment owner to delete >>>
                                    echo "
                                    <script>
                                    $(document).ready(function() {
                                    		$('.del_cmnt$comment_id').click(function(e){
                                    			e.preventDefault();
                                    				var del_cmnt_id = $comment_id;
                                    					$.ajax({
                                              	type: \"POST\",
                                    			 			url: \"../del_cmnt/del_cmnt.php\",
                                                data: \"del_cmnt_id=\"+del_cmnt_id,
                                    						success: function(data){
                                    				 			setTimeout(function() {
                                                  	$('.message_box').html(data);
                                              	});
                                    					}
                                    			 });
                                    		 });
                                    	 });
                                    </script>
                                    ";
                                    // If post owner, allow to delete all comments >>>
                                    echo "
                                    <script>
                                    $(document).ready(function() {
                                        $('.del_all_cmnt$comment_id').click(function(e){
                                          e.preventDefault();
                                            var del_all_cmnt_id = $comment_id;
                                              $.ajax({
                                                type: \"POST\",
                                                url: \"../del_cmnt/del_all_cmnt.php\",
                                                data: \"del_all_cmnt_id=\"+del_all_cmnt_id,
                                                success: function(data){
                                                  setTimeout(function() {
                                                    $('.message_box').html(data);
                                                });
                                              }
                                           });
                                         });
                                       });
                                    </script>
                                    ";

                    }


                  ?>
               </div>
               <br>
               <br>
               </div>
               </div>
               <br>
               <br>
               <br>

              <!-- Right side -------------------------------------------------------------------->
              <div class="right_holder">
                <div class="to_back_holder">

                </div>
                <p class="similar_c_txt">You might also like</p>
                <div class="similar_c_holder">
                  <!-- Similar content ----------------------------------->
                  <?php

                  // Detect if it is mobile or pc
                  function isMobileDevice() {
                      return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
                  }
                  if(isMobileDevice()){
                      $limit = "3";
                  }
                  else {
                      $limit = "5";
                  }

                  // Get data from suggestions db ----------------------------------->
                  $suggest_sql = "SELECT * FROM suggest_content WHERE for_user = '$secure_id '";
                    $suggest_rs = mysqli_query($dbconn, $suggest_sql);
                      $get_s_data = mysqli_fetch_assoc($suggest_rs);
                        $display_s_data = $get_s_data['from_users'];
                          $replase_s_br = preg_replace("/[.]/","'",$display_s_data);
                            $suggestion_s_rs = preg_replace("/[+]/","OR user_id=",$replase_s_br);
                            // Render content from post db by data which was taken from suggestions db ----------------------------------->
                              $c_query = "SELECT * FROM post_data WHERE user_id = '$secure_id' $suggestion_s_rs ORDER BY RAND() LIMIT $limit";
                                $c_result = mysqli_query($dbconn, $c_query);
                                  if (mysqli_num_rows($c_result) > 0){
                                      while ($sim_c_row = mysqli_fetch_array($c_result)){

                                     $post_c = $sim_c_row["content"];
                                       $sim_post_c_s = mb_substr($post_c, 0, 140, "UTF-8");
                                         $sim_count_characters = strlen($sim_post_c_s);
                                           if ($sim_count_characters > '139'){
                                             $sim_three_dots = "...";
                                               $sim_read_more = "<a>view more</a>";
                                                 $sim_post_c_s = "$sim_post_c_s $sim_three_dots $sim_read_more";}
                                                   $sim_post_l = $sim_c_row["likes"];
                                                     $sim_post_v = $sim_c_row["views"];
                                                       $sim_poster = $sim_c_row["poster"];
                                                        $sim_file = $sim_c_row["file"];
                                                          $sim_post_cmnt = $sim_c_row["comments"];
                                                            $post_pdt = $sim_c_row["post_date_two"];
                                                              $sim_post_id = $sim_c_row["id"];
                                                                $sim_post_uid = $sim_c_row["user_id"];
                                                                  $sim_time = strtotime("$post_pdt");
                                                                    $sim_time_from = humanTiming($sim_time);

                                                                    $sql = "SELECT * FROM users WHERE secure_id = '$sim_post_uid'";
                                                                    $rs = mysqli_query($dbconn, $sql);
                                                                    $fetchRow = mysqli_fetch_assoc($rs);
                                                                    $sim_post_by = $fetchRow['uname'];


                                                                    if ($post_c == "") {
                                                                      $sim_br_show = "block";
                                                                    }else {
                                                                      $sim_br_show = "none";
                                                                    }

                                                                    if ($sim_poster == "") {
                                                                      $src = "$sim_file";
                                                                      $src_ext = strtolower(pathinfo($sim_file,PATHINFO_EXTENSION));
                                                                      if ($src_ext != 'jpg' && $src_ext != 'jpeg' && $src_ext != 'ico' && $src_ext != 'png'){
                                                                        $src = "img/c_prev_default.jpg";
                                                                      }
                                                                    }else {
                                                                      $src = "$sim_poster";
                                                                    }



                                         echo "<div onclick=\"window.location.href='$sim_post_id'\" class='sug_content_box'>";
                                         echo "<img class='c_prev' src='../$src'> </img>";
                                         echo "<p class='c_by_info'>From:$sim_post_by • $sim_time_from ago</p>";
                                         echo "<p class='c_actions_info'>views: $sim_post_v • likes: $sim_post_l • comments: $sim_post_cmnt</p>";
                                         echo "<br style='display:$sim_br_show;'>";
                                         echo "<p class='p_content'>$sim_post_c_s</p>";
                                         echo "</div>";

                           }
                           echo "<p class='no_rs_msg'>Explore more content! <a href='../explore'> Explore more </a></p>";
                         }
                     ?>
                </div>
              </div>

              <!-- SCRIPTS in  -------------------------------------------------------------------->
              <script src="../js/jquery.min.js"></script>
              <script>
                      window.addEventListener('load', function() {
                        if(!navigator.share) {
                          document.querySelector('.share-container').innerHTML = 'Web Share not supported in this browser!';
                          return;
                        }
                        document.getElementById("share-content").addEventListener('click', function() {
                          navigator.share({
                            title: "Check out content by <?=$posted_by?>",
                            text: "likes:<?=$likes?> • views:<?=$views?> • comments:<?=$comments?>",
                            url: "https://ripledd.com/content/<?=$post_id?>",
                          });
                        });
                      });
              </script>
              <!--For opening content menu-------------------------------------------------------------------->
              <script>
               document.addEventListener('mouseup', function(e) {
                 var container = document.getElementById('content_selection_menu');
                   if (!container.contains(e.target)) {
                       container.style.display = 'none';
                     }
                   });

                   function openContentMenu() {
                     var x = document.getElementById("content_selection_menu");
                       if (x.style.display === "block") {
                         x.style.display = "none";
                           } else {
                             x.style.display = "block";
                           }
                         }
              </script>
              <!-- To delete post -------------------------------------------------------------------->
              <script>
              $(document).ready(function() {
              		$('#to_del_post').click(function(e){
              			e.preventDefault();
              				var del_post_id = "<?=$post_id?>";
              					$.ajax({
                        	type: "POST",
              			 			url: "../del_post/del_post.php",
                          data: "del_post_id="+del_post_id,
              						success: function(data){
              				 			setTimeout(function() {
                            	$('.message_box').html(data);
                        	});
              					}
              			 });
              		 });
              	 });
              </script>
              <!-- For like status submission -------------------------------------------------------------------->
              <script>
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
              </script>

              <!-- For comment submission -------------------------------------------------------------------->
              <script>
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
              </script>




           <?php
           function humanTiming ($time){
             $time = time() - $time; // time since that moment
              $time = ($time<1)? 1 : $time;
                $tokens = array (
                  31536000 => "year",
                  2592000 => "month",
                  604800 => "week",
                  86400 => "day",
                  3600 => "hour",
                  60 => "min",
                  1 => "sec");

                  foreach ($tokens as $unit => $text) {
                    if ($time < $unit) continue;
                      $numberOfUnits = floor($time / $unit);
                        return $numberOfUnits." ".$text.(($numberOfUnits>1)?"s":"");
                  }
           }?>


           <!-- Other scripsts ------------------------------------------------------------------->

           <script type="text/javascript">

             // For comment system --------------------------------------
             function textAreaAdjust(element) {
               element.style.height = "1px";
               element.style.height = (1+element.scrollHeight)+"px";
               }

             function eraseComment() {
               document.getElementById("comment_data").value = "";
             }

             // Hide pop-ups --------------------------------------
             if ( window.history.replaceState ) {
               window.history.replaceState( null, null, window.location.href );
             }

           </script>

      </div>
    </div>
  </body>
</html>
