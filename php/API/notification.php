<?php
require_once "../base/User.php";
require_once "../base/Notification.php";
session_start();

if ($_GET["action"] == "get") {
    echo json_encode(Notification::getUserNotifications($_SESSION["user"]->getId()), JSON_PRETTY_PRINT);
}

if ($_GET["action"] == "accept") {
    echo json_encode(Notification::acceptRequest($_GET["id"], $_GET["from"]));
}
if ($_GET["action"] == "decline") {
    echo json_encode(Notification::declineRequest($_GET["id"]));
}

if ($_GET["action"] == "block") {
    echo json_encode(Notification::declineRequestAndBlockUser($_GET["id"], $_GET["from"]));
}
