<?php
class Events {

    private $connection;

    function __construct($mysqli){
        $this->connection = $mysqli;

    }

    function insertEvent($name, $place, $class, $starttime, $endtime, $day, $month, $year) {
      $startdate = $year . "-" . $month . "-" . $day . " " . $starttime;
      $enddate = $year . "-" . $month . "-" . $day . " " . $endtime;
      $colors = ["#c52c66", "#c52c2c", "#a8c52c", "#45c52c", "#2cc5a7", "#2c80c5", "#2d2cc5", "#732cc5", "#de7a0f", "#f5c436"];

      $stmt = $this->connection->prepare("INSERT INTO events (name, place, class, starttime, endtime, day, month, year, startdate, enddate, color, inserted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("sssssssssss", $name, $place, $class, $starttime, $endtime, $day, $month, $year, $startdate, $enddate, $colors[array_rand($colors)]);
      $stmt->execute();
      $stmt->close();
    }

    function deleteEvent($id) {
      $stmt = $this->connection->prepare("UPDATE events SET deleted = NOW() WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->close();
    }

    function updateEvent($id, $name, $place, $class, $starttime, $endtime, $day, $month, $year) {
      $startdate = $year . "-" . $month . "-" . $day . " " . $starttime;
      $enddate = $year . "-" . $month . "-" . $day . " " . $endtime;

      $stmt = $this->connection->prepare("UPDATE events SET name = ?, place = ?, class = ?, starttime = ?, endtime = ?, day = ?, month = ?, year = ?, startdate = ?, enddate = ? WHERE id = ?");
      $stmt->bind_param("ssssssssssi", $name, $place, $class, $starttime, $endtime, $day, $month, $year, $startdate, $enddate, $id);
      $stmt->execute();
      $stmt->close();

    }

    function getAllEvents() {
      //Järjestama datetime järgi, kell väiksem kui praegune kell
      //mõtekas teha kaks tulpa juurde, algus ja lõpp datetime, sellejärgi lihtne võrrelda nowiga praegust date
      $stmt = $this->connection->prepare("SELECT * FROM events WHERE deleted IS NULL AND enddate > NOW() ORDER BY startdate LIMIT 7");
      $stmt->bind_result($id, $name, $place, $class, $start, $end, $day, $month, $year, $startdate, $enddate, $color, $inserted, $deleted);
      $stmt->execute();

      $array = array();

      while($stmt->fetch()) {
        $a = new stdClass();
        $a->id = $id;
        $a->name = $name;
        $a->place = $place;
        $a->class = $class;
        $a->start = $start;
        $a->end = $end;
        $a->day = $day;
        $a->month = $month;
        $a->year = $year;
        $a->color = $color;
        $a->inserted = $inserted;
        $a->deleted = $deleted;

        array_push($array, $a);

      }

      echo json_encode($array);

      $stmt->close();
    }

    function getEventData($did) {
      $stmt = $this->connection->prepare("SELECT * FROM events WHERE deleted IS NULL AND id = ?");
      $stmt->bind_param("i", $did);
      $stmt->bind_result($id, $name, $place, $class, $start, $end, $day, $month, $year, $startdate, $enddate, $color, $inserted, $deleted);
      $stmt->execute();

      if($stmt->fetch()) {
        $a = new stdClass();
        $a->id = $id;
        $a->name = $name;
        $a->place = $place;
        $a->class = $class;
        $a->start = $start;
        $a->end = $end;
        $a->day = $day;
        $a->month = $month;
        $a->year = $year;
        $a->inserted = $inserted;
        $a->deleted = $deleted;
      }

      echo json_encode($a);

      $stmt->close();
    }



  }

 ?>
