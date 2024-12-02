<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/function.php");

// Add Category
if (isset($_POST['saveCategory'])) {
    // echo "Form submitted!";
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status,
    ];

    $result = insert('categories', $data);

    if ($result) {
        redirect('categories.php', 'success', 'Category Added Successfully.');
        die();
    } else {
        redirect('categories.php', 'error', 'Something went wrong!.');
        die();
    }
}


// Update category
if(isset($_POST['updateCategory'])){
    $categoryId = validate($_POST['categoryId']);

    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ?1:0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status,
    ];

    $result = update('categories', $categoryId, $data);

    if ($result) 
    {
        redirect('categories.php?id=' . $categoryId, 'success', 'Category Updated Successfully.');
    } 
    else 
    {
        redirect('categories.php?id=' . $categoryId, 'error', 'Something went wrong.');
    }

}



// Delete category

$paramResultId = checkIdParam('id');

if(is_numeric($paramResultId)){
    $categoryId = validate($paramResultId);

    // echo $adminId;

    $categoryData = getByID('categories', $categoryId);
    if($categoryData['status'] == 200)
    {
        $categoryDeleteRes = delete('categories', $categoryId);
        if($categoryDeleteRes)
        {
            redirect('categories.php','success', 'Category deleted successfully');
        }
        else{
            redirect('categories.php','error', 'Something went wrong');
        }
        
    }
    else
    {
        redirect('categories.php', 'error', $categoryData['message']);
    }
}
else{
    redirect('categories.php', 'Something went wrong');
}



?>