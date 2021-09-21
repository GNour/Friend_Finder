<?php

require_once "../base/User.php";
require_once "../base/Notification.php";
require_once "../config/connection.php";

session_start();
$userId = $_SESSION["user"]->getId();
if ($_GET["action"] == "querySearch") {

    $query = $_GET["search"];
    $searchQuery = $connection->query("SELECT *\n"

        . "FROM user as u\n"

        . "WHERE \n"

        . "(u.first_name LIKE \"" . $query . "%\" OR u.last_name LIKE \"" . $query . "%\")\n"

        . "AND u.id NOT IN(SELECT friend_id FROM user_friend_list WHERE user_id = " . $userId . ")\n"

        . "AND u.id NOT IN(SELECT user_id FROM user_friend_list WHERE friend_id = " . $userId . ")\n"

        . "AND u.id NOT IN(SELECT friend_id FROM user_block_list WHERE user_id = " . $userId . ")\n"

        . "AND u.id NOT IN(SELECT user_id FROM user_block_list WHERE friend_id = " . $userId . ")\n"

        . "AND u.id NOT IN(SELECT to_user FROM notification WHERE to_user = " . $userId . " OR from_user = " . $userId . " AND response = -1)\n"

        . "AND u.id != " . $userId);

    $result = array();

    while ($row = $searchQuery->fetch_assoc()) {
        $temp["id"] = $row["id"];
        $temp["first_name"] = $row["first_name"];
        $temp["last_name"] = $row["last_name"];
        $temp["city"] = $row["city"];
        $temp["country"] = $row["country"];
        $temp["profile_image"] = $row["profile_image"];

        $result[$row["id"]] = $temp;
    }

    echo json_encode($result);
}

if ($_GET["action"] == "block") {
    if ($_SESSION["user"]->blockUser($_GET["id"])) {
        echo json_encode(array("ok" => 200, "message" => "Blocked User"));
    }
}

if ($_GET["action"] == "createRequest") {
    echo json_encode(Notification::createRequest($userId, $_GET["id"], $_GET["date"]));
}
