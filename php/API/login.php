<?php
require_once "../base/User.php";
// $data = json_decode(trim(file_get_contents("php://input")), true);

if (isset($_POST["email"]) && isset($_POST["password"])) {
    echo json_encode(User::loginUser($_POST["email"], $_POST["password"]));
}
