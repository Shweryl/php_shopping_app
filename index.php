<?php
require("config/config.php");
?>

<?php require("header.php"); ?>

<?php
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header('Location:login.php');
}
if (!empty($_GET['category'])) {
	setcookie('category', $_GET['category'], time() + (86400 * 30), "/");
} else {
	if (empty($_GET['pageno'])) {
		unset($_COOKIE['category']);
		setcookie('category', "", time() - 3600, '/');
	}
}
if (!empty($_POST['search'])) {
	setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
	if (empty($_GET['pageno'])) {
		unset($_COOKIE['search']);
		setcookie('search', "", time() - 3600, '/');
	}
}
if (!empty($_GET['pageno'])) {
	$pageno = $_GET['pageno'];
} else {
	$pageno = 1;
}
$numOfRecord = 10;
$offset = ($pageno - 1) * $numOfRecord;

if (empty($_POST['search']) && empty($_COOKIE['search'])) {
	if (empty($_GET['category']) && empty($_COOKIE['category'])) {
		$stmp = $pdo->prepare("SELECT * FROM products WHERE quantity != 0 ORDER BY id DESC");
	} else {
		$category = empty($_GET['category']) ? $_COOKIE['category'] : $_GET['category'];
		$stmp = $pdo->prepare("SELECT * FROM products WHERE category_id='$category' AND quantity != 0 ORDER BY id DESC");
	}

	$stmp->execute();
	$rawResults = $stmp->fetchAll();
	$total_pages = ceil(count($rawResults) / $numOfRecord);

	if (empty($_GET['category']) && empty($_COOKIE['category'])) {
		$stmt = $pdo->prepare("SELECT * FROM products WHERE quantity != 0 ORDER BY id DESC LIMIT $offset,$numOfRecord");
	} else {
		$category = empty($_GET['category']) ? $_COOKIE['category'] : $_GET['category'];
		$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id='$category' AND quantity != 0 ORDER BY id DESC LIMIT $offset,$numOfRecord");
	}

	$stmt->execute();
	$results = $stmt->fetchALL();
} else {

	$searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
	if (empty($_GET['category']) && empty($_COOKIE['category'])) {
		$stmp = $pdo->prepare("SELECT * FROM products WHERE quantity != 0 AND WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
	} else {
		$category = empty($_GET['category']) ? $_COOKIE['category'] : $_GET['category'];
		$stmp = $pdo->prepare("SELECT * FROM products WHERE quantity != 0 AND WHERE category_id='$category' AND WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
	}

	$stmp->execute();
	$rawResults = $stmp->fetchAll();
	$total_pages = ceil(count($rawResults) / $numOfRecord);

	if (empty($_GET['category']) && empty($_COOKIE['category'])) {
		$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecord");
	} else {
		$category = empty($_GET['category']) ? $_COOKIE['category'] : $_GET['category'];
		$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id='$category' AND WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecord");
	}

	$stmt->execute();
	$results = $stmt->fetchALL();
}


?>

<div class="col-xl-3 col-lg-4 col-md-5">
	<div class="sidebar-categories">
		<div class="head">Browse Categories</div>
		<ul class="main-categories">
			<li class="main-nav-list">
				<?php
				$catstmt = $pdo->prepare("SELECT * FROM categories");
				$catstmt->execute();
				$catresults = $catstmt->fetchAll();
				foreach ($catresults as $value) { ?>
					<a type="button" href="index.php?category=<?php echo $value['id']; ?>"><span class="lnr lnr-arrow-right"></span><?php echo $value['name']; ?></a>

				<?php
				}
				?>
			</li>
		</ul>
	</div>

</div>
<div class="col-xl-9 col-lg-8 col-md-7">
	<!-- Start Filter Bar -->
	<div class="filter-bar d-flex flex-wrap align-items-center">
		<div class="pagination">
			<a class="" href="?pageno=1">First</a>
			<a <?php if ($pageno <= 1) {
					echo "disabled";
				} ?> href="<?php if ($pageno <= 1) {
								echo "#";
							} else {
								echo "?pageno=" . ($pageno - 1);
							} ?>" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
			<a href="#" class="active"><?php echo $pageno; ?></a>
			<a <?php if ($pageno >= $total_pages) {
					echo "disabled";
				} ?> href="<?php if ($pageno >= $total_pages) {
								echo "#";
							} else {
								echo "?pageno=" . ($pageno + 1);
							} ?>" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
			<a class="" href="?pageno=<?php echo $total_pages ?>">Last</a>
		</div>
	</div>
	<!-- End Filter Bar -->
	<!-- Start Best Seller -->
	<section class="lattest-product-area pb-40 category-list">
		<div class="row">
			<?php
			foreach ($results as $product) {
			?>
				<div class="col-lg-4 col-md-6">
					<div class="single-product">
						<a href="product_detail.php?id=<?php echo $product['id']; ?>">
							<img class="" style="width: 100%;height: 250px !important;" src="admin/images/<?php echo $product['image'] ?>" alt="">
						</a>
						<div class="product-details">
							<h6><?php echo $product['name'] ?></h6>
							<div class="price">
								<h6><?php echo $product['price'] ?></h6>
								<h6 class="l-through"><?php echo $product['price'] ?></h6>
							</div>
							<div class="prd-bottom">

								<form action="addtocart.php" method="post">
									<input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
									<input type="hidden" name="id" value="<?php echo $product['id']; ?>">
									<input type="hidden" name="qty" value="1">
									<div class="social-info">
										<button style="display: contents" type="submit">
											<span class="ti-bag"></span>
											<p class="hover-text" style="left: 20px;">add to bag</p>
										</button>
									</div>
									<a href="product_detail.php?id=<?php echo $product['id']; ?>" class="social-info">
										<span class="lnr lnr-move"></span>
										<p class="hover-text">view more</p>
									</a>
								</form>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>

		</div>
	</section>
	<!-- End Best Seller -->
	<?php require("footer.php"); ?>