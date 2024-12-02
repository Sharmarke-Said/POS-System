<?php
// Include necessary files and configurations
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config/function.php");

// Add Customer
if (isset($_POST['saveCustomer'])) {
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) ? 1 : 0;

    if ($name != '' && $email != '' && $phone != '') {
        // Check if email is already used by another customer
        $emailCheck = mysqli_query($conn, "SELECT * FROM customers WHERE email = '$email'");

        if ($emailCheck) {
            if (mysqli_num_rows($emailCheck) > 0) {
                redirect('customers.php', 'error', 'Email already used by another customer.');
                die();
            }
        }

        // Data to be inserted into the 'customers' table
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
        ];

        // Insert data into the 'customers' table
        $result = insert('customers', $data);

        if ($result) {
            redirect('customers.php', 'success', 'Customer Added Successfully.');
            die();
        } else {
            redirect('customers.php', 'error', 'Something went wrong!');
            die();
        }
    } else {
        redirect('customers.php', 'error', 'Please fill the required fields');
        die();
    }
}


// Update Customer
if(isset($_POST['updateCustomer'])){
    $customerId = validate($_POST['customerId']);

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) == true ?1:0;

    $data = [
        'name' => $name,
        'email'=> $email,
        'phone' => $phone,
        'status' => $status,
    ];

    $result = update('customers', $customerId, $data);

    if ($result) 
    {
        redirect('customers.php?id='.$customerId,  'success',  'Customer Updated Successfully.');
    } 
    else 
    {
        redirect('customers.php?id='.$customerId, 'error', 'Something went wrong.');
    } 

}


// Delete Customer

$paramResultId = checkIdParam('id');

if (is_numeric($paramResultId)) {
    $customerId = validate($paramResultId);

    $customerData = getByID('customers', $customerId);
    
    if ($customerData['status'] == 200) {
        $Respose = delete('customers', $customerId);
        if ($Respose) {
            redirect('customers.php', 'success', 'Customer deleted successfully');
            die();
        } else {
            redirect('customers.php', 'error', 'Something went wrong');
            die();
        }
    } else {
        redirect('customers.php', 'error', $customerData['message']);
        die();
    }
} else {
    redirect('customers.php', 'error', 'Something went wrong');
    die();
}



?>