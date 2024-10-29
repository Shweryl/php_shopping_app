<?php require("config/config.php"); ?>
<?php require("header.php"); ?>
<?php
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header('Location:login.php');
}
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$category = $pdo->prepare("SELECT * FROM categories WHERE id=" . $result['category_id']);
$category->execute();
$categorydata = $category->fetch(PDO::FETCH_ASSOC);

// print "<pre>";
// print_r($_SESSION['cart']);
?>
<!--================Single Product Area =================-->
<div class="product_image_area mb-3 pt-0">
	<div class="container">
		<div class="row s_product_inner">
			<div class="col-lg-6 p-4">
				<img class="" style="width: 100%;" src="admin/images/<?php echo $result['image']; ?>" alt="">
			</div>
			<div class="col-lg-5 offset-lg-1">
				<div class="s_product_text">
					<h3><?php echo $result['name']; ?></h3>
					<h2><?php echo $result['price']; ?></h2>
					<ul class="list">
						<li><span>Category</span> : <?php echo $categorydata['name']; ?></li>
						<li><span>Instock</span> : <?php echo $result['quantity']; ?></li>
					</ul>
					<p><?php echo $result['description']; ?></p>
					<form action="addtocart.php" method="post">
						<input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
						<input type="hidden" class="" name="id" value="<?php echo $result['id']; ?>">
						<div class="product_count">
							<label for="qty">Quantity:</label>
							<input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
							<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
								class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
							<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
								class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
						</div>
						<div class="card_area d-flex align-items-center">
							<button class="primary-btn border-0">Add to Cart</button>
							<a class="primary-btn" href="index.php">Back</a>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
<!--================End Single Product Area =================-->
<?php require("footer.php"); ?>