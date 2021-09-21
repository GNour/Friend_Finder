<?php
require_once "./base/User.php";

if (isset($_POST["email"]) && isset($_POST["password"])) {
    if ($_FILES["userImage"]["name"] != "") {
        $target_dir = "../images/users/";

        $imageFileType_primary = strtolower(pathinfo(basename($_FILES["userImage"]["name"]), PATHINFO_EXTENSION));
        $target_file_primary = $target_dir . strtolower(explode("@", $_POST["email"])[0]) . "." . $imageFileType_primary;

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

                User::registerUser($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["password"], $_POST["year"] . "-" . $_POST["month"] . "-" . $_POST["day"], $_POST["gender"], $_POST["city"], $_POST["country"], $target_file_primary);

            } else {
                echo "Failed to register user";
            }
        }
    } else {
        if ($_POST["gender"] == 0) {
            $image = "../images/users/female.jpg";
        } else {
            $image = "../images/users/male.jpg";
        }
        User::registerUser($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["password"], $_POST["year"] . "-" . $_POST["month"] . "-" . $_POST["day"], $_POST["gender"], $_POST["city"], $_POST["country"], $image);
    }

}

function validatePassword()
{
    if ($_POST["password"] == $_POST["confirmPassword"]) {
        return true;
    }
    return false;
}

function validateEmail()
{
    if (strlen($_POST["email"]) > 5 && strripos($_POST["email"], ".") > strripos($_POST["email"], "@") && strripos($_POST["email"], "@")) {
        return true;
    }

    return false;
}
