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

$currentDate = date('Y-m-d');
$fromDate = date('Y-m-d', strtotime($currentDate . '+1 day'));
$toDate = date('Y-m-d', strtotime($currentDate . '-1 month'));

$stmp = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date BETWEEN '$toDate' AND '$fromDate' ORDER BY id DESC");
$stmp->execute();
$rawResults = $stmp->fetchAll();
$total_pages = ceil(count($rawResults) / $numOfRecord);

$stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date BETWEEN '$toDate' AND '$fromDate' ORDER BY id DESC LIMIT $offset,$numOfRecord");
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
                        <h3 class="card-title">Monthly Report</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>User</th>
                                    <th>Total Price</th>
                                    <th>Order-date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($results) {
                                    $i = 1;
                                    foreach ($results as $value) {
                                        $userstmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $value['user_id']);
                                        $userstmt->execute();
                                        $user = $userstmt->fetchAll();
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo escape($user[0]['name']); ?></td>
                                            <td><?php echo escape($value['total_price']); ?></td>
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