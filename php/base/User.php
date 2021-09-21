<?php

class User
{
    public $id;
    public $email;
    public $first_name;
    public $last_name;
    public $birthday;
    public $gender;
    public $city;
    public $country;
    public $profileImage;

    public function __construct($id, $email, $first_name, $last_name, $birthday, $gender, $city, $country, $profileImage)
    {
        $this->id = $id;
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->city = $city;
        $this->country = $country;
        $this->profileImage = $profileImage;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUserBlockedList()
    {
        include "../config/connection.php";

        $blockedList = [];

        $stmt = $connection->query("SELECT u.id, u.email, u.birthday, u.first_name, u.last_name, u.gender, u.city, u.country, u.profile_image FROM user as u,user_block_list as ub WHERE u.id = ub.friend_id AND ub.user_id = " . $this->id);
        while ($row = $stmt->fetch_assoc()) {
            $blocked = new User($row["id"], $row["email"], $row["first_name"], $row["last_name"], $row["birthday"], $row["gender"], $row["city"], $row["country"], $row["profile_image"]);
            $blockedList[$row["id"]] = $blocked;
        }

        return $blockedList;

    }

    public function getUserFriends()
    {
        include "../config/connection.php";

        $friends = [];

        $stmt = $connection->query("SELECT ul.id as friendId, u.id, u.email, u.birthday, u.first_name, u.last_name, u.gender, u.city, u.country, u.profile_image FROM user as u,user_friend_list as ul WHERE (u.id = ul.friend_id AND ul.user_id = " . $this->id . ") OR (u.id = ul.user_id AND ul.friend_id = " . $this->id . ")");
        while ($row = $stmt->fetch_assoc()) {
            $friend = new User($row["id"], $row["email"], $row["first_name"], $row["last_name"], $row["birthday"], $row["gender"], $row["city"], $row["country"], $row["profile_image"]);
            $friends[$row["friendId"]] = $friend;
        }

        return $friends;

    }

    public function getUserPendingFriends()
    {
        include "../config/connection.php";

        $pendings = [];

        $stmt = $connection->query("SELECT u.id, u.email, u.birthday, u.first_name, u.last_name, u.gender, u.city, u.country, u.profile_image, n.id as notificationId FROM user as u, notification as n WHERE u.id = n.to_user AND n.from_user = " . $this->id . " AND n.response = -1");
        while ($row = $stmt->fetch_assoc()) {
            $pending = new User($row["id"], $row["email"], $row["first_name"], $row["last_name"], $row["birthday"], $row["gender"], $row["city"], $row["country"], $row["profile_image"]);
            $pendings[$row["notificationId"]] = $pending;
        }

        return $pendings;

    }

    public function addFriend($id)
    {
        include "../config/connection.php";

        $stmt = $connection->prepare("INSERT INTO user_friend_list (`user_id`, `friend_id`) VALUES (?,?)");
        $stmt->bind_param("ii", $this->id, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {

            return true;
        } else {
            return false;
        }

    }

    public function removeFriend($id)
    {
        include "../config/connection.php";

        $stmt = $connection->prepare("DELETE FROM user_friend_list WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return array("ok" => 200, "message" => "Removed Friend");
        } else {
            return array("ok" => 500, "message" => "Couldn't Remove Friend");
        }

    }

    public function blockUser($id)
    {
        include "../config/connection.php";

        $stmt = $connection->prepare("INSERT INTO user_block_list (`user_id`, `friend_id`) VALUES (?,?)");
        $stmt->bind_param("ii", $this->id, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $stmt = $connection->prepare("DELETE FROM user_friend_list WHERE user_id = ? AND friend_id = ?");
            $stmt->bind_param("ii", $this->id, $id);
            $stmt->execute();
            return true;
        } else {
            return false;
        }

    }

    public function removeBlock($id)
    {
        include "../config/connection.php";

        $stmt = $connection->prepare("DELETE FROM user_block_list WHERE user_id = ? AND friend_id = ?");
        $stmt->bind_param("ii", $this->id, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return array("ok" => 200, "message" => "Removed Block");
        } else {
            return array("ok" => 500, "message" => "Couldn't Remove Block");
        }

    }

    public static function checkIfUserLoggedIn()
    {
        session_start();
        if (isset($_SESSION["user"])) {
            $user = new User($_SESSION["user"]->getId(), $_SESSION["user"]->getEmail());
            $_SESSION["user"] = $user;
            return array("ok" => 200, "message" => "Welcome back " . $_SESSION["user"]->getEmail() . " ");
        } else {
            return array("ok" => 500, "message" => "Please Login");
        }
    }

    public static function loginUser($email, $userPass)
    {
        require_once "../config/connection.php";

        $stmt = $connection->prepare("SELECT `id`,`email`,`birthday`,`first_name`,`last_name`,`gender`,`city`,`country`, `profile_image` FROM user WHERE user.email = ? AND user.password = ?");
        $stmt->bind_param("ss", $email, hash("sha256", $userPass));
        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $email, $birthday, $first_name, $last_name, $gender, $city, $country, $profileImage);
            $stmt->fetch();

            $user = new User($id, $email, $first_name, $last_name, $birthday, $gender, $city, $country, $profileImage);
            session_start();
            $_SESSION["user"] = $user;
            $data["user"] = $user;

            $friendsCount = $connection->query("SELECT COUNT(id) as count FROM user_friend_list WHERE user_id = " . $id);
            $row = $friendsCount->fetch_assoc();
            $data["friendsCount"] = $row["count"];

            return array("ok" => 200, "message" => "Welcome " . $email . " ", "data" => $data);
        } else {
            return array("ok" => 500, "message" => "Sorry Couldn't login you in");
        }

    }

    public static function registerUser($first_name, $last_name, $email, $userPass, $birthday, $gender, $city, $country, $imageUrl)
    {
        require_once "config/connection.php";

        if ($stmt = $connection->prepare('SELECT email FROM user WHERE email = ?')) {
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo ("Email already exists, Try to login");
                header("refresh:2;url=../login.html");
            } else {
                if ($stmt = $connection->prepare("INSERT INTO user (`first_name`, `last_name`, `email`, `password`, `birthday`, `gender`, `city`, `country`, `profile_image`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    $stmt->bind_param("sssssisss", $first_name, $last_name, $email, hash("sha256", $userPass), date("Y-m-d", strtotime($birthday)), $gender, $city, $country, $imageUrl);
                    $stmt->execute();

                }
                if ($stmt->affected_rows > 0) {
                    header("location: ../index.html");
                } else {
                    echo 'An error occured' . $stmt->error;
                }
            }

        }
    }

}
