<?php
session_start();
require("config/config.php");
require("config/common.php");
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("Location: login.php");
  }
  if($_SESSION['role'] != 1) {
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
$stmp = $pdo->prepare("SELECT * FROM sale_order_detail ORDER BY id DESC");
$stmp->execute();
$rawResults = $stmp->fetchAll();
$total_pages = ceil(count($rawResults) / $numOfRecord);

$stmt = $pdo->prepare("SELECT * FROM sale_order_detail ORDER BY id DESC LIMIT $offset,$numOfRecord");
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
                        <h3 class="card-title">Order Detail</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="order_list.php" type="button" class="btn btn-success">Back</a>
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Order Date</th>
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
                                            <td><?php echo escape($value['quantity']); ?></td>
                                            <td><?php echo date('Y-m-d',strtotime($value['order_date'])); ?></td>
                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

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