<?php

$hostname = "localhost";
$username = "";
$password = "";
$databasename = "mobile_database";
$mysqli = new mysqli($hostname, $username, $password, $databasename);

// require_once('php/mobile.php');

header('Content-type: text/javascript');


$type = isset($_GET["type"]) ? $_GET["type"] : "get";

if ($type == "get") {
    $master = isset($_GET["master"]) ? $_GET["master"] : "";
    $data = isset($_GET["data"]) ? $_GET["data"] : "";
    $command = isset($_GET["command"]) ? $_GET["command"] : "";
} else {
    $master = isset($_POST["master"]) ? $_POST["master"] : "";
    $data = isset($_POST["data"]) ? $_POST["data"] : "";
    $command = isset($_POST["command"]) ? $_POST["command"] : "";
}

if ($command == "to") {

    if ($master=="") {
        $json = '{"error":"missing id"}';
        echo $type == "get" ? "async.callbackTo($json)" : $json;
        exit;
    }

    $query = "INSERT INTO player (id, json) VALUES (?, ?) ON DUPLICATE KEY UPDATE json = ?";
    $stmt = $mysqli->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("sss", $master, $data, $data); // matches the ? in the query
        $stmt->execute();

        $json = '{"success":"true"}';
        echo $type == "get" ? "async.callbackTo($json)" : $json;
    } else {
        $json = '{"error":"could not add data"}';
        echo $type == "get" ? "async.callbackTo($json)" : $json;
    }

} else if ($command == "from") {

    if ($master=="") {
        $json = '{"error":"missing id"}';
        echo $type == "get" ? "async.callbackFrom($json)" : $json;
        exit;
    }

    $query = "SELECT json FROM player WHERE id=?";
    $stmt = $mysqli->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("s", $master); // matches the ? in the query
        $stmt->execute();
        $stmt->bind_result($json);
        $stmt->fetch();
        if (!$json) $json = '{}';
        echo $type == "get" ? "async.callbackFrom($json)" : $json;
    } else {
        $json = '{"error":"could not get data"}';
        echo $type == "get" ? "async.callbackFrom($json)" : $json;
    }

} else if ($command == "removeAll") {

    if ($master=="") {
        $json = '{"error":"missing id"}';
        echo $type == "get" ? "async.callbackRemoveAll($json)" : $json;
        exit;
    }

    $query = "DELETE FROM player WHERE id=?";
    $stmt = $mysqli->stmt_init();
    if ($stmt->prepare($query)) {
        $stmt->bind_param("s", $master);
        $stmt->execute();
        $json = '{"success":"success"}';
        echo $type == "get" ? "async.callbackRemoveAll($json)" : $json;
    } else {
        $json = '{"error":"could not connect"}';
        echo $type == "get" ? "async.callbackRemoveAll($json)" : $json;
    }

}

?>
