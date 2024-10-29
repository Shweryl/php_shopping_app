<?php
require('config/config.php');
require('header.php');
?>

<!--================Cart Area =================-->
<section class="cart_area pt-2">
    <div class="container">
        <div class="cart_inner">
            <div class="table-responsive">
                <?php
                if (!empty($_SESSION['cart'])) {
                ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($_SESSION['cart'] as $key => $qty) {
                                $id = str_replace('id', '', $key);
                                $stmt = $pdo->prepare("SELECT * FROM products WHERE id='$id'");
                                $stmt->execute();
                                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                                $total += $product['price'] * $qty;
                            ?>
                                <tr>
                                    <td>
                                        <div class="media">
                                            <div class="d-flex">
                                                <img src="admin/images/<?php echo $product['image']; ?>" width="100" height="100" alt="">
                                            </div>
                                            <div class="media-body">
                                                <p><?php echo $product['description']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5><?php echo $product['price']; ?></h5>
                                    </td>
                                    <td>
                                        <div class="product_count">
                                            <input type="text" name="qty" id="sst" maxlength="12" value="<?php echo $qty; ?>" title="Quantity:"
                                                class="input-text qty">
                                            <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                                class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
                                            <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                                                class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
                                        </div>
                                    </td>
                                    <td>
                                        <h5><?php echo $product['price'] * $qty; ?></h5>
                                    </td>
                                    <td>
                                        <a style="line-height: 40px;border-radius : 20px;" href="cart_item_clear.php?pid=<?php echo $product['id']; ?>" type="button" class="primary-btn">Clear</a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>


                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5><?php echo $total; ?></h5>
                                </td>
                            </tr>
                            <tr class="out_button_area">
                                <td></td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="gray_btn" style="text-wrap: nowrap;" href="clearAll.php">Clear All</a>
                                        <a class="primary-btn" href="index.php">Continue Shopping</a>
                                        <a class="gray_btn" style="text-wrap: nowrap;" href="order_submit.php">Order Submmit</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php

                }
                ?>
            </div>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->

<!-- start footer Area -->
<?php require('footer.php'); ?>