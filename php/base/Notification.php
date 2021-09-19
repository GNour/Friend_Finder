<?php

class Notification
{
    private $id;
    private $from_user;
    private $to_user;
    private $date;
    private $response;
    private $body;

    public function __construct($id, $from_user, $to_user, $date)
    {
        $this->id = $id;
        $this->from_user = $from_user;
        $this->to_user = $to_user;
        $this->date = $date;
        $this->response = -1;
        $this->body = $from_user . " Sent you a friend request";
    }

    public function acceptRequest()
    {
        include "../config/connection.php";
        include "../base/User.php";
        session_start();
        $isAdded = $_SESSION["user"]->addFriend($this->$from_user);

        if ($isAdded) {
            $stmt = $connection->query("UPDATE notification SET response = 1 WHERE id = " . $this->id);

            if ($stmt->affected_rows > 0) {
                return array("ok" => 200, "message" => "Accepted Request");
            }
        } else {
            return array("ok" => 500, "message" => "Couldn't Accept Request");
        }

    }

    public function declineRequest()
    {
        include "../config/connection.php";

        $stmt = $connection->query("UPDATE notification SET response = 0 WHERE id = " . $this->id);

        if ($stmt->affected_rows > 0) {

            return array("ok" => 200, "message" => "Declined Request");
        } else {
            return array("ok" => 500, "message" => "Couldn't Decline Request");
        }
    }

    public function declineRequestAndBlockUser()
    {
        include "../config/connection.php";
        include "../base/User.php";
        session_start();
        $isAdded = $_SESSION["user"]->blockUser($this->$from_user);

        $stmt = $connection->query("UPDATE notification SET response = 0 WHERE id = " . $this->id);

        if ($stmt->affected_rows > 0) {

            return array("ok" => 200, "message" => "Declined Request");
        } else {
            return array("ok" => 500, "message" => "Couldn't Decline Request");
        }
    }

    public static function createRequest($from, $to, $date)
    {
        include "../config/connection.php";

        $stmt = $connection->prepare("INSERT INTO notification (`from_user`, `to_user`, `date`, `response`, `body`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisis", $from, $to, $date, -1, "Sent you a friend request");
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return array("ok" => 200, "message" => "Sent Request");
        } else {
            return array("ok" => 500, "message" => "Couldn't Send Request");
        }
    }
}
