<?php

include_once "config/connection.php";
include_once "base/User.php";
session_start();
$email = $_SESSION["user"]->getEmail();
$id = $_SESSION["user"]->getId();
echo $email;
echo $id;
if ($_FILES["userImage"]["name"] != "") {
    $target_dir = "../images/users/";

    $imageFileType_primary = strtolower(pathinfo(basename($_FILES["userImage"]["name"]), PATHINFO_EXTENSION));
    $target_file_primary = $target_dir . strtolower(explode("@", $email)[0]) . "." . $imageFileType_primary;

    $uploadok = 1;

    $check_primary = getimagesize($_FILES["userImage"]["tmp_name"]);

    if ($check_primary !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    if (file_exists($target_file_primary)) {
        unlink($target_file_primary);
    }

    if ($_FILES["userImage"]["size"] > 5000000) {
        $uploadOk = 0;
    }
    echo $target_file_primary;
    if ($uploadOk == 0) {
        echo "Sorry, your files was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["userImage"]["tmp_name"], $target_file_primary)) {

        } else {
            echo "Failed to update info";
        }
    }
} else {
    if ($_POST["gender"] == 0) {
        $target_file_primary = "../images/users/female.jpg";
    } else {
        $target_file_primary = "../images/users/male.jpg";
    }
}
echo $_POST["firstName"], $_POST["lastName"], date("Y-m-d", strtotime($_POST["year"] . "-" . $_POST["month"] . "-" . $_POST["day"])), $_POST["city"], $_POST["country"], $target_file_primary, $id;
$stmt = $connection->prepare("UPDATE user SET first_name = ?, last_name = ?,birthday = ?, city = ?, country = ?, profile_image = ? WHERE id = ?");
$stmt->bind_param("ssssssi", $_POST["firstName"], $_POST["lastName"], date("Y-m-d", strtotime($_POST["year"] . "-" . $_POST["month"] . "-" . $_POST["day"])), $_POST["city"], $_POST["country"], $target_file_primary, $id);
$stmt->execute();

if ($connection->affected_rows > 0) {
    header("location: ./logout.php");
}
