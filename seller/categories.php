<?php include('includes/header.php'); ?>


<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Categories
                <a href="add-category.php" class="btn btn-primary float-end">Add Category</a>
            </h4>
        </div>
        <div class="card-body">

            <?php 
            $categories = getAll('categories');
            if(!$categories){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if(mysqli_num_rows($categories)> 0) {
                            
                        
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <td>#</td>
                            <td>Name</td>
                            <td>description</td>
                            <td>Status</td>
                            <td>Actions</td>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach($categories as $category):?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><?= $category['name'] ?></td>
                            <td><?= $category['description'] ?></td>
                            <td>
                                <?php
                                if ($category['status'] == 1) {
                                    echo '<span class="badge bg-danger">Hidden</span>';
                                } else {
                                    echo '<span class="badge bg-success">Visible</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <!-- Update Button (Modal Trigger) -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#updateCategoryModal<?= $category['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete Button (Modal Trigger) -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteCategoryModal<?= $category['id'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>

                        </tr>




                        <!-- Update Category Modal -->
                        <div class="modal fade" id="updateCategoryModal<?= $category['id'] ?>" tabindex="-1"
                            aria-labelledby="updateCategoryModalLabel<?= $category['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateCategoryModalLabel<?= $category['id'] ?>">
                                            Update Category</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="categories-code.php" method="POST">
                                            <?php
                                            $categoryID = $category['id'];
                                            $categoryData = getByID('categories', $categoryID);
                                            if ($categoryData && $categoryData['status'] == 200) :
                                            ?>
                                            <input type="hidden" class="form-control"
                                                value="<?= $categoryData['data']['id']; ?>" name="categoryId">
                                            <div class="mb-3">
                                                <label for="name">Name *</label>
                                                <input type="text" class="form-control"
                                                    value="<?= $categoryData['data']['name']; ?>" name="name" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="description">Description *</label>
                                                <textarea class="form-control" name="description" rows="3"
                                                    placeholder="Enter product category description"><?= $categoryData['data']['description']; ?></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="is_ban">Status(Checked=Visible, Unchecked=Hidden)</label>
                                                <br>
                                                <input type="checkbox" style="height: 30px; width: 30px;" name="status"
                                                    <?= $categoryData['data']['status'] == true ? 'checked' : ''; ?>>
                                            </div>
                                            <div class="col-md-12 mb-3 text-end">
                                                <button type="submit" name="updateCategory"
                                                    class="btn btn-primary">Save</button>
                                            </div>
                                            <?php else : ?>
                                            <h5><?= $categoryData['message']; ?></h5>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteCategoryModal<?= $category['id'] ?>" tabindex="-1"
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
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <!-- Link to code.php for delete operation -->
                                        <a href="categories-code.php?id=<?= $category['id'] ?>"
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