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

if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    // print_r("this worked");exit;
    setcookie('search', $search, time() + (86400 * 30), "/");
} else {
    if (empty($_GET['pageno'])) {
        // print_r("this worked?");exit;
        unset($_COOKIE['search']);
        setcookie('search', "", time() - 3600, '/');
    }
}
?>

<?php include("header.php"); ?>
<?php
if (!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$numOfRecord = 5;
$offset = ($pageno - 1) * $numOfRecord;

if (empty($_POST['search']) && empty($_COOKIE['search'])) {
    $stmp = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
    $stmp->execute();
    $rawResults = $stmp->fetchAll();
    $total_pages = ceil(count($rawResults) / $numOfRecord);

    $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$numOfRecord");
    $stmt->execute();
    $results = $stmt->fetchALL();
} else {
    $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
    $stmp = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
    $stmp->execute();
    $rawResults = $stmp->fetchAll();
    $total_pages = ceil(count($rawResults) / $numOfRecord);

    $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecord");
    $stmt->execute();
    $results = $stmt->fetchALL();
}


?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Category Listing</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="category_add.php" class="btn btn-success mb-2">New Category</a>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th style="width: 40px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($results) {
                                    $i = 1;
                                    foreach ($results as $value) {
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo escape($value['name']); ?></td>
                                            <td><?php echo escape(substr($value['description'], 0, 50)) . '...'; ?></td>
                                            <td class="">
                                                <div class="d-flex">
                                                    <a href="category_edit.php?id=<?php echo $value['id']; ?>" type="button" class="btn btn-info mr-2">Edit</a>
                                                    <a href="category_delete.php?id=<?php echo $value['id']; ?>" onclick="return confirm('Are you sure to delete')" type="button" class="btn btn-danger">Delete</a>
                                                </div>
                                            </td>
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