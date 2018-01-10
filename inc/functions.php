<?php
$realurl = "/telekas";

session_start();

include($_SERVER['DOCUMENT_ROOT'] . $realurl . "/config.php");
include($_SERVER['DOCUMENT_ROOT'] . $realurl . "/inc/events.class.php");
include($_SERVER['DOCUMENT_ROOT'] . $realurl . "/inc/picture.class.php");
include($_SERVER['DOCUMENT_ROOT'] . $realurl . "/inc/user.class.php");

$database = "d69215_teler";

$mysqli = new mysqli($servername, $server_username, $server_password, $database);

$Events = new Events($mysqli);
$Picture = new Picture($mysqli);
$User = new User($mysqli);
?>
