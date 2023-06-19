<?php

$dateNow = date("Y-m-d H:i:s");

$separator = "!I_I!";

$ORIGIN = "http://$_SERVER[SERVER_NAME]";
$PATH = "/pharma";

$SERVER_NAME = "";
if ($_SERVER['HTTP_HOST'] == "localhost") {
  $SERVER_NAME = ($ORIGIN . $PATH);
} else {
  $SERVER_NAME = ($ORIGIN);
}

function checkItemNameCount($itemId = null, $itemName)
{
  global $conn;;

  $itemNameCount = mysqli_num_rows(
    mysqli_query(
      $conn,
      "SELECT * FROM `inventory` WHERE " . ($itemId == null ? "" : "item_id != '$itemId' and ") . " name='$itemName'"
    )
  );

  return $itemNameCount;
}

function getTableData($table, $column = null, $value = null)
{
  global $conn;

  $data = array();

  $query = mysqli_query(
    $conn,
    "SELECT * FROM $table " . ($column ? "WHERE $column='$value'" : "")
  );

  while ($row = mysqli_fetch_object($query)) {
    array_push($data, $row);
  }

  return count($data) > 0 ? $data : null;
}

function getCourse($course_id)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM course WHERE course_id='$course_id'"
  );

  return mysqli_num_rows($query) > 0 ? mysqli_fetch_object($query) : null;
}

function update($table, $data, $columnWHere, $columnVal)
{

  global $conn;

  $set = array();

  try {
    if (count($data) > 0) {
      foreach ($data as $column => $value) {
        if ($value) {
          array_push($set, "$column = '" . mysqli_escape_string($conn, $value) . "'");
        }
        if ($table == "inventory") {
          if ($value != "") {
            array_push($set, "$column = '" . mysqli_escape_string($conn, $value) . "'");
          }
        }
        if ($value == "set_null") {
          array_push($set, "$column = NULL");
        }
      }

      if (count($set) > 0) {
        $queryStr = "UPDATE `$table` SET " . (implode(', ', $set)) . " WHERE $columnWHere='$columnVal'";

        return mysqli_query($conn, $queryStr);
      }

      return null;
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }

  return null;
}

function delete($table, $column, $value)
{
  global $conn;

  try {
    $queryStr = "DELETE FROM `$table` WHERE `$column`='$value'";

    return mysqli_query($conn, $queryStr);
  } catch (Exception $e) {
    $error = $e->getMessage();
  }

  return null;
}

function insert($table, $data)
{
  global $conn;

  $columns = array();
  $values = array();

  try {
    if (count($data) > 0) {
      foreach ($data as $column => $value) {
        if ($value) {
          array_push($columns, "`$column`");
          array_push($values, "'" . mysqli_escape_string($conn, $value) . "'");
        }
      }

      if (count($values) == count($columns)) {
        $queryStr = "INSERT INTO `$table` (" . implode(",", $columns) . ") VALUES (" . implode(",", $values) . ")";

        $query = mysqli_query($conn, $queryStr);

        if ($query) {
          return mysqli_insert_id($conn);
        } else {
          $error = mysqli_error($conn);
        }
      }

      return null;
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }

  return null;
}

function generateSystemId($preferredLetter = null)
{
  global $conn, $db;
  $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

  $AUTO_INCREMENT = mysqli_fetch_object(
    mysqli_query(
      $conn,
      "SELECT AUTO_INCREMENT AS ID FROM information_schema.tables WHERE table_name = 'users' and table_schema = '$db'"
    )
  );

  $countUser = mysqli_num_rows(
    mysqli_query(
      $conn,
      "SELECT COUNT(*) AS count FROM users"
    )
  );

  $letterIndex = intval(intval($countUser) / 100);
  $letter = $preferredLetter == null ? $characters[$letterIndex] : $preferredLetter;

  return date('Y') . $letter . str_pad($AUTO_INCREMENT->ID, 4, '0', STR_PAD_LEFT);
}

function isSelected($value, $toCheck)
{
  if ($value && $toCheck) {
    if ($value == $toCheck) {
      return "selected";
    } else {
      return "";
    }
  }
  return "";
}

function getUserById($userId)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$userId'"
  );

  return mysqli_num_rows($query) > 0 ? mysqli_fetch_object($query) : null;
}

function getFullName($userId, $format = "") // format = with_middle
{
  $user = getUserById($userId);
  $fullName = "";

  if ($user->mname == "") {
    $fullName = ucwords("$user->fname $user->lname");
  } else {
    if ($format) {
      $fullName = ucwords("$user->fname $user->mname $user->lname");
    } else {
      $middle = $user->mname[0];
      $fullName = ucwords("$user->fname " . $middle . ". $user->lname");
    }
  }

  return $fullName;
}

function getAvatar($userId)
{
  global $SERVER_NAME;
  $user = getUserById($userId);

  if ($user->avatar) {
    return "$SERVER_NAME/media/$user->avatar";
  }

  return "$SERVER_NAME/public/default.png";
}

function getItemById($itemId)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM inventory WHERE item_id='$itemId'"
  );

  return mysqli_num_rows($query) > 0 ? mysqli_fetch_object($query) : null;
}

function getItemImage($itemId)
{
  global $SERVER_NAME, $conn;
  $inventoryQuery = mysqli_query(
    $conn,
    "SELECT * FROM inventory WHERE item_id='$itemId'"
  );

  if (mysqli_num_rows($inventoryQuery) > 0) {
    $inventory = mysqli_fetch_object($inventoryQuery);
    if ($inventory->image) {
      return "$SERVER_NAME/items/$inventory->image";
    }
    return "$SERVER_NAME/items/default-item.png";
  }
  return "$SERVER_NAME/items/default-item.png";
}

function returnResponse($params)
{
  print_r(
    json_encode($params)
  );
}

function pr($data)
{
  echo "<pre>";
  print_r($data); // or var_dump($data);
  echo "</pre>";
}
