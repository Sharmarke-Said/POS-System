<?php include('includes/header.php'); ?>


<div class="container-fluid">
    <div class="card shadow-ms mt-4">
        <div class="card-header">

            <div class="row">
                <div class="col-md-4">
                    <h4 class="mb-0">Orders</h4>
                </div>
                <div class="col-md-8">
                    <form action="" method="GET">
                        <div class="row g-1">
                            <div class="col-md-4">
                                <input type="date" name="date" class="form-control"
                                    value="<?= isset($_GET['date']) == true ? $_GET['date'] : ''; ?>">
                                <!-- <br> -->
                            </div>
                            <div class="col-md-4">
                                <select name="payment_status" class="form-select">
                                    <option value="">Select Payment Status</option>
                                    <option value="cash_payment" <?= isset($_GET['payment_status']) == true 
                                    ?
                                    ($_GET['payment_status'] == 'cash_payment' ? 'selected': '')
                                    : 
                                    '';   
                                    ?>>
                                        Cash Payment
                                    </option>
                                    <option value="online_payment" <?= isset($_GET['payment_status']) == true 
                                    ?
                                    ($_GET['payment_status'] == 'online_payment' ? 'selected': '')
                                    : 
                                    '';  
                                    ?>>Online Payment</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit">Filter</button>
                                <a href="orders.php" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class=" card-body">
            <?php 

            if(isset($_GET['date']) || isset($_GET['payment_status'])) 
            {
                $orderDate = validate(isset($_GET['date']));
                $paymentMode = validate(isset($_GET['payment_status']));

                if($orderDate != '' && $paymentMode == ''){
                    $query = "SELECT o.*, c.* FROM orders o, customers c
                 WHERE c.id = o.customer_id AND DATE(o.order_date)='$orderDate' ORDER BY o.id DESC"; 
                }
                elseif ($orderDate == '' && $paymentMode != '') {
                    $query = "SELECT o.*, c.* FROM orders o, customers c
                 WHERE c.id = o.customer_id AND o.payment_mode='$paymentMode' ORDER BY o.id DESC"; 
                }
                else if($orderDate != '' && $paymentMode != ''){
                    $query = "SELECT o.*, c.* FROM orders o, customers c
                 WHERE c.id = o.customer_id 
                 AND DATE(o.order_date)='$orderDate' 
                 AND o.payment_mode='$paymentMode' ORDER BY o.id DESC"; 
                }
                else{
                        
                    $query = "SELECT o.*, c.* FROM orders o, customers c
                    WHERE c.id = o.customer_id ORDER BY o.id DESC"; 
                }
            }
            else{
                
                $query = "SELECT o.*, c.* FROM orders o, customers c
                 WHERE c.id = o.customer_id ORDER BY o.id DESC"; 
            }
            
            $query = "SELECT o.*, c.* FROM orders o, customers c
            WHERE c.id = o.customer_id ORDER BY o.id DESC"; 
            $orders = mysqli_query($conn, $query);
            
            if($orders){
                if(mysqli_num_rows($orders) > 0){

                    ?>

            <table class=" table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <td>Tracking No</td>
                        <td>Customer Name</td>
                        <td>Customer Phone</td>
                        <td>Order Date</td>
                        <td>Order Status</td>
                        <td>Action</td>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($orders as $orderItem): ?>
                    <tr>
                        <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                        <td><?= $orderItem['name']; ?></td>
                        <td><?= $orderItem['phone']; ?></td>
                        <td><?= date('d M Y H:i A', strtotime($orderItem['order_date'] . '')); ?>
                        </td>
                        <td><?= $orderItem['order_status']; ?></td>
                        <td>
                            <a href="view-order.php?track=<?= $orderItem['tracking_no']; ?>"
                                class="btn btn-info mb-0 px-2 btn-sm">View</a>
                            <a href="view-order-print.php?track=<?= $orderItem['tracking_no']; ?>"
                                class="btn btn-primary mb-0 px-2 btn-sm">Print</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
                    
                }
            }
            else{
                echo '<h5>No records found.</h5>';
            }
            
            ?>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>