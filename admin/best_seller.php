<?php
session_start();
require("config/config.php");
require("config/common.php");
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("Location: login.php");
}
if ($_SESSION['role'] != 1) {
    header("Location: login.php");
}

?>

<!-- Attaching header -->
<?php include("header.php"); ?>

<!-- Retrieving post data with pagination and
Retrieving searched data with pagination -->
<?php
if (!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$numOfRecord = 5;
$offset = ($pageno - 1) * $numOfRecord;

$stmp = $pdo->prepare("SELECT id, SUM(quantity) as total_qty, product_id, order_date FROM sale_order_detail GROUP BY product_id HAVING SUM(quantity) >= 4 ORDER BY total_qty DESC");
$stmp->execute();
$rawResults = $stmp->fetchAll();
$total_pages = ceil(count($rawResults) / $numOfRecord);

$stmt = $pdo->prepare("SELECT id, SUM(quantity) as total_qty, product_id, order_date FROM sale_order_detail GROUP BY product_id HAVING SUM(quantity) >= 4 ORDER BY total_qty DESC LIMIT $offset,$numOfRecord");
$stmt->execute();
$results = $stmt->fetchALL();

?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Royal Customer</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Product Name</th>
                                    <th>Total Quantity</th>
                                    <th>Order-date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($results) {
                                    $i = 1;
                                    foreach ($results as $value) {
                                        $productstmt = $pdo->prepare("SELECT * FROM products WHERE id=" . $value['product_id']);
                                        $productstmt->execute();
                                        $product = $productstmt->fetchAll();
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo escape($product[0]['name']); ?></td>
                                            <td><?php echo escape($value['total_qty']); ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($value['order_date'])); ?></td>
                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation example" class="mt-3 float-right">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                <li class="page-item <?php if ($pageno <= 1) {
                                                            echo "disabled";
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($pageno <= 1) {
                                                                    echo "#";
                                                                } else {
                                                                    echo "?pageno=" . ($pageno - 1);
                                                                } ?>">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                                <li class="page-item <?php if ($pageno >= $total_pages) {
                                                            echo "disabled";
                                                        } ?>">
                                    <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                                    echo "#";
                                                                } else {
                                                                    echo "?pageno=" . ($pageno + 1);
                                                                } ?>">Next</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
                            </ul>
                        </nav>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<?php include("footer.html"); ?>