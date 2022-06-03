   <!-- function for user's new notifications ---->
   <?php
    $sql = "SELECT * FROM --- WHERE --- = '$secure_id' AND --- = 'new'";
     $rs = mysqli_query($dbconn, $sql);
     //get row
     $fetchRow = mysqli_fetch_assoc($rs);
     $notification_status = $fetchRow['---'];

      if ($notification_status == 'new') {
        $notifi_red_dot_style = 'block';
        }else {
          $notifi_red_dot_style = 'none';
        }
        //get user data
        $sql = "SELECT * FROM --- WHERE --- = '$secure_id'";
        $rs = mysqli_query($dbconn, $sql);
        //get
        $fetchRow = mysqli_fetch_assoc($rs);
        $avatar = $fetchRow['---'];


   ?>

   <!-- notifiaction html element in navigation panel (header) ----->
   <div class="nav_div">
    <button onclick="window.location.href='notifications'" class="nav_btn" type="button" name="button">
     <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3zm-8.27 4a2 2 0 0 1-3.46 0"></path></svg>
     <svg class='notifi_red_dot' style="display:<?=$notifi_red_dot_style?>;" height="10" width="10"> <circle cx="5" cy="5" r="3" stroke="red" stroke-width="3" fill="red"/></svg>
    </button>
    <button onclick="window.location.href='explore'" class="nav_btn" type="button" name="button">
     <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><circle cx="12" cy="12" r="10"/><path d="M16.2 7.8l-2 6.3-6.4 2.1 2-6.3z"/></svg>
    </button>
    <button onclick="openPostMenu()" class="nav_btn" type="button" name="button">
     <svg class="header_svg_act" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.7" stroke-linecap="square" stroke-linejoin="arcs"><circle cx="12" cy="12" r="10"></circle>
     <line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
    </button>
   </div>
