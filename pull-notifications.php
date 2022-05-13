   <!-- function for user's new notifications ---->
   <?php
    $sql = "SELECT * FROM notifications_data WHERE user_s_id = '$secure_id' AND status = 'new'";
     $rs = mysqli_query($dbconn, $sql);
     //get row
     $fetchRow = mysqli_fetch_assoc($rs);
     $notification_status = $fetchRow['status'];

      if ($notification_status == 'new') {
        $notifi_red_dot_style = 'block';
        }else {
          $notifi_red_dot_style = 'none';
        }
        //get user data
        $sql = "SELECT * FROM users WHERE secure_id = '$secure_id'";
        $rs = mysqli_query($dbconn, $sql);
        //get
        $fetchRow = mysqli_fetch_assoc($rs);
        $avatar = $fetchRow['avatar'];


   ?>
