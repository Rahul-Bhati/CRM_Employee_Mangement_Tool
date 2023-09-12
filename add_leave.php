<?php
include("db.php");
if (!isset($_COOKIE["user"])) {
     header("location:index.php");
     die();
} else {
     if (empty($_POST["emp_id"]) || empty($_POST["from_date"]) || empty($_POST["to_date"]) || empty($_POST["pass"]) || empty($_POST["days"]) || empty($_POST["reason"])) {
          header("location:send_leaves.php?empty=1");
          die();
     } else {
          $emp_id = mysqli_real_escape_string($conn, $_POST["emp_id"]);
          $pass = mysqli_real_escape_string($conn, $_POST["pass"]);
          $from_date = mysqli_real_escape_string($conn, $_POST["from_date"]);
          $to_date = mysqli_real_escape_string($conn, $_POST["to_date"]);
          $days = mysqli_real_escape_string($conn, $_POST["days"]);
          $reason = mysqli_real_escape_string($conn, $_POST["reason"]);

          $code = "";
          $sn = 0;

          $rs = mysqli_query($conn, "SELECT MAX(sn) FROM `leave_req`");
          if ($r = mysqli_fetch_array($rs)) {
               $sn = $r[0];
          }
          $sn++;

          $a = array();
          for ($i = 'A'; $i <= 'Z'; $i++) {
               array_push($a, $i);
               if ($i = 'Z')
                    break;
          }
          for ($i = 'a'; $i <= 'z'; $i++) {
               array_push($a, $i);
               if ($i = 'z')
                    break;
          }
          for ($i = 0; $i <= 9; $i++) {
               array_push($a, $i);
          }
          $b = array_rand($a, 5);
          for ($i = 0; $i < sizeof($b); $i++) {
               $code = $code . $a[$b[$i]];
          }

          $code = $code . "_" . $sn;

          date_default_timezone_set('Asia/Calcutta');
          $date = date('m/d/Y h:i:s a', time());


          $rk = mysqli_query($conn, "select * from user where code='$emp_id' and pass='$pass'");
          if ($r1 = mysqli_fetch_array($rk)) {

               // $sql = "INSERT INTO `leave_req`(`sn`, `code`, `emp_id`, `from_date`, `to_date`, `days`, `reason`, `date`, `status`) VALUES ('2','dssdf','wdfsd','23-32-2000','33-32-3312','2','sdfas','$date',0))";

               if (mysqli_query($conn, "insert into leave_req values($sn,'$code','$emp_id','$from_date','$to_date',$days,'$reason','$date',0)") > 0) {
                    header("location:send_leaves.php?success=1");
                    die();
               } else {
                    header("location:send_leaves.php?error=1");
                    // echo "error<br>";
                    // echo $sn . " " . $code . " " . $pass . " " . $emp_id . " " . $from_date . " " . $to_date . " " . $days . " " . $date . " " . $reason;
                    die();
               }
          }
     }
}
