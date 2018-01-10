<?php
class User {

    private $connection;

    function __construct($mysqli){
        $this->connection = $mysqli;
    }

    function login($usern, $pass) {
      $stmt = $this->connection->prepare("SELECT id FROM users WHERE user = ? AND password = ?");
      $stmt->bind_param("ss", $usern, $pass);
      $stmt->bind_result($id);
      $stmt->execute();

      if($stmt->fetch()) {
        $_SESSION['user_session'] = "koikok";
        header("Location: index.php");
      }

      $stmt->close();

      if($_SESSION['user_session'] === "koikok") {
      } else {
      header("Location: login.php");
      }

    }



  }

 ?>
