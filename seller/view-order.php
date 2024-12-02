<?php include('includes/header.php'); ?>

<div class="container-fluid">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4>veiw order</h4>
            <a href="view-order-print.php?track=<?= $_GET['track']; ?>" class="btn btn-info mx-2 btn-sm float-end">Print
                Bill</a>
            <a href="orders.php" class="btn btn-danger mx-2 btn-sm float-end">Back</a>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php 
            if(isset($_GET['track']))
            {
                if($_GET['track'] == ''){
                    ?>
            <div class="text-center py-5">
                <h5>Tracking No not found!</h5>
                <div class="">
                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back to Orders</a>
                </div>
            </div>
            <?php
            return false;
            }

            $trackingNo = validate($_GET['track']);
            $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id AND tracking_no =
            '$trackingNo' ORDER BY o.id DESC";

            $order = mysqli_query($conn, $query);


            if($order){

            if(mysqli_num_rows($order) > 0){

            $orderData = mysqli_fetch_assoc($order);
            $orderId = $orderData['id'];

            ?>

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Order Details</h4>

                        <label class="mb-1">
                            Tracking No: <span class="fw-bold"><?= $orderData['tracking_no'] ;?></span>
                        </label>
                        <br>

                        <label class="mb-1">
                            Order Date:
                            <span class="fw-bold"><?= $orderData['order_date'] ;?></span>
                        </label>
                        <br>

                        <label class="mb-1">
                            Order Status:
                            <span class="fw-bold"><?= $orderData['order_status'] ;?></span>
                        </label>
                        <br>

                        <label class="mb-1">
                            Payment Mode:
                            <span class="fw-bold"><?= $orderData['payment_mode'] ;?></span>
                        </label>
                        <br>

                    </div>
                    <div class="col-md-6">
                        <h4>Customer Details</h4>

                        <label class="mb-1">
                            Customer Name: <span class="fw-bold"><?= $orderData['name'] ;?></span>
                        </label>
                        <br>

                        <label class="mb-1">
                            Customer Email: <span class="fw-bold">
                                <?= $orderData['email'] ;?>
                            </span>
                        </label>
                        <br>

                        <label class="mb-1">
                            Customer Phone: <span class="fw-bold"><?= $orderData['phone'] ;?></span>
                        </label>
                        <br>


                    </div>
                </div>
            </div>

            <?php 

            $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.* 
            FROM orders as o, order_items as oi, products as p 
            WHERE oi.order_id = o.id AND oi.product_id = p.id AND o.tracking_no = $trackingNo ";
            $orderItemsRes = mysqli_query($conn, $orderItemQuery);

                if($orderItemsRes){
                
                if(mysqli_num_rows($orderItemsRes) > 0){

                    ?>
            <h4 class="my-3">Order Item Details</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td>Product</td>
                        <td>Price</td>
                        <td>Quantity</td>
                        <td>Total</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orderItemsRes as $orderItemRow): ?>
                    <tr>
                        <td class="fw-bold text-center">
                            <img src="<?= $orderItemRow['image'] != '' ? '../'.$orderItemRow['image'] : '../assets/images/no-image.png' ;?>"
                                style="width: 50px; height: 50px;" alt="img">
                            <?= $orderItemRow['name'] ;?>
                        </td>
                        <td class="fw-bold text-center"><?= number_format($orderItemRow['orderItemPrice']) ;?>
                        </td>
                        <td class="fw-bold text-center"><?= number_format($orderItemRow['orderItemQuantity']) ;?></td>
                        <td class="fw-bold text-center">
                            <?= number_format($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'], 0) ;?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="text-end fw-bold">Total Price: </td>
                        <td colspan="3" class="fw-bold text-end">$
                            <?= number_format($orderData['total_amount'], 2) ;?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
                    
                }
                else{
                    echo '<h5>Something went wrong</h5>';
                    return false;
                }
                
            }
            else{
                echo '<h5>Something went wrong</h5>';
                return false;
            }
            
            ?>

            <?php
                }
                else{
                echo "<h5>No order found</h5>";
                return false;
                }
                }
                else{
                echo "<h5>Something went wrong</h5>";
                }
                }
                else{
                    ?>
            <div class="text-center py-5">
                <h5>Tracking No not found!</h5>
                <div class="">
                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Go Back to Orders</a>
                </div>
            </div>
            <?php
                }
                ?>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>