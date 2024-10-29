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

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
$stmt->execute();
$fetchedData = $stmt->fetchAll();

if($_POST){
    if(empty($_POST['name']) || empty($_POST['description'])){
        if(empty($_POST['name'])){
            $nameErr = "Name is required.";
        }
        if(empty($_POST['description'])){
            $descErr = "You need to fill description.";
        }
    }else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("UPDATE categories SET name=:name, description=:description WHERE id='$id'");
        $result = $stmt->execute(
                    array(
                        ":name" => $name,
                        ":description" => $description
                    )
                );
        if($result){
            echo "<script>alert('New category is updated.');window.location.href = 'category.php';</script>";
        }
    }
}
?>

<?php include("header.php"); ?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
              <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" name="name" value="<?php echo $fetchedData[0]['name']; ?>">
                <div class="invalid-feedback">
                  <?php echo $nameErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Description</label>
                <textarea type="text" rows="8" class="form-control <?php echo $descErr ? 'is-invalid' : ''; ?>" name="description"><?php echo $fetchedData[0]['description']; ?></textarea>
                <div class="invalid-feedback">
                  <?php echo $descErr; ?>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-info">Update</button>
                <a href="category.php" type="button" class="btn btn-warning">Back</a>
              </div>
            </form>
          </div>
        </div>
        <!-- /.card -->

      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<?php include("footer.html"); ?>
