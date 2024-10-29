<?php
session_start();
include('../db_connect.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit();
}

// Lấy company_id từ URL nếu có
$company_id = isset($_GET['company_id']) ? $_GET['company_id'] : ''; 

// Lấy từ khóa tìm kiếm từ URL nếu có
$search_query = isset($_GET['search']) ? $_GET['search'] : ''; 

// Xây dựng truy vấn sản phẩm
$query = "SELECT p.*, c.company_name FROM product p LEFT JOIN company c ON p.company_id = c.company_id WHERE 1=1";
if ($company_id) {
    $query .= " AND p.company_id = $company_id";
}
if ($search_query) {
    $query .= " AND p.product_name LIKE '%$search_query%'";
}
$result = $connect->query($query); // Truy vấn sản phẩm

// Truy vấn danh sách công ty
$companies = $connect->query("SELECT * FROM company");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style_admin.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <h1 class="text-center mb-4">Manage Product</h1>
        <div class="card">
            <div style="display: flex; justify-content: space-around">
                <div class="card-header">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Thêm Sản Phẩm</button>
                </div>
                <form method="get" action="index.php" class="form-inline">
                    <input type="text" name="search" placeholder="Search..." class="form-control mr-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <form action="logout.php" method="post" class="form-inline">
                    <button type="submit" class="btn btn-danger" style="margin-top:20px">Logout</button>
                </form>
            </div>
            <div class="card-body">
                <!-- Danh sách công ty dưới dạng menu -->
                <ul>
                    <li>
                        <a href="index.php" style="text-align: center;">
                            Tất cả công ty
                        </a>
                    </li>
                    <?php while ($company = $companies->fetch_assoc()): ?>
                        <li>
                            <a href="?company_id=<?php echo $company['company_id'] ?>" style="text-align: center;">
                                <?php echo htmlspecialchars($company['company_name']); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <!-- Hiển thị bảng sản phẩm -->
                <?php if ($result->num_rows > 0): ?>
                    <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; text-align: center;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Company Name</th>
                                <th>Product Name</th>
                                <th>Product Price</th>
                                <th>Product Discount</th>
                                <th>Product Description</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php while ($product = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo htmlspecialchars($product['company_name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['product_price']); ?></td>
                                    <td><?php echo htmlspecialchars($product['product_discount']); ?></td>
                                    <td><?php echo htmlspecialchars($product['product_description']); ?></td>
                                    <td>
                                        <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" height="125px;" width="130px;" alt="Product Image">
                                    </td>
                                    <td>
                                        <a href="update.php?product_id=<?php echo $product['product_id']; ?>">Update</a> | 
                                        <a href="delete.php?product_id=<?php echo $product['product_id']; ?>">Delete</a>
                                    </td>
                                </tr>
                                <?php $count++; ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No products found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Sản Phẩm -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="productName">Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="form-group">
                            <label for="productPrice">Price</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                        </div>
                        <div class="form-group">
                            <label for="productDiscount">Discount</label>
                            <input type="number" class="form-control" id="productDiscount" name="productDiscount" required>
                        </div>
                        <div class="form-group">
                            <label for="productDescription">Description</label>
                            <input type="text" class="form-control" id="productDescription" name="productDescription" required>
                        </div>
                        <div class="form-group">
                            <label for="fileField">Image</label>
                            <input type="file" name="fileField" id="fileField" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Sản Phẩm -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="editProductId">
                        <div class="form-group">
                            <label for="editProductName">Name</label>
                            <input type="text" class="form-control" id="editProductName" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductPrice">Price</label>
                            <input type="number" class="form-control" id="editProductPrice" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductDiscount">Discount</label>
                            <input type="number" class="form-control" id="editProductDiscount" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductDescription">Description</label>
                            <input type="text" class="form-control" id="editProductDescription" required>
                        </div>
                        <div class="form-group">
                            <label for="editFileField">Image</label>
                            <input type="file" name="fileField" id="editFileField" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
