<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
  $host = "localhost";
  $user = "root";
  $password = "";
  $db = "pharma_bak";
} else {
  $host = "";
  $user = "";
  $password = "";
  $db = "";
}

$response = array(
  "success" => false,
  "message" => ""
);

try {
  $conn = mysqli_connect($host, $user, $password, $db);
} catch (Exception $e) {
  $response["message"] = $e->getMessage();
  returnResponse($response);
}
