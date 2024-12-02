<?php include('includes/header.php'); ?>


<div class="container-fluid px-4">


    <!-- Add Customer Modal  -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="customers-code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">

                            <div class="mb-3">
                                <label for="name" class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone </label>
                                <input type="number" class="form-control" name="phone" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <!-- <div class="form-check"> -->
                                <label class="form-check-label" for="status">Status(Checked=Inactive)</label>
                                <br>
                                <input class="form-check-input" class="form-check" type="checkbox"
                                    style="height: 30px; width: 30px;" name="status">
                                <!-- </div> -->
                            </div>
                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name="saveCustomer" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Add Customer</button>
                </div> -->
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Customers
                <!-- Add Customer Modal Trigger Button -->
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                    data-bs-target="#addCustomerModal">
                    Add Customer
                </button>
            </h4>
        </div>
        <div class="card-body">

            <?php 
            $customers = getAll('customers');
            if(!$customers){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if(mysqli_num_rows($customers)> 0) {
                            
                        
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <td class="fw-bold">#</td>
                            <td class="fw-bold">Name</td>
                            <!-- <td>description</td> -->
                            <td class="fw-bold">Status</td>
                            <td class="fw-bold">Actions</td>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                            $i = 1;
                            foreach($customers as $customer):

                            
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $customer['name'] ?></td>
                            <td>
                                <?php
                                if ($customer['status'] == 1) {
                                    echo '<span class="badge bg-danger">Hidden</span>';
                                } else {
                                    echo '<span class="badge bg-success">Visible</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <!-- Update Button (Modal Trigger) -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#updateCustomerModal<?= $customer['id'] ?>">
                                    <i class="fas fa-edit fa-1x"></i>
                                </button>

                                <!-- Delete Button (Modal Trigger) -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteCustomerModal<?= $customer['id'] ?>">
                                    <i class="fas fa-trash fa-1x"></i>
                                </button>
                            </td>
                        </tr>


                        <!-- Update Modal -->

                        <div class="modal fade" id="updateCustomerModal<?= $customer['id'] ?>" tabindex="-1"
                            aria-labelledby="updateModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateModalLabel">Update Customer</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="Customers-code.php" method="POST">
                                            <?php
                                            $customerId = $customer['id'];
                                            $customerData = getByID('customers', $customerId);
                                            if ($customerData && $customerData['status'] == 200) :
                                        ?>
                                            <div class="row">
                                                <div class="mb-3">
                                                    <input type="number" class="form-control"
                                                        value="<?= $customerData['data']['id']; ?>" name="customerId"
                                                        hidden>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="name">Name *</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $customerData['data']['name']; ?>" name="name"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="">Email *</label>
                                                    <input type="email" class="form-control"
                                                        value="<?= $customerData['data']['email']; ?>" name="email"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="">Password *</label>
                                                    <input type="password" class="form-control" name="password">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="">Phone Number *</label>
                                                    <input type="number" class="form-control"
                                                        value="<?= $customerData['data']['phone']; ?>" name="phone">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="">Status</label>
                                                    <br>
                                                    <input type="checkbox" style="height: 30px; width: 30px;"
                                                        name="status"
                                                        <?= $customerData['data']['status'] == true ? 'checked': ''; ?>>
                                                </div>
                                                <div class="col-md-12 mb-3 text-end">
                                                    <button type="submit" name="updateCustomer"
                                                        class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                            <?php else : ?>
                                            <h5><?= $customerData['message']; ?></h5>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteCustomerModal<?= $customer['id'] ?>" tabindex="-1"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Delete customer</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this Customer?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <!-- Link to code.php for delete operation -->
                                        <a href="customers-code.php?id=<?= $customer['id'] ?>"
                                            class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>



            <?php 
                        
                }
                else{
                    ?>
            <tr>
                <h4 class=" mb-0">No Record Found!.</h4>
            </tr>
            <?php
                }
                ?>
        </div>
    </div>

    <?php alertMessage();  ?>


</div>


<?php include('includes/footer.php'); ?>