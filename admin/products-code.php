<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include("../config/function.php");

// Add Product
if (isset($_POST['saveProduct'])) {
    // echo "Form submitted!";
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1 : 0;

    if($_FILES['image']['size'] > 0) {
        $path = ("../assets/uploads/products");
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time().'.'.$image_ext;
        
        move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);

        $finalimage = "assets/uploads/products/".$filename;
    }
    else{
        $finalimage = "";
    }
    

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalimage,
        'status' => $status,
    ];

    $result = insert('products', $data);

    if ($result) {
        redirect('products.php', 'success', 'Product Added Successfully.');
        die();
    } else {
        redirect('products.php', 'error', 'Something went wrong!.');
        die();
    }
}


// Update Product
if (isset($_POST['updateProduct'])) {
    $productId = validate($_POST['productId']);
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) ? 1 : 0;

    $productData = getByID('products', $productId);
    if (!$productData || $productData['status'] !== 200) {
        redirect('products.php', 'error', 'No product found!');
    }

    // Check if a new image is uploaded
    if ($_FILES['image']['size'] > 0) {
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);

        $finalImage = "assets/uploads/products/" . $filename;

        // Delete old image file
        $deleteImage = "../" . $productData['data']['image'];
        if (file_exists($deleteImage)) {
            unlink($deleteImage);
        }
    } else {
        // If no new image is uploaded, keep the existing image
        $finalImage = $productData['data']['image'];
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status,
    ];

    $result = update('products', $productId, $data);

    if ($result) {
        redirect('products.php?id=' . $productId, 'success', 'Product Updated Successfully.');
        die();
    } else {
        redirect('products.php?id=' . $productId, 'error', 'Something went wrong during the update.');
        die();
    }
}



// Delete product

$paramResultId = checkIdParam('id');

if (is_numeric($paramResultId)) {
    $productId = validate($paramResultId);

    $productData = getByID('products', $productId);
    
    if ($productData['status'] == 200) {
        // Get the image path before deleting the product
        $delteimagepath = "../" . $productData['data']['image'];

        $response = delete('products', $productId);

        if ($response) {
            // Delete the associated image file
            if (file_exists($delteimagepath)) {
                unlink($delteimagepath);
            }

            redirect('products.php', 'success', 'Product deleted successfully');
            die();
        } else {
            redirect('products.php', 'error', 'Something went wrong');
            die();
        }
    } else {
        redirect('products.php', 'error', $productData['message']);
        die();
    }
} else {
    redirect('products.php', 'error', 'Something went wrong');
    die();
}




?>