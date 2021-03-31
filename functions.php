<?php
include_once 'database.php';
include_once 'user.php';

function getAllUsers()
{
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $stmt = $user->read();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $users = array();
        $users["records"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_row = array(
                "id" => $id,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
                "create_date" => $create_date,
                "update_date" => $update_date
            );
            array_push($users["records"], $user_row);
        }
        http_response_code(200);
        echo json_encode($users);
    } else {
        http_response_code(404);
    }
}

function getById()
{
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $user->id = isset($_GET['id']) ? $_GET['id'] : die();

    $user->readById();

    if ($user->first_name != null) {
        $user_arr = array(
            "id" =>  $user->id,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "create_date" => $user->create_date,
            "update_date" => $user->update_date
        );
        http_response_code(200);
        echo json_encode($user_arr);
    } else {
        http_response_code(404);
    }
}

function addUser()
{
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->first_name) &&
        !empty($data->last_name) &&
        !empty($data->email)
    ) {
        $user->first_name = $data->first_name;
        $user->last_name = $data->last_name;
        $user->email = $data->email;
        $user->create_date = date('Y-m-d H:i:s');

        if ($user->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "User added"), JSON_UNESCAPED_UNICODE);
            header("Location: " . $_SERVER["REQUEST_URI"]);
            exit;
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "User not added"), JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "User not added. Not enough data."), JSON_UNESCAPED_UNICODE);
    }
}

function editUser()
{
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    $user->id = $data->id;
    $user->first_name = $data->first_name;
    $user->last_name = $data->last_name;
    $user->email = $data->email;

    if ($user->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "User edited successful."), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Error. User did not edited."), JSON_UNESCAPED_UNICODE);
    }
}

function filter()
{
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));
    
    $stmt = $user->filter(
        $data->f_id,
        $data->f_first_name,
        $data->f_last_name,
        $data->f_email,
        $data->from_create_date,
        $data->to_create_date,
        $data->from_update_date,
        $data->to_update_date        
    );
    $num = $stmt->rowCount();

    if ($num > 0) {
        $users_arr = array();
        $users_arr["records"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $user_item = array(
                "id" => $id,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
                "create_date" => $create_date,
                "update_date" => $update_date
            );

            array_push($users_arr["records"], $user_item);
        }
        http_response_code(200);
        echo json_encode($users_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Users not found."), JSON_UNESCAPED_UNICODE);
    }
}

function search()
{
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $keywords = isset($_GET["s"]) ? $_GET["s"] : "";

    $stmt = $user->search($keywords);
    $num = $stmt->rowCount();

    if ($num > 0) {
        $users = array();
        $users["records"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                "id" => $id,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
                "create_date" => $create_date,
                "update_date" => $update_date
            );
            array_push($users["records"], $user_item);
        }
        http_response_code(200);
        echo json_encode($users);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Users not found."), JSON_UNESCAPED_UNICODE);
    }
}
