<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
  $host = "localhost";
  $user = "root";
  $password = "";
  $db = "pharma";
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
  echo '<script>
        alert("' . (nl2br($e->getMessage())) . '");
  </script>';
}
