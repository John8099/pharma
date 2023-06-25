<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

date_default_timezone_set("Asia/Manila");

include("conn.php");
include("helpers.php");

$response = array(
  "success" => false,
  "message" => ""
);

$user = null;
$isLogin = isset($_SESSION["userId"]) ? true : false;
if ($isLogin) {
  $user = getUserById($_SESSION["userId"]);
}

if (isset($_GET['action'])) {
  try {
    switch ($_GET['action']) {
      case "logout":
        logout();
        break;
      case "login":
        login();
        break;
      case "addUser":
        addUser();
        break;
      case "update-user":
        updateUser();
        break;
      case "check_email":
        checkEmailIfExistR();
        break;
      case "delete_item":
        deleteItem();
        break;
      case "change_password":
        changePassword();
        break;
      case "save_manufacturer":
        saveManufacturer();
        break;
      case "save_medicine_type":
        save_medicine_type();
        break;
      case "add_medicine":
        add_medicine();
        break;
      default:
        null;
        break;
    }
  } catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = $e->getMessage();
  }
}

function add_medicine()
{
  global $conn, $_POST, $_FILES;

  $action = $_POST["action"];

  $medicine_img = $_FILES["medicine_img"];

  $code = generateSystemId();
  $type_id = $_POST["type_id"];
  $manufacturer_id = $_POST["manufacturer_id"];

  $classification = ucwords($_POST["classification"]);
  $generic_name = ucwords($_POST["generic_name"]);
  $brand_name = ucwords($_POST["brand_name"]);
  $dose = ucwords($_POST["dose"]);
  $price = $_POST["price"];
  $quantity = $_POST["quantity"];
  $expiration = $_POST["expiration"];
  $med_desc = ucfirst($_POST["med_desc"]);

  $medicineData = array(
    "manufacturer_id" => $manufacturer_id,
    "type_id" => $type_id,
    "code" => $code,
    "classification" => $classification,
    "generic_name" => $generic_name,
    "brand_name" => $brand_name,
    "dose" => $dose,
    "price" => $price,
    "quantity" => $quantity,
    "expiration" => $expiration,
    "image" => "",
    "description" => $med_desc
  );

  $uploadedImg = uploadImg($medicine_img, "../media/drugs");

  if ($uploadedImg->success) {
    $medicineData["image"] = $uploadedImg->file_name;

    if ($action == "add") {
      $comm = insert("medicine", $medicineData);
    } else if ($action == "edit") {
      $medicine_id = $_POST["medicine_id"];
      // $comm = update;
    } else {
      $response["success"] = false;
      $response["message"] = "An error occurred while uploading the image.<br>Please try again later.";
    }

  } else {
    $response["success"] = false;
    $response["message"] = "Error while saving medicine.<br>Please try again later.";
  }

  returnResponse($response);
}

function save_medicine_type()
{
  global $conn, $_POST;

  $typeId = isset($_POST["typeId"]) ? $_POST["typeId"] : null;
  $name = ucwords($_POST["name"]);
  $isActive = isset($_POST["isActive"]) ? "active" : "inactive";

  $action = $_POST["action"];

  if (!isMedicineTypeExist($name, $typeId)) {
    $typeData = array(
      "name" => $name,
      "status" => $isActive
    );

    $procMedicineType = null;
    if ($action == "add") {
      $procMedicineType = insert("medicine_types", $typeData);
    } else {
      $procMedicineType = update("medicine_types", $typeData, "type_id", $typeId);
    }

    if ($procMedicineType) {
      $response["success"] = true;
      $response["message"] = "Medicine type successfully " . ($action == "add" ? "added." : "updated.");
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Medicine type name: <strong>\"$name\"</strong> already exist.";
  }

  returnResponse($response);
}

function saveManufacturer()
{
  global $conn, $_POST;

  $manufactureId = isset($_POST["manufacturerId"]) ? $_POST["manufacturerId"] : null;
  $name = ucwords($_POST["name"]);
  $isActive = isset($_POST["isActive"]) ? "active" : "inactive";

  $action = $_POST["action"];

  if (!isManufacturerExist($name, $manufactureId)) {
    $manufacturerData = array(
      "name" => $name,
      "status" => $isActive
    );

    $procManufacturer = null;
    if ($action == "add") {
      $procManufacturer = insert("manufacturers", $manufacturerData);
    } else {
      $procManufacturer = update("manufacturers", $manufacturerData, "manufacturer_id", $manufactureId);
    }

    if ($procManufacturer) {
      $response["success"] = true;
      $response["message"] = "Manufacturer successfully " . ($action == "add" ? "added." : "updated.");
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Manufacturer name: <strong>\"$name\"</strong> already exist.";
  }

  returnResponse($response);
}

function addUser()
{
  global $conn, $_POST, $_SESSION;

  $fname = mysqli_escape_string($conn, ucwords($_POST["fname"]));
  $mname = mysqli_escape_string($conn, ucwords($_POST["mname"]));
  $lname = mysqli_escape_string($conn, ucwords($_POST["lname"]));
  $email = $_POST["email"];
  $password = isset($_POST["password"]) ? $_POST["password"] : "password123";
  $role = $_POST["role"];
  $action = $_POST["action"];

  $isEmailExist = checkEmailIfExistF($email);

  if (!$isEmailExist) {
    $userData = array(
      "fname" => $fname,
      "mname" => $mname,
      "lname" => $lname,
      "email" => $email,
      "password" => password_hash($password, PASSWORD_ARGON2I),
      "role" => $role
    );

    $user = insert("users", $userData);

    if ($user) {
      $response["success"] = true;
      if ($action == "register") {
        $_SESSION["userId"] = $user;
        $response["message"] = "You are now registered";
      } else {
        $response["message"] = "User successfully added<br>User's <strong>default</strong> password is \"<strong>$password</strong>\"";
      }
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already exist.";
  }

  returnResponse($response);
}

function changePassword()
{
  global $conn, $_POST;

  $userId = $_POST["userId"];
  $password = $_POST["nPassword"];

  $update = update(
    "users",
    array(
      "password" => md5($password),
      "isNew" => "set_null"
    ),
    "id",
    $userId
  );

  if ($update) {
    $response["success"] = true;
    $response["userId"] = $userId;
    $response["message"] = "Password successfully change";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function deleteItem()
{
  global $_POST, $conn;

  $table = $_POST["table"];
  $column = $_POST["column"];
  $val = $_POST["val"];

  $del = delete($table, $column, $val);

  if ($del) {
    $response["success"] = true;
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function checkEmailIfExistR()
{
  global $conn, $_POST;

  $id = isset($_GET['id']) ? $_GET['id'] : null;

  returnResponse(
    ["isExist" => mysqli_num_rows(
      mysqli_query(
        $conn,
        "SELECT * FROM users WHERE " . ($id ? "id != '$id' and " : "") . " email = '{$_POST['email']}'"
      )
    ) > 0 ? true : false]
  );
}

function checkEmailIfExistF($email, $id = null)
{
  global $conn;

  return mysqli_num_rows(
    mysqli_query(
      $conn,
      "SELECT * FROM users WHERE " . ($id ? "id != '$id' and " : "") . " email = '{$email}'"
    )
  ) > 0 ? true : false;
}

function updateUser()
{
  global $conn, $_POST, $_FILES;

  $profile = $_FILES["profile"];
  $userId = $_POST['id'];
  $uploadedFile = "";

  if (intval($profile["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($profile['name']);
    $target_dir = "../media";

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($profile['tmp_name'], "$target_dir/$uploadFile")) {
      $uploadedFile = $uploadFile;
    } else {
      $response["success"] = false;
      $response["message"] = "Error uploading profile.<br>Please try again later.";
    }
    exit();
  }

  $personalData = array(
    "first_name" => ucwords($_POST["fname"]),
    "middle_name" => ucwords($_POST["mname"]),
    "last_name" => ucwords($_POST["lname"]),
    "course_id" => $_POST["course"],
    "year" => ucwords($_POST["year"]),
    "section" => ucwords($_POST["section"]),
    "school" => ucwords($_POST["school"]),
    "place_of_birth" => ucwords($_POST["pob"]),
    "date_of_birth" => $_POST["dob"],
    "gender" => ucwords($_POST["gender"]),
    "address" => ucwords($_POST["address"]),
    "mobile_number" => $_POST["mobileNumber"],
    "email" => $_POST["email"],
    "blood_type" => ucwords($_POST["bloodType"]),
    "body_built" => ucwords($_POST["bodyBuilt"]),
    "height" => $_POST["height"],
    "weight" => $_POST["weight"],
    "ethnic_group" => ucwords($_POST["ethnicGroup"]),
    "religion" => ucwords($_POST["religion"]),
    "citizenship" => ucwords($_POST["citizenship"]),
    "identification_mark" => $_POST["identificationMark"],
    "hair_color" => ucwords($_POST["hairColor"]),
    "eye_color" => ucwords($_POST["eyeColor"]),
    "civil_status" => ucwords($_POST["civil"]),
    "avatar" => "$uploadedFile"
  );

  $updatePersonalData = update("users", $personalData, "id", $userId);
  if ($updatePersonalData) {
    // Civil Data
    if ($_POST["civil"] == "Married") {
      $civilData = array(
        "name_of_spouse" => ucwords($_POST["spouseName"]),
        "address" => ucwords($_POST["spouseAddress"]),
        "contact" => $_POST["spouseContact"],
        "occupation" => ucwords($_POST["spouseOccupation"]),
        "company_name" => ucwords($_POST["spouseCompany"])
      );

      if (isset($_POST['civil_id'])) {
        update("civil", $civilData, "user_id", $userId);
      } else {
        $civilData["user_id"] = $userId;
        insert("civil", $civilData);
      }
    } else {
      $civilDataDB = getTableData("civil", "user_id", $userId);

      if ($civilDataDB) {
        delete("civil", "civil_id", $userId);
      }
    }

    // Children Data
    if (count($_POST["childrenName"]) > 1) {

      for ($i = 0; $i < count($_POST["childrenName"]); $i++) {
        $childrenData = array(
          "name" => ucwords($_POST["childrenName"][$i]),
          "date_of_birth" => $_POST["childrenDOB"][$i],
          "place_birth" => $_POST["childrenPOB"][$i],
          "grade_or_year" => $_POST["childrenGradeOrYearLevel"][$i],
          "school" => ucwords($_POST["childrenSchool"][$i])
        );

        if ($_POST["childrenID"][$i] != "0") {
          update("childrens", $childrenData, "children_id", $_POST["childrenID"][$i]);
        } else {
          $childrenData["user_id"] = $userId;
          insert("childrens", $childrenData);
        }
      }
    } else {
      $childrenData = array(
        "name" => ucwords($_POST["childrenName"][0]),
        "date_of_birth" => $_POST["childrenDOB"][0],
        "place_birth" => $_POST["childrenPOB"][0],
        "grade_or_year" => $_POST["childrenGradeOrYearLevel"][0],
        "school" => ucwords($_POST["childrenSchool"][0])
      );

      if ($_POST["childrenID"][0] != "0") {
        update("childrens", $childrenData, "children_id", $_POST["childrenID"][0]);
      } else {
        $childrenData["user_id"] = $userId;
        insert("childrens", $childrenData);
      }
    }

    // Family Data
    $familyData = array(
      "father_name" => ucwords($_POST["fatherName"]),
      "father_date_of_birth" => $_POST["fatherDOB"],
      "father_place_of_birth" => ucwords($_POST["fatherPOB"]),
      "father_address" => ucwords($_POST["fatherAddress"]),
      "father_contact" => $_POST["fatherContact"],
      "father_occupation" => ucwords($_POST["fatherOccupation"]),
      "father_company_name" => ucwords($_POST["fatherCompany"]),
      "mother_name" => ucwords($_POST["motherName"]),
      "mother_date_of_birth" => $_POST["motherDOB"],
      "mother_place_of_birth" => ucwords($_POST["motherPOB"]),
      "mother_address" => ucwords($_POST["motherAddress"]),
      "mother_contact" => $_POST["motherContact"],
      "mother_occupation" => ucwords($_POST["motherOccupation"]),
      "mother_company_name" => ucwords($_POST["motherCompany"]),
    );

    update("family", $familyData, "user_id", $userId);

    // Siblings Data
    if (count($_POST["siblingName"]) > 1) {
      for ($i = 0; $i < count($_POST["siblingName"]); $i++) {
        $siblingData = array(
          "name" => ucwords($_POST["siblingName"][$i]),
          "date_of_birth" => $_POST["siblingDOB"][$i],
          "occupation" => ucwords($_POST["siblingOccupation"][$i]),
          "company" => ucwords($_POST["siblingCompany"][$i]),
        );

        if ($_POST["siblingID"][$i] != "0") {
          update("siblings", $siblingData, "sibling_id", $_POST["siblingID"][$i]);
        } else {
          $siblingData["user_id"] = $userId;
          insert("siblings", $siblingData);
        }
      }
    } else {
      $siblingData = array(
        "name" => ucwords($_POST["siblingName"][0]),
        "date_of_birth" => $_POST["siblingDOB"][0],
        "occupation" => ucwords($_POST["siblingOccupation"][0]),
        "company" => ucwords($_POST["siblingCompany"][0]),
      );

      if ($_POST["siblingID"][0] != "0") {
        update("siblings", $siblingData, "sibling_id", $_POST["siblingID"][0]);
      } else {
        $siblingData["user_id"] = $userId;
        insert("siblings", $siblingData);
      }
    }

    // Education Data
    if (count($_POST["educationLevel"]) > 1) {
      for ($i = 0; $i < count($_POST["educationLevel"]); $i++) {
        $educationData = array(
          "education_level" => ucwords($_POST["educationLevel"][$i]),
          "course_taken" => ucwords($_POST["educationCourse"][$i]),
          "name_of_school" => ucwords($_POST["educationSchoolName"][$i]),
          "address" => ucwords($_POST["educationAddress"][$i]),
          "year_completed" => $_POST["yearCompleted"][$i],
        );

        if ($_POST["educationID"][$i] != "0") {
          update("education", $educationData, "education_id", $_POST["educationID"][$i]);
        } else {
          $educationData["user_id"] = $userId;
          insert("education", $educationData);
        }
      }
    } else {
      $educationData = array(
        "education_level" => ucwords($_POST["educationLevel"][0]),
        "course_taken" => ucwords($_POST["educationCourse"][0]),
        "name_of_school" => ucwords($_POST["educationSchoolName"][0]),
        "address" => ucwords($_POST["educationAddress"][0]),
        "year_completed" => $_POST["yearCompleted"][0],
      );

      if ($_POST["educationID"][0] != "0") {
        update("education", $educationData, "education_id", $_POST["educationID"][0]);
      } else {
        $educationData["user_id"] = $userId;
        insert("education", $educationData);
      }
    }

    $response["success"] = true;
    $response["message"] = "User has been updated successfully";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function login()
{
  global $conn;

  $email = $_POST["email"];
  $password = $_POST["password"];
  $role = $_POST["role"];

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE email='$email'"
  );

  if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_object($query);
    if ($role != $user->role) {
      $response["success"] = false;
      $response["message"] = "You are not allowed to login on this page.";
    } else {
      if (password_verify($password, $user->password)) {
        $response["success"] = true;
        $_SESSION["userId"] = $user->id;

        if ($role == "admin") {
          $response["isNew"] = $user->isNew;
        }
      } else {
        $response["success"] = false;
        $response["message"] = "Password not match.";
      }
    }
  } else {
    $response["success"] = false;
    $response["message"] = "User not found.";
  }

  returnResponse($response);
}

function logout()
{
  global $_SESSION, $_GET;
  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  session_destroy();
  if ($_GET["location"] == "user") {
    header("location: ../");
  } else {
    header("location: ../admin/");
  }
}
