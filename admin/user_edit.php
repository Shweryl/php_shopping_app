<?php
session_start();
require("../config/config.php");
require("../config/common.php");
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("Location: login.php");
}
if($_SESSION['role'] != 1) {
  header("Location: login.php");
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['email']) || !isset($_POST['role'])) {
    if (empty($_POST['name'])) {
      $nameErr = "Name is required.";
    }
    if (empty($_POST['email'])) {
      $emailErr = "Email is required.";
    }
    if (!isset($_POST['role'])) {
      $roleErr = "You need to check at least one.";
    }
  } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
    $passwordErr = "Password should be at least four characters.";
  } else {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email='$email' AND id != '$id'");
    $stmt->execute();
    $data = $stmt->fetchAll();
    if ($data) {
      echo "<script>alert('Email is already used.Create with another email.')</script>";
    } else {
      if (empty($_POST['password'])) {
        $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', role='$role' WHERE id='$id'");
      } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', password='$password', role='$role' WHERE id='$id'");
      }

      $result = $stmt->execute();
      if ($result) {
        echo "<script>alert('Successfully updated');window.location.href='user_list.php';</script>";
      }
    }
  }
}
?>

<?php include("header.php"); ?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form action="" method="post">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <input type="hidden" class="" name="id" value="<?php echo $result[0]['id']; ?>">
              <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" name="name" value="<?php echo escape($result[0]['name']); ?>" required>
                <div class="invalid-feedback">
                  <?php echo $nameErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control <?php echo $emailErr ? 'is-invalid' : ''; ?>" name="email" value="<?php echo escape($result[0]['email']); ?>">
                <div class="invalid-feedback">
                  <?php echo $emailErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Password</label>
                <div>
                  <small>This user already has password.</small>
                </div>
                <input type="password" class="form-control <?php echo $passwordErr ? 'is-invalid' : ''; ?>" name="password" placeholder="Fill Password">
                <div class="invalid-feedback">
                  <?php echo $passwordErr; ?>
                </div>
              </div>
              <div class="form-group">
                <label for="">Do you want to assign this user admin?</label><br>
                <span>Yes</span>
                <input type="checkbox" class="" name="role" value="1" <?php if ($result[0]['role'] == 1) {echo "checked";} ?>>
                <span class="ml-3">No</span>
                <input type="checkbox" class="" name="role" value="0" <?php if ($result[0]['role'] == 0) {echo "checked";} ?>>
                <?php
                  if(!empty($roleErr)){
                ?>
                <div class="text-danger">
                  <small><?php echo $roleErr; ?></small>
                </div>
                <?php
                  }
                ?>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-info">Update</button>
                <a href="user_list.php" type="button" class="btn btn-warning">Back</a>
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
