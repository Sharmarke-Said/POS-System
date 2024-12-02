<?php include('includes/header.php'); ?>


<div class="container-fluid px-4">


    <!-- Add Product Modal  -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdminModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="products-code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Category *</label>
                                <select class="form-select" name="category_id">
                                    <option value="">select category</option>
                                    <?php

                                    $categories = getAll('categories');

                                    if ($categories) {
                                        if (mysqli_num_rows($categories) > 0) {
                                            foreach ($categories as $category) {
                                                echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No Categoris found.</option>';
                                        }
                                    } else {
                                        echo '<option value="">select category</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>


                            <div class=" mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <!-- <input type="" class="form-control" name="phone" required> -->
                                <textarea class="form-control" name="description" rows="3" placeholder="Enter product category description"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Price *</label>
                                <input type="number" class="form-control" name="price" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Quantity *</label>
                                <input type="number" class="form-control" name="quantity" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Image *</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                            <div class="col-md-6 mb-3">
                                <!-- <div class="form-check"> -->
                                <label class="form-check-label" for="status">Status(Checked=Hidden)</label>
                                <br>
                                <input class="form-check-input" class="form-check" type="checkbox" style="height: 30px; width: 30px;" name="status">
                                <!-- </div> -->
                            </div>
                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name="saveProduct" class="btn btn-primary">Save</button>
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

    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Products
                <!-- Add Product Modal Trigger Button -->
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    Add Product
                </button>
            </h4>
        </div>
        <div class="card-body">

            <?php
            $products = getAll('products');
            if (!$products) {
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }

            if (mysqli_num_rows($products) > 0) {


            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <td>#</td>
                                <td>Image</td>
                                <td>Name</td>
                                <td>Status</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($products as $product) : ?>
                                <tr>
                                    <td><?= $product['id'] ?></td>
                                    <td>
                                        <img src="../<?= $product['image'] ?>" style="width: 60px; height: 60px;">
                                    </td>
                                    <td><?= $product['name'] ?></td>

                                    <td>
                                        <?php
                                        if ($product['status'] == 1) {
                                            echo '<span class="badge bg-danger">Hidden</span>';
                                        } else {
                                            echo '<span class="badge bg-success">Visible</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <!-- Update Button (Modal Trigger) -->
                                        <button type=" button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateProductModal<?= $product['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Delete Button (Modal Trigger) -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal<?= $product['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>




                                <!-- Update Product Modal -->
                                <div class="modal fade" id="updateProductModal<?= $product['id']; ?>" tabindex="-1" aria-labelledby="updateProductModal<?= $product['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateProductModal<?= $product['id'] ?>">
                                                    Update Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <form action="products-code.php?id=<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">

                                                    <?php
                                                    $productID = $product['id'];
                                                    $productData = getByID('products', $productID);
                                                    if ($productData && $productData['status'] == 200) :
                                                    ?>
                                                        <input type="hidden" class="form-control" value="<?= $productData['data']['id']; ?>" name="productId">

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="name" class="form-label">Category *</label>
                                                                <select class="form-select" name="category_id">
                                                                    <option value="">Select category</option>
                                                                    <?php
                                                                    $categories = getAll('categories');

                                                                    if ($categories) {
                                                                        if (mysqli_num_rows($categories) > 0) {
                                                                            foreach ($categories as $category) {
                                                                    ?>
                                                                                <option value="<?= $category['id']; ?>" <?= $productData['data']['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                                                                    <?= $category['name']; ?>
                                                                                </option>
                                                                    <?php
                                                                            }
                                                                        } else {
                                                                            echo '<option value="">No Categories found.</option>';
                                                                        }
                                                                    } else {
                                                                        echo '<option value="">Error fetching categories.</option>';
                                                                    }
                                                                    ?>
                                                                </select>

                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="name">Name *</label>
                                                                <input type="text" class="form-control" value="<?= $productData['data']['name']; ?>" name="name" required>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label for="description">Description *</label>
                                                                <textarea class="form-control" name="description" rows="3" placeholder="Enter product category description"><?= $productData['data']['description']; ?></textarea>
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label for="name">Price *</label>
                                                                <input type="text" class="form-control" value="<?= $productData['data']['price']; ?>" name="price" required>
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label for="name">Quantity *</label>
                                                                <input type="text" class="form-control" value="<?= $productData['data']['quantity']; ?>" name="quantity" required>
                                                            </div>

                                                            <div class="mb-3 col-md-6">
                                                                <label for="name" class="form-label">Image *</label>
                                                                <input type="file" class="form-control" name="image">
                                                                <img src="../<?= $productData['data']['image']; ?>" alt="img" style="height: 40px; width: 40px;">
                                                            </div>


                                                            <div class="col-md-6 mb-3">
                                                                <label for="is_ban">Status(Checked=Hidden)</label>
                                                                <br>
                                                                <input type="checkbox" style="height: 30px; width: 30px;" name="status" <?= $productData['data']['status'] == true ? 'checked' : ''; ?>>
                                                            </div>
                                                            <div class=" col-md-12 mb-3 text-end">
                                                                <button type="submit" name="updateProduct" class="btn btn-primary">Save</button>
                                                            </div>
                                                        </div>


                                                    <?php else : ?>
                                                        <h5><?= $productData['message']; ?></h5>
                                                    <?php endif; ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteCategoryModal<?= $product['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Delete
                                                    Admin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this product?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <!-- Link to code.php for delete operation -->
                                                <a href="products-code.php?id=<?= $product['id'] ?>" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>





                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php

            } else {
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