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
      case "save_supplier":
        save_supplier();
        break;
      case "save_category":
        save_category();
        break;
      case "save_brand":
        save_brand();
        break;
      case "medicine_save":
        medicine_save();
        break;
      case "add_medicine_quantity":
        add_medicine_quantity();
        break;
      case "add_to_cart":
        add_to_cart();
        break;
      case "remove_to_cart":
        remove_to_cart();
        break;
      case "change_qty":
        change_qty();
        break;
      case "admin_checkout":
        admin_checkout();
        break;
      case "get_order_details":
        get_order_details();
        break;
      case "get_category":
        get_select_data("category");
        break;
      case "get_brand":
        get_select_data("brands");
        break;
      case "get_supplier":
        get_select_data("supplier");
        break;
      case "get_medicine":
        get_select_data("medicine_profile");
        break;
      case "save_purchase":
        save_purchase();
        break;
      case "delete_med":
        delete_med();
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

function delete_med()
{
  global $conn, $_POST;

  $deletedData = array(
    "deleted" => "1"
  );

  $up = update("medicine_profile", $deletedData, "id", $_POST["medicine_id"]);

  if ($up) {
    $response["success"] = true;
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function save_purchase()
{
  global $conn, $_POST, $_SESSION;

  $supplier_id = $_POST["supplier_id"];
  $medicine_id = $_POST["medicine_id"];
  $payment_date = $_POST["payment_date"];
  $payment_amount = $_POST["payment_amount"];
  $quantity = $_POST["quantity"];

  $createdBy = $_SESSION["userId"];
  $creationDate = date("Y-m-d");

  $purchaseOrderData = array(
    "supplier_id" => $supplier_id,
    "created_by" => $createdBy,
    "medicine_id" => $medicine_id,
    "creation_date" => $creationDate,
    "payment_amount" => $payment_amount,
    "payment_date" => $payment_date,
    "quantity" => $quantity
  );

  $procPurchase = insert("purchase_order", $purchaseOrderData);

  if ($procPurchase) {
    $response["success"] = true;
    $response["message"] = "Purchase order successfully added.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function get_select_data($table)
{
  global $conn;

  $data = [];

  if ($table == "medicine_profile") {
    $medQuery = mysqli_query(
      $conn,
      "SELECT 
      mp.id,
      mp.medicine_name,
      mp.generic_name,
      (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name'
      FROM medicine_profile mp
      "
    );

    while ($row = mysqli_fetch_object($medQuery)) {
      array_push($data, $row);
    }
  } else {
    $data = getTableData($table);
  }

  returnResponse($data);
}

function get_order_details()
{
  global $_POST;

  $orderItems = array();
  $orderDetails = getTableDataById("orders", "order_id", $_POST['order_id']);

  array_push($orderItems, stripcslashes($orderDetails->items));

  returnResponse($orderItems);
}

function admin_checkout()
{
  global $conn, $_SESSION;

  if (isset($_SESSION["userId"])) {
    $cartData = getTableData("carts", "user_id", $_SESSION["userId"]);

    if (count($cartData) > 0) {
      $cartDetails = array(
        "order_code" => generateOrderCode(),
        "user_id" => $_SESSION["userId"],
        "overall_total" => "",
        "order_from" => "system",
        "items" => array()
      );
      $overallTotal = 0;
      foreach ($cartData as $cart) {
        $medicine = getTableDataById("medicines", "medicine_id", $cart->medicine_id);

        $overallTotal += floatval($medicine->price *  $cart->quantity);

        $itemData = array(
          "classification" => $medicine->classification,
          "generic_name" => $medicine->generic_name,
          "brand_name" => $medicine->brand_name,
          "price" => $medicine->price,
          "order_quantity" => $cart->quantity,
          "total" => number_format(floatval($medicine->price *  $cart->quantity), 2, ".")
        );

        array_push($cartDetails["items"], $itemData);
      }
      $cartDetails["items"] = mysqli_escape_string($conn, json_encode($cartDetails["items"]));
      $cartDetails["overall_total"] = $overallTotal;

      $insertOrder = insert("orders", $cartDetails);

      if ($insertOrder) {
        delete("carts", "user_id", $_SESSION["userId"]);
        $response["success"] = true;
        $response["message"] = "Cart successfully checked out.";
      } else {
        $response["success"] = false;
        $response["message"] = mysqli_error($conn);
      }
    } else {
      $response["success"] = false;
      $response["message"] = "Internal server error.<br>Please contact administrator";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Internal server error.<br>Please contact administrator";
  }

  returnResponse($response);
}

function change_qty()
{
  global $conn, $_POST, $_SESSION;

  if (isset($_SESSION["userId"])) {
    $action = $_POST["action"];
    $cart_id = $_POST["cart_id"];

    $cartData = getTableDataById("carts", "cart_id", $cart_id);

    if ($cartData->medicine_id) {
      try {
        $medicineData = getTableDataById("medicines", "medicine_id", $cartData->medicine_id);
        $newMedicineQty = $action == "add" ? intval($medicineData->quantity) - 1 : intval($medicineData->quantity) + 1;
        $newCartQty = $action == "add" ? intval($cartData->quantity) + 1 : intval($cartData->quantity) - 1;

        update("medicines", array("quantity" => $newMedicineQty), "medicine_id", $cartData->medicine_id);
        update("carts", array("quantity" => $newCartQty), "cart_id", $cartData->cart_id);

        $response["success"] = true;
      } catch (Exception $e) {
        $response["success"] = false;
        $response["message"] = $e->getMessage();
      }
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Internal server error.<br>Please contact administrator";
  }

  returnResponse($response);
}

function remove_to_cart()
{
  global $conn, $_POST;

  $getCartData = getTableData("carts", "cart_id", $_POST["cart_id"]);
  if (count($getCartData) > 0) {
    $cartData = $getCartData[0];
    $newQuantity = intval($_POST["medicine_qty"]) + intval($cartData->quantity);
    $updateQty = update("medicines", array("quantity" => $newQuantity), "medicine_id", $cartData->medicine_id);

    if ($updateQty) {
      delete("carts", "cart_id", $cartData->cart_id);
      $response["success"] = true;
      $response["message"] = "Item in cart successfully remove";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Internal server error.<br>Please contact Administrator.";
  }

  returnResponse($response);
}

function add_to_cart()
{
  global $conn, $_POST, $_SESSION;

  $quantity_to_add = $_POST["quantity_to_add"];
  $medicine_id = $_POST["medicine_id"];
  $userId = $_SESSION["userId"];

  if (isset($_SESSION["userId"])) {
    $cartIdIfExist = getCartDataIdIfExist($medicine_id, $userId);

    $cartData = array(
      "user_id" => $userId,
      "medicine_id" => $medicine_id,
      "quantity" => $quantity_to_add
    );

    if ($cartIdIfExist) {
      $dbCartData = getTableData("carts", "cart_id", $cartIdIfExist);
      $newCartQuantity = intval($dbCartData[0]->quantity) + intval($quantity_to_add);

      $comm = update("carts", array("quantity" => $newCartQuantity), "cart_id", $cartIdIfExist);
    } else {
      $comm = insert("carts", $cartData);
    }

    if ($comm) {
      update_quantity($medicine_id, $quantity_to_add);
      $response["success"] = true;
      $response["message"] = "Successfully added to cart.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Internal server error!<br>Please contact administrator.";
  }

  returnResponse($response);
}

function update_quantity($medicine_id, $quantity)
{
  $medicineData = getTableData("medicines", "medicine_id", $medicine_id)[0];
  $newQuantity = intval($medicineData->quantity) - intval($quantity);

  update("medicines", array("quantity" => $newQuantity), "medicine_id", $medicine_id);
}

function add_medicine_quantity()
{
  global $conn, $_POST;

  $quantity_to_add = $_POST['quantity_to_add'];
  $medicine_id = $_POST['medicine_id'];

  $medicine_data = getTableData("medicines", "medicine_id", $medicine_id);

  if (count($medicine_data) > 0) {
    $newMedicineQty = intval($medicine_data[0]->quantity) + intval($quantity_to_add);
    $medicine_arr = array(
      "quantity" => $newMedicineQty
    );
    $comm = update("medicines", $medicine_arr, "medicine_id", $medicine_id);

    if ($comm) {
      $response["success"] = true;
      $response["message"] = "Medicine successfully added.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Error while adding quantity.<br>Please try again later.";
  }

  returnResponse($response);
}

function medicine_save()
{
  global $conn, $_POST, $_FILES;

  $action = $_POST["action"];
  $isCleared = isset($_POST["isCleared"]) ? $_POST["isCleared"] : "";

  $medicine_id = isset($_POST["medicine_id"]) ? $_POST["medicine_id"] : null;

  $medicine_img = $_FILES["medicine_img"];
  $name = ucwords($_POST["name"]);
  $category_id = $_POST["category_id"];
  $dose = $_POST["dose"];
  $generic_name = ucwords($_POST["generic_name"]);
  $brand_id = $_POST["brand_id"];
  $med_desc = ucfirst(nl2br($_POST["med_desc"]));

  if (!isMedicineExist(strtolower($name), strtolower($generic_name), $brand_id, strtolower($dose), $category_id, $medicine_id)) {

    $medicineData = array(
      "medicine_name" => $name,
      "category_id" => $category_id,
      "image" => "",
      "brand_id" => $brand_id,
      "generic_name" => $generic_name,
      "description" => $med_desc,
      "dosage" => $dose
    );

    $uploadedImg = uploadImg($medicine_img, "../media/drugs");
    $medicineData["image"] = $uploadedImg->success ? $uploadedImg->file_name : "";

    $comm = null;

    if ($action == "add") {
      $comm = insert("medicine_profile", $medicineData);
    } else if ($action == "edit") {
      $medicineData["image"] = $isCleared == "Yes" ? "set_null" : $uploadedImg->file_name;

      $comm = update("medicine_profile", $medicineData, "id", $medicine_id);
    } else {
      $response["success"] = false;
      $response["message"] = "An error occurred while uploading the image.<br>Please try again later.";
    }

    if ($comm) {
      $response["success"] = true;
      if ($action == "edit") {
        $response["message"] = "Medicine successfully updated.";
      } else {
        $response["message"] = "Successfully added new medicine.";
      }
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Medicine is already exist.";
  }

  returnResponse($response);
}

function save_brand()
{
  global $conn, $_POST;

  $brandId = isset($_POST["brandId"]) ? $_POST["brandId"] : null;
  $type = isset($_POST["type"]) ? $_POST["type"] : null;

  $name = ucwords($_POST["name"]);
  $description = ucwords(nl2br($_POST["description"]));

  $action = $_POST["action"];

  if (!isBrandExist($name, $brandId)) {
    $brandData = array(
      "brand_name" => $name,
      "brand_description" => $description
    );

    $procBrand = null;
    if ($action == "add") {
      $procBrand = insert("brands", $brandData);
    } else {
      $procBrand = update("brands", $brandData, "id", $brandId);
    }

    if ($procBrand) {
      $response["success"] = true;
      $response["message"] = "Brand successfully " . ($action == "add" ? "added." : "updated.");

      if ($type == "add_select") {
        $response["id"] = $procBrand;
        $response["description"] = $description;
        $response["brand_name"] = $name;
      }
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Brand name: <strong>\"$name\"</strong> already exist.";
  }

  returnResponse($response);
}

function save_category()
{
  global $conn, $_POST;

  $categoryId = isset($_POST["categoryId"]) ? $_POST["categoryId"] : null;
  $name = ucwords($_POST["name"]);
  $description = ucwords(nl2br($_POST["description"]));

  $action = $_POST["action"];

  if (!isCategoryExist($name, $categoryId)) {
    $categoryData = array(
      "category_name" => $name,
      "description" => $description
    );

    $procCategory = null;
    if ($action == "add") {
      $procCategory = insert("category", $categoryData);
    } else {
      $procCategory = update("category", $categoryData, "id", $categoryId);
    }

    if ($procCategory) {
      $response["success"] = true;
      $response["message"] = "Category successfully " . ($action == "add" ? "added." : "updated.");
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Category name: <strong>\"$name\"</strong> already exist.";
  }

  returnResponse($response);
}

function save_supplier()
{
  global $conn, $_POST;

  $supplierId = isset($_POST["supplierId"]) ? $_POST["supplierId"] : null;
  $name = ucwords($_POST["name"]);
  $address = ucwords($_POST["address"]);
  $contact = ucwords($_POST["contact"]);

  $action = $_POST["action"];

  if (!isSupplierExist($name, $address, $supplierId)) {
    $supplierData = array(
      "supplier_name" => $name,
      "address" => $address,
      "contact" => $contact
    );

    $procSupplier = null;
    if ($action == "add") {
      $procSupplier = insert("supplier", $supplierData);
    } else {
      $procSupplier = update("supplier", $supplierData, "id", $supplierId);
    }

    if ($procSupplier) {
      $response["success"] = true;
      $response["message"] = "Supplier successfully " . ($action == "add" ? "added." : "updated.");
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Supplier name: <strong>\"$name\"</strong> already exist.";
  }

  returnResponse($response);
}

function addUser()
{
  global $conn, $_POST, $_SESSION;

  $fname = mysqli_escape_string($conn, ucwords($_POST["fname"]));
  $mname = mysqli_escape_string($conn, ucwords($_POST["mname"]));
  $lname = mysqli_escape_string($conn, ucwords($_POST["lname"]));
  $uname = mysqli_escape_string($conn, ucwords($_POST["uname"]));

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
      "uname" => $uname,
      "email" => $email,
      "password" => password_hash($password, PASSWORD_ARGON2I),
      "role" => $role,
      "isNew" => "1"
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
