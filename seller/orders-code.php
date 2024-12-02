<?php 


include('../config/function.php');



if(!isset($_SESSION['productItems'])){
    $_SESSION['productItems'] = [];
}
if(!isset($_SESSION['productItemIds'])){
    $_SESSION['productItemIds'] = [];
}

if(isset($_POST['addItem'])){
    
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);


    $checkProduct = mysqli_query($conn, "SELECT * FROM products WHERE id ='$productId' LIMIT 1");

    if($checkProduct){
        
        if(mysqli_num_rows($checkProduct) > 0){
            
            $row = mysqli_fetch_assoc($checkProduct);
            if($row['quantity'] < $quantity){
                redirect('create-orders.php', 'error', 'only '.$row['quantity'].' quantity of '.$row['name'].' is available.');
            }

            $productData = [
                'product_id' => $row['id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'quantity' => $quantity,
            ];

            if(!in_array($row['id'], $_SESSION['productItemIds'])){
                array_push($_SESSION['productItemIds'], $row['id']);
                array_push($_SESSION['productItems'], $productData);
            }else{

                foreach($_SESSION['productItems'] as $key => $productSessionItem){
                    if($productSessionItem['product_id'] == $row['id']){
                        $newQuantity = $productSessionItem['quantity'] + $quantity;
                        $productData = [
                            'product_id' => $row['id'],
                            'name' => $row['name'],
                            'image' => $row['image'],
                            'price' => $row['price'],
                            'quantity' => $newQuantity,
                        ];
                        $_SESSION['productItems'][$key] = $productData;
                    }
                }
                
            }

            redirect('create-orders.php', 'success', ''.$row['name'].' Item added.');


            
            
            
        }else{
            redirect('create-orders.php', 'error', 'Product not found.');
        }
    }
    else{
        redirect('create-orders.php', 'error', 'Something went wrong.');
    }
    
}


// quantity increment, decremment
if(isset($_POST['producttIncDec'])){
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    foreach($_SESSION['productItems'] as $key => $item){
        if($item['product_id'] == $productId){
            $flag = true;
            $_SESSION['productItems'][$key]['quantity'] = $quantity;
        }
    }

    if($flag){
      
        jsonResponse(200, 'success','Quantity updated.');
    }
    else{
        jsonResponse(500, 'error','Something went wrong please refresh.');
    }
}


// Proceed to place order
if(isset($_POST['proceedToPlaceBtn'])){
    $phone = validate($_POST['cphone']);
    $payment_mode = validate($_POST['payment_mode']);

    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");

    if($checkCustomer){
        if(mysqli_num_rows($checkCustomer) > 0){
            $_SESSION['invoice_no'] = "INV-".rand(111111, 999999);
            $_SESSION['cphone'] = $phone;
            $_SESSION['payment_mode'] = $payment_mode;
            jsonResponse(200, 'success','Customer found.');
        }
        else{
            $_SESSION['cphone'] = $phone;
            jsonResponse(404, 'warning','Customer not found.');
        }
    }
    else{
        jsonResponse(500, 'error','Something went wrong.');
    }
    
}


// Save the customer to the database table

if(isset($_POST['saveCustomerBtn'])){
    $name = validate($_POST['c_name']);
    $phone = validate($_POST['c_phone']);
    $email = validate($_POST['c_email']);

    if(!$name == '' && !$phone == ''){
        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
        ];
        $result = insert('customers', $data);
        if($result){
            jsonResponse(200, 'success','Customer Added successfully.');
        }
        else{
            jsonResponse(500, 'error','Something went wrong.');
        }
    }
    else{
        jsonResponse(422, 'warning','please fill the required fields.');
    }
}


// save order
if(isset($_POST['saveOrderBtn'])){
    $phone = validate($_SESSION['cphone']);
    $invoice_no = validate($_SESSION['invoice_no']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];

    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
    
    if(!$checkCustomer){
        jsonResponse(500, 'error','Something went wrong.');
    }
    if(mysqli_num_rows($checkCustomer) > 0){
        $customerData = mysqli_fetch_assoc($checkCustomer);

        if(!isset($_SESSION['productItems'])){
            jsonResponse(404, 'error','No items to place order.');
        }

        $sessionProducts = $_SESSION['productItems'];
        $totalAmount = 0;
        
        foreach($sessionProducts as $amItem){
            $totalAmount += $amItem['price'] * $amItem['quantity'];
        }
        
        $data = [
            'customer_id' => $customerData['id'],
            'tracking_no' => rand(1111111, 9999999),
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d H:i:s'),
            'order_status' => 'booked',
            'payment_mode' => $payment_mode,    
            'order_placed_by_id' => $order_placed_by_id,
        ];

        $result = insert('orders', $data);

        $lastOrderId = mysqli_insert_id($conn);

        foreach($sessionProducts as $prodItem){
            $product_id = $prodItem['product_id'];
            $price = $prodItem['price'];
            $quantity = $prodItem['quantity'];

            $dataOrderItems = [
                'order_id' => $lastOrderId,
                'product_id' => $product_id,
                'price' => $price,
                'quantity' => $quantity,
            ];

            $orderItemQuery = insert('order_items', $dataOrderItems);

            // check the product quantity and decrease it and make Total quantity
            $checkProductQtyQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id' LIMIT 1");
            $productQtyData = mysqli_fetch_assoc($checkProductQtyQuery);
            $totalProductQty = $productQtyData['quantity'] - $quantity;

            $dataUpdate = [
                'quantity' => $totalProductQty,
            ];
            
            $updateProductQty = update('products', $product_id, $dataUpdate);
        }

        unset($_SESSION['productItems']);
        unset($_SESSION['productItemIds']);
        unset($_SESSION['cphone']);
        unset($_SESSION['invoice_no']);
        unset($_SESSION['payment_mode']);
        jsonResponse(200, 'success','Order placed successfully.');
        
    }
    else{
        jsonResponse(404, 'warning','Customer not found.');
    }
    
    
}



?>