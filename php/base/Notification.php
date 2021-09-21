<?php

class Notification
{
    public $id;
    public $from_user_id;
    public $from_user_first_name;
    public $from_user_last_name;
    public $from_user_image;
    public $to_user;
    public $date;
    public $response;
    public $body;

    public function __construct($id, $from_user, $from_user_first_name, $from_user_last_name, $from_user_image, $to_user, $date)
    {
        $this->id = $id;
        $this->from_user_id = $from_user;
        $this->from_user_first_name = $from_user_first_name;
        $this->from_user_last_name = $from_user_last_name;
        $this->from_user_image = $from_user_image;
        $this->to_user = $to_user;
        $this->date = $date;
        $this->response = -1;
        $this->body = $from_user_first_name . " " . $from_user_last_name . " Sent you a friend request";
    }

    public static function acceptRequest($id, $from)
    {
        include_once "../config/connection.php";
        include_once "../base/User.php";
        session_start();
        $isAdded = $_SESSION["user"]->addFriend($from);
        if ($isAdded) {
            $stmt = $connection->query("UPDATE notification SET response = 1 WHERE id = " . $id);
            if ($connection->affected_rows > 0) {
                return array("ok" => 200, "message" => "Accepted Request");
            }
        } else {
            return array("ok" => 500, "message" => "Couldn't Accept Request");
        }

    }

    public static function declineRequest($id)
    {
        include_once "../config/connection.php";

        $stmt = $connection->query("UPDATE notification SET response = 0 WHERE id = " . $id);

        if ($connection->affected_rows > 0) {
            return array("ok" => 200, "message" => "Declined Request");
        } else {
            return array("ok" => 500, "message" => "Couldn't Decline Request");
        }
    }

    public static function declineRequestAndBlockUser($id, $from)
    {
        include_once "../config/connection.php";
        include_once "../base/User.php";
        session_start();
        $isBlocked = $_SESSION["user"]->blockUser($from);

        if ($isBlocked) {
            $stmt = $connection->query("UPDATE notification SET response = 0 WHERE id = " . $id);

            if ($connection->affected_rows > 0) {

                return array("ok" => 200, "message" => "Declined Request");
            } else {
                return array("ok" => 500, "message" => "Couldn't Decline Request");
            }
        }
    }

    public static function createRequest($from, $to, $date)
    {
        include "../config/connection.php";
        if ($stmt = $connection->prepare("INSERT INTO `notification` (`from_user`, `to_user`, `date`, `response`, `body`) VALUES (?, ?, ?, -1, 'Sent you a friend request')")) {
            $stmt->bind_param("iis", $from, $to, date("Y-m-d", strtotime($date)));
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return array("ok" => 200, "message" => "Sent Request");
            } else {
                return array("ok" => 500, "message" => "Couldn't Send Request");
            }
        } else {
            echo $stmt->error;
        }

    }

    public static function getUserNotifications($userId)
    {
        include "../config/connection.php";

        $notifications = [];
        $stmt = $connection->query("SELECT n.*, u.first_name as fromFirstName, u.last_name as fromLastName, u.profile_image as profileImage FROM user as u,notification as n WHERE u.id = n.from_user AND n.to_user = " . $userId . " AND n.response = -1");
        while ($row = $stmt->fetch_assoc()) {
            $notification = new Notification($row["id"], $row["from_user"], $row["fromFirstName"], $row["fromLastName"], $row["profileImage"], $row["to_user"], $row["date"]);
            $notifications[$row["id"]] = $notification;
        }
        return $notifications;

    }
}
