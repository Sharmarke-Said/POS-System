<?php include('includes/header.php'); ?>


<div class="container-fluid px-4">



    <h1 class="mt-4">Dashboard</h1>



    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card card-body bg-primary text-white p-3">
                <!-- Add the flex-column and justify-content-between classes to the card body -->
                <div class="d-flex flex-column justify-content-between">
                    <!-- Add the d-flex and align-items-center classes to the first flex item -->
                    <div class="d-flex align-items-center">
                        <!-- Add the mr-2 class to the card name -->
                        <p class="text-sm mb-0 text-capitalize font-weight-bold mx-4">Total Categories</p>
                        <i class="fa fa-cubes float-right fa-2x"></i>
                    </div>
                    <!-- Add the mt-2 class to the second flex item -->
                    <h5 class="font-weight mb-0 mt-2 text-center">
                        <?= getCount('categories'); ?>
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body bg-success text-white p-3">
                <!-- Add the flex-column and justify-content-between classes to the card body -->
                <div class="d-flex flex-column justify-content-between">
                    <!-- Add the d-flex and align-items-center classes to the first flex item -->
                    <div class="d-flex align-items-center">
                        <!-- Add the mr-2 class to the card name -->
                        <p class="text-sm mb-0 text-capitalize font-weight-bold mx-4">Total Products</p>
                        <!-- Change the icon to fa fa-shopping-cart -->
                        <i class="fa fa-shopping-cart float-right fa-2x"></i>
                    </div>
                    <!-- Add the mt-2 class to the second flex item -->
                    <h5 class="font-weight mb-0 mt-2 text-center">
                        <?= getCount('products'); ?>
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 ">
            <div class="card card-body bg-info text-white p-3">
                <!-- Add the flex-column and justify-content-between classes to the card body -->
                <div class="d-flex flex-column justify-content-between">
                    <!-- Add the d-flex and align-items-center classes to the first flex item -->
                    <div class="d-flex align-items-center">
                        <!-- Add the mr-2 class to the card name -->
                        <p class="text-sm mb-0 text-capitalize font-weight-bold mx-4">Total Admins</p>
                        <!-- Change the icon to fa fa-user-shield -->
                        <i class="fa fa-user-shield float-right fa-2x"></i>
                    </div>
                    <!-- Add the mt-2 class to the second flex item -->
                    <h5 class="font-weight mb-0 mt-2 text-center">
                        <?= getCount('admins'); ?>
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body bg-warning text-white p-3">
                <!-- Add the flex-column and justify-content-between classes to the card body -->
                <div class="d-flex flex-column justify-content-between">
                    <!-- Add the d-flex and align-items-center classes to the first flex item -->
                    <div class="d-flex align-items-center">
                        <!-- Add the mr-2 class to the card name -->
                        <p class="text-sm mb-0 text-capitalize font-weight-bold mx-4">Total Customers</p>
                        <!-- Change the icon to fa fa-user-friends -->
                        <i class="fa fa-user-friends float-right fa-2x"></i>
                    </div>
                    <!-- Add the mt-2 class to the second flex item -->
                    <h5 class="font-weight mb-0 mt-2 text-center">
                        <?= getCount('customers'); ?>
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3 mb-1">
            <h5>Orders</h5>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body bg-danger text-white p-3">
                <!-- Add the flex-column and justify-content-between classes to the card body -->
                <div class="d-flex flex-column justify-content-between">
                    <!-- Add the d-flex and align-items-center classes to the first flex item -->
                    <div class="d-flex align-items-center">
                        <!-- Add the mr-2 class to the card name -->
                        <p class="text-sm mb-0 text-capitalize font-weight-bold mx-4">Today's Orders</p>
                        <!-- Change the icon to fa fa-calendar-check -->
                        <i class="fa fa-calendar-check float-right fa-2x"></i>
                    </div>
                    <!-- Add the mt-2 class to the second flex item -->
                    <h5 class="font-weight mb-0 mt-2 text-center">
                        <?php
            $todayDate = date('Y-m-d');
            $todayOrders = mysqli_query($conn, "SELECT * FROM orders WHERE DATE(order_date) = '$todayDate'");

            if ($todayOrders) {
                if (mysqli_num_rows($todayOrders) > 0) {
                    $totalCountOrders = mysqli_num_rows($todayOrders);
                    echo $totalCountOrders;
                } else {
                    echo "0";
                }
            } else {
                echo '<h5>Something went wrong</h5>';
            }
            ?>
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card card-body bg-dark text-white p-3">
                <!-- Add the flex-column and justify-content-between classes to the card body -->
                <div class="d-flex flex-column justify-content-between">
                    <!-- Add the d-flex and align-items-center classes to the first flex item -->
                    <div class="d-flex align-items-center">
                        <!-- Add the mr-2 class to the card name -->
                        <p class="text-sm mb-0 text-capitalize font-weight-bold mx-4">Total Orders</p>
                        <!-- Change the icon to fa fa-receipt -->
                        <i class="fa fa-receipt float-right fa-2x"></i>
                    </div>
                    <!-- Add the mt-2 class to the second flex item -->
                    <h5 class="font-weight mb-0 mt-2 text-center">
                        <?= getCount('orders'); ?>
                    </h5>
                </div>
            </div>
        </div>

    </div>





    <?php include('includes/footer.php'); ?>