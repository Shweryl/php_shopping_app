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

$catstmt = $pdo->prepare("SELECT * FROM categories");
$catstmt->execute();
$catresults = $catstmt->fetchAll();
if($_POST){
    if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category_id']) ||
    empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image'])){
        if(empty($_POST['name'])){
            $nameErr = "Name is required.";
        }
        if(empty($_POST['description'])){
            $descErr = "You need to fill description.";
        }
        if(empty($_POST['category_id'])){
            $categoryErr = "You need to select at least one category";
        }
        if(empty($_POST['quantity'])){
            $quantityErr = "You need to state how many items are available.";
        }elseif(is_numeric($_POST['quantity']) != 1){
            $quantityErr = "Quamtity should be number";
        }
        if(empty($_POST['price'])){
            $priceErr = "You need to mention the value of the item.";
        }elseif(is_numeric($_POST['price']) != 1){
            $priceErr = "Price should be number";
        }
        if(empty($_FILES['image'])){
            $imageErr = "You need to upload picture of item.";
        }
    }else{

        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file, PATHINFO_EXTENSION);
        if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'jfif'){
            echo "<script>alert('Image should be one of png or jpg or jpeg')</script>";
        }else{
            $name = $_POST['name'];
            $description = $_POST['description'];
            $category_id = $_POST['category_id'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            $image = $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $file);
            $stmt = $pdo->prepare("INSERT INTO products(name, description, category_id, quantity, price, image) VALUES(:name, :description, :category_id, :quantity, :price, :image)");
            $result = $stmt->execute(
                        array(
                            ":name" => $name,
                            ":description" => $description,
                            ":category_id" => $category_id,
                            ":quantity" => $quantity,
                            ":price" => $price,
                            ":image" => $image
                        )
                    );
            if($result){
                echo "<script>alert('New product is added.');window.location.href = 'index.php';</script>";
            }
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
            <form action="product_add.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" name="name">
                <div class="invalid-feedback">
                  <?php echo $nameErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Description</label>
                <textarea type="text" rows="8" class="form-control <?php echo $descErr ? 'is-invalid' : ''; ?>" name="description"></textarea>
                <div class="invalid-feedback">
                  <?php echo $descErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Category</label>
                <select name="category_id" id="" class="form-control <?php echo $categoryErr ? 'is-invalid' : ''; ?>">
                    <option value="">Select Category</option>
                    <?php
                        if($catresults){
                            foreach($catresults as $category){
                    ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php        
                            }
                        }
                    ?>
                </select>
                <div class="invalid-feedback">
                  <?php echo $categoryErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Instock</label>
                <input type="number" class="form-control <?php echo $quantityErr ? 'is-invalid' : ''; ?>" name="quantity">
                <div class="invalid-feedback">
                  <?php echo $quantityErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Price</label>
                <input type="number" class="form-control <?php echo $priceErr ? 'is-invalid' : ''; ?>" name="price">
                <div class="invalid-feedback">
                  <?php echo $priceErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Image</label>
                <input type="file" name="image" class="form-control <?php echo $imageErr ? 'is-invalid' : ''; ?>">
                <div class="invalid-feedback">
                  <?php echo $imageErr; ?>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-info">Submit</button>
                <a href="index.php" type="button" class="btn btn-warning">Back</a>
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
