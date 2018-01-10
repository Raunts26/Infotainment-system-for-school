<?php
require_once("functions.php");
$ffmpeg = "../plugins/ffmpeg-3.4";

$total = count($_FILES['my_files']['name']);

$target_dir = "../images/";

for($i = 0; $i < $total; $i++) {

  $target_file = $target_dir . basename($_FILES["my_files"]["name"][$i]);
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $isVideo = "false";
  $durations = 0;
  //var_dump($target_file);

  // Pole mõtet enablida, äkki soovitakse sama pilt teist korda panna telekasse, panimajes? Kui siiski tahad nii kuri olla, palun väga
  /*if (file_exists($target_file)) {
    $_SESSION['error_msg'] =  "Fail juba eksisteerib.";
    $uploadOk = 0;
  }*/

  //Faili laiendi kontroll
  if($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png" && $fileType != "mp4" && $fileType != "mpeg4" && $fileType != "wmv") {
      $_SESSION['error_msg'] =  "Ainult jpg, jpeg, png, mp4, mpeg4, wmv failid on lubatud. Räägi IT juhiga, et lisada formaate juurde.";
      $uploadOk = 0;
  }

  if($fileType == "mp4" || $fileType == "mpeg4" || $fileType == "wmv") { // KUI ON TEGEMIST VIDEOGA - SIIA LOETLE AINULT VIDEO FOMAADID
    $isVideo = "true"; // No touchy
    $time =  exec("$ffmpeg -i " . $_FILES['my_files'][$i] . " 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");
    $duration = explode(":",$time);
    var_dump($duration);
    var_dump($time);
    $durations = $duration[0] * 3600 + $duration[1] * 60 + round($duration[2]);
    var_dump($durations);
  }

  if ($_FILES["my_files"]["size"][$i] > 100000000) {
    $_SESSION['error_msg'] =  "Fail liiga suur. Räägi IT juhiga, et suurendada limiiti.";
    $uploadOk = 0;
  }

  if ($uploadOk == 0) {
    $_SESSION['error_msg'] = "Faili ei laetud üles!";
  } else {
    if (move_uploaded_file($_FILES["my_files"]["tmp_name"][$i], $target_file)) {
      $_SESSION['success_msg'] = "Fail ". basename( $_FILES["my_files"]["name"][$i]). " on üles laetud.<br>";
      $Picture->insertPicture($target_file, $fileType, $isVideo, $durations);
    } else {
      $_SESSION['error_msg'] = "Tekkis tundmatu viga, anna teada haldajale!";
    }
  }

}

$_SESSION['msg_seen'] = false;
//header("Location: ../admin");




?>
