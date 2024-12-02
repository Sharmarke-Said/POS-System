<?php 

session_start();

function logoutsession(){
    unset($_SESSION["loggedIn"]);
    unset($_SESSION["loggedInUser"]);
}

require 'dbcon.php';


// input field validation
function validate($inputData){
    
    global $conn;
    $validateData = mysqli_real_escape_string($conn, $inputData);
    return trim($validateData);
}

// Redirect
// function redirect($url, $status){
//     $_SESSION['status'] = $status;
//     header('Location:' . $url);
    
//     exit(0);
// }

// Altert messages after each operation
// function alertMessage(){

//     if(isset($_SESSION['status'])){
//         // echo $_SESSION['status'];
//         echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
//             <h6>'.$_SESSION['status'].'</h6>    
//             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//         </div>';
//         unset($_SESSION['status']);
//     }
    
// }





// Redirect with status and optional message
function redirect($url, $status, $customMessage = null) {
    $messages = [
        'success' => 'Operation was successful!',
        'error' => 'Operation failed. Please try again.'
        // Add more status types and default messages as needed
    ];

    $message = ($customMessage !== null) ? $customMessage : $messages[$status];

    $_SESSION['status'] = [
        'type' => $status,
        'message' => $message
    ];

    header('Location: ' . $url);
    exit(0);
}



// Display alert message
function alertMessage() {
    if (isset($_SESSION['status'])) {
        $status = $_SESSION['status']['type'];
        $message = $_SESSION['status']['message'];

        $alertClass = ($status === 'success') ? 'alert-success' : 'alert-danger';

        echo '<div class="alert ' . $alertClass . ' text-center alert-dismissible fade show alert-sm" role="alert">
            <p class="mb-0">' . $message . '</p>    
        </div>';

        unset($_SESSION['status']);
    }
}








// CRUD Operations

// CREATE(INSERT) operation

function insert($tableName, $data){
    global $conn;

    $table = validate($tableName);

    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = "'".implode("', '", $values)."'";

    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($conn, $query);

    return $result;
}

// UPDATE operation

function update($tableName, $id, $data){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach ($data as $column => $value) {
        $updateDataString .= $column.'='."'$value',";
    }

    $finalUpdateDate = substr(trim($updateDataString),0,-1);

    
    $query = "UPDATE $table SET $finalUpdateDate WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    return $result;
}


// DISPLAY operation
// Display all records
function getAll($tableName, $status = NULL){
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    if($status == 'status'){
        $query = "SELECT * FROM $table WHERE status='0'";
    }
    else{
        $query = "SELECT * FROM $table";
    }
    
    return mysqli_query($conn, $query);
    
}

// Display by specific(id) record
function getByID($tableName, $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);
    
    $query = "SELECT * FROM $table WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if($result){
        
        if(mysqli_num_rows($result) == 1){
            // $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $row = mysqli_fetch_assoc($result);

            $response = [
                'status' => 200,
                'data' => $row,
                'message' => 'Record Found',
            ];
        }
        else{
            $response = [
                'status' => 404,
                'message' => 'No Data Found',
            ];
        }
    }
    else{
        $response = [
            'status' => 500,
            'message' => 'Something went wrong'
        ];
    }

    return $response;
        
}


// DELETE  operation
function delete($tableName, $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    return $result;
    
}



// Check if id parameter exists or not

function checkIdParam($type){
    if(isset($type)){
        if($_GET[$type] != ''){
            return $_GET[$type];
        }
        else{
            echo '<h5>No id Found!.</h5>';
        }
    }
    else{
        echo '<h5>No id given!.</h5>';
    }
}


// Json response

function jsonResponse($status, $status_type, $message){
    $response = [
        'status' => $status,
        'status_type' => $status_type,
        'message' => $message,
    ];
    echo json_encode($response);
    return;
}

function getCount($tableName){
    global $conn;

    $table = validate($tableName);

    $query = "SELECT * FROM $table";
    $query_num = mysqli_query($conn, $query);
    if($query_num){
        $totalCount = mysqli_num_rows($query_num);
        return $totalCount;
    }else{
        return "Something went wrong";
    }
    
}




?>