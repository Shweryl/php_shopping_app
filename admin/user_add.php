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
  if($_POST){
      if(empty($_POST['name']) || empty($_POST['email']) || !isset($_POST['role']) || empty($_POST['password'])){
        if(empty($_POST['name'])){
          $nameErr = "Name is required.";
        }
        if(empty($_POST['email'])){
          $emailErr = "Email is required.";
        }
        if(!isset($_POST['role'])){
          $roleErr = "You need to check at least one.";
        }
        if(empty($_POST['password'])){
          $passwordErr = "Password is required.";
        }
      }elseif(strlen($_POST['password']) < 4){
        $passwordErr = "Password should be at least four characters.";
      }else{
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email='$email'");
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          echo "<script>alert('Email is already used.Create with another email.')</script>";
        }else{
          $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(:name, :email, :password, :role)");
          $result = $stmt->execute(
            array(
              ':name' => $name,
              ':email' => $email,
              ':password' => $password,
              ':role' => $role,
            )
          );
          if($result){
            echo "<script>alert('Successfully added');window.location.href='user_list.php';</script>";
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
                <form action="User_add.php" method="post">
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" name="name" placeholder="Fill Username">
                    <div class="invalid-feedback">
                      <?php echo $nameErr; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" class="form-control <?php echo $emailErr ? 'is-invalid' : ''; ?>" name="email" placeholder="Fill Email">
                    <div class="invalid-feedback">
                      <?php echo $emailErr; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control <?php echo $passwordErr ? 'is-invalid' : ''; ?>" name="password" placeholder="Fill Password">
                    <div class="invalid-feedback">
                      <?php echo $passwordErr; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="">Do you want to assign this user admin?</label><br>
                    <span>Yes</span>
                    <input type="checkbox" class="" name="role" value="1">
                    <span class="ml-3">No</span>
                    <input type="checkbox" class="" name="role" value="0">
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
                    <button type="submit" class="btn btn-info">Submit</button>
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
