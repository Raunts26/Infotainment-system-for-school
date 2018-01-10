<?php
class Picture {

    private $connection;

    function __construct($mysqli){
        $this->connection = $mysqli;
    }

    function insertPicture($url, $fileType, $isVideo, $duration) {
      $stmt = $this->connection->prepare("INSERT INTO images (url, type, video, duration, inserted) VALUES (?, ?, ?, ?, NOW())");
      $stmt->bind_param("ssss", $url, $fileType, $isVideo, $duration);
      $stmt->execute();
      $stmt->close();
    }

    function getPictures() {
      $stmt = $this->connection->prepare("SELECT * FROM images WHERE deleted IS NULL");
      $stmt->bind_result($id, $url, $type, $isVideo, $duration, $inserted, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $a = new stdClass();
        $a->id = $id;
        $a->url = $url;
        $a->type = $type;
        $a->isVideo = $isVideo;
        $a->duration = $duration;
        $a->inserted = $inserted;

        array_push($array, $a);
      }

      echo json_encode($array);

      $stmt->close();
    }

    function deletePicture($id) {
      $stmt = $this->connection->prepare("UPDATE images SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $id);
      if($stmt->execute()) {
        foreach($this->getPictures() as $pic) {
          if((int)$pic->id === (int)$id) {
            var_dump($pic->url);
            unlink($pic->url);
          }
        }
      }
      $stmt->close();
    }

    function deleteAllPictures() {
      $stmt = $this->connection->prepare("UPDATE images SET deleted = NOW() WHERE deleted IS NULL");
      if($stmt->execute()) {
        $files = glob('../images/');

        foreach($files as $file) {
          if(is_file($file))
            unlink($file);
        }

      }
      $stmt->close();

    }


  }

 ?>
