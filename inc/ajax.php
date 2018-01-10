<?php require($_SERVER['DOCUMENT_ROOT'] . "/telekas/inc/functions.php"); ?>

<?php

  if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if($_GET['allevents']) {
      $Events->getAllEvents();
    }

    if($_GET['addevent']) {
      $Events->insertEvent($_GET['name'], $_GET['place'], $_GET['class'], $_GET['start'], $_GET['end'], $_GET['day'], $_GET['month'], $_GET['year']);
    }

    if($_GET['updateevent']) {
      $Events->updateEvent($_GET['updateevent'], $_GET['name'], $_GET['place'], $_GET['class'], $_GET['start'], $_GET['end'], $_GET['day'], $_GET['month'], $_GET['year']);
    }

    if($_GET['eventdata']) {
      $Events->getEventData($_GET['eventdata']);
    }

    if($_GET['deleteevent']) {
      $Events->deleteEvent($_GET['deleteevent']);
    }

    if($_GET['allpictures']) {
      $Picture->getPictures();
    }

    if($_GET['deletepicture']) {
      $Picture->deletepicture($_GET['deletepicture']);
    }

    if($_GET['deleteallpictures']) {
      $Picture->deleteAllPictures();
    }


  }

 ?>
