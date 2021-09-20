<?php
require_once "../base/User.php";
require_once "../base/Notification.php";
session_start();

if ($_GET["action"] == "get") {
    $result = array();
    $result["friends"] = $_SESSION["user"]->getUserFriends();
    $result["pendings"] = $_SESSION["user"]->getUserPendingFriends();
    echo json_encode($result);
}

if ($_GET["action"] == "remove") {
    echo json_encode($_SESSION["user"]->removeFriend($_GET["id"]));
}
if ($_GET["action"] == "block") {
    if ($_SESSION["user"]->blockUser($_GET["id"])) {
        echo json_encode(array("ok" => 200, "message" => "Blocked User"));
    }
}

if ($_GET["action"] == "removePending") {
    echo json_encode(Notification::declineRequest($_GET["id"]));
}

if ($_GET["action"] == "blockPending") {
    echo json_encode(Notification::declineRequestAndBlockUser($_GET["id"], $_GET["from"]));
}
