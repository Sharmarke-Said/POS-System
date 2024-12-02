<?php 

require("../config/function.php");

$paramResult = checkIdParam('index');

if(is_numeric($paramResult)){
    
    $indexValue = validate($paramResult);
    
    if(isset($_SESSION['productItems']) && isset($_SESSION['productItemIds'])){
        unset($_SESSION['productItems'][$indexValue]);
        unset($_SESSION['productItemIds'][$indexValue]);

        redirect('create-orders.php', 'success', 'Item' .$_SESSION['productItems']['name']. ' removed.');
    }
    else{
        redirect('create-orders.php', 'error', 'There is no item to remove.');
    }
}
else{
    redirect('create-orders.php', 'error', 'param is not numeric');
}





?>