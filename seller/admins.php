<?php include('includes/header.php'); ?>


<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminModalLabel">Add Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="code.php" method="POST">
                    <div class="row">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class=" mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class=" mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class=" mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="number" class="form-control" name="phone" required>
                        </div>
                        <div class=" mb-3">
                            <!-- <div class="form-check"> -->
                            <label class="form-check-label" for="is_active">Status</label>
                            <br>
                            <input class="form-check-input" class="form-check" type="checkbox"
                                style="height: 30px; width: 30px;" name="is_active">
                            <!-- </div> -->
                        </div>
                        <div class="col-md-12 mb-3 text-end">
                            <button type="submit" name="saveAdmin" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Add Admin</button>
                </div> -->
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="card mt-4 mb-2 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Admins/Staff
                <!-- Add Admin Modal Trigger Button -->
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                    data-bs-target="#addAdminModal">
                    Add Admin
                </button>
            </h4>
        </div>
        <div class="card-body">

            <?php 
            $admins= getAll('admins');
            if(!$admins){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if(mysqli_num_rows($admins)> 0) {
                            
                        
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <td>#</td>
                            <td>Name</td>
                            <td>Email</td>
                            <td>Status</td>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach($admins as $admin):?>
                        <tr>
                            <td><?= $admin['id'] ?></td>
                            <td><?= $admin['name'] ?></td>
                            <td><?= $admin['email'] ?></td>
                            <td>
                                <?php
                                if ($admin['is_ban'] == 1) {
                                    echo '<span class="badge bg-danger">Banned</span>';
                                } else {
                                    echo '<span class="badge bg-success">Active</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <!-- Your action buttons here -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#updateModalAdmin<?=$admin['id']?>">
                                    <i class="fas fa-edit"></i>
                                    <!-- Update -->
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModalAdmin<?=$admin['id']?>">
                                    <i class="fas fa-trash"></i>
                                    <!-- Delete -->
                                </button>
                            </td>
                        </tr>





                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Update Modal -->

                <div class="modal fade" id="updateModalAdmin<?= $admin['id'] ?>" tabindex="-1"
                    aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Admin</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="code.php" method="POST">
                                    <?php
                                            $adminId = $admin['id'];
                                            $adminData = getByID('admins', $adminId);
                                            if ($adminData && $adminData['status'] == 200) :
                                        ?>
                                    <div class="row">
                                        <div class="mb-3">
                                            <input type="number" class="form-control"
                                                value="<?= $adminData['data']['id']; ?>" name="adminId" hidden>
                                        </div>
                                        <div class="mb-3">
                                            <label for="name">Name *</label>
                                            <input type="text" class="form-control"
                                                value="<?= $adminData['data']['name']; ?>" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Email *</label>
                                            <input type="email" class="form-control"
                                                value="<?= $adminData['data']['email']; ?>" name="email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Password *</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Phone Number *</label>
                                            <input type="number" class="form-control"
                                                value="<?= $adminData['data']['phone']; ?>" name="phone">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Status</label>
                                            <br>
                                            <input type="checkbox" style="height: 30px; width: 30px;" name="is_ban"
                                                <?= $adminData['data']['is_ban'] == true ? 'checked': ''; ?>>
                                        </div>
                                        <div class="col-md-12 mb-3 text-end">
                                            <button type="submit" name="updateAdmin"
                                                class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                    <?php else : ?>
                                        <h5><?= $adminData['message'] ?></h5>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModalAdmin<?= $admin['id'] ?>" tabindex="-1"
                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Admin</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this admin?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <!-- Link to code.php for delete operation -->
                                <a href="code.php?id=<?= $admin['id'] ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>


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