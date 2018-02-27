<?php
  include('../header.php');
  include('../connection.php');
  session_start();

  /***** Cart Product ID Info *****/
  $query = "SELECT * FROM cart WHERE user_id='{$_SESSION[u_id]}'";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0) {
    $cartProduct = array();
    while($row = mysqli_fetch_assoc($result)) {
      array_push($cartProduct, $row['product_id']);
      $cartQuantity[$row['product_id']] = $row['quantity'];
    }
  }
  
  /***** Product Info. *****/
  $pQuery = "SELECT * FROM products WHERE product_id IN (" . implode(',', $cartProduct) . ")";
  $pResult = mysqli_query($conn, $pQuery);

  if(mysqli_num_rows($pResult) > 0) {
    $products = array();
    while($row = mysqli_fetch_object($pResult)) {
      array_push($products, $row);
    }
  }

  /***** Delete Products *****/
  if(isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM cart WHERE product_id='{$_POST[product_id]}'";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    echo "<meta http-equiv='refresh' content='0'>";
  }

  /***** Order Info. *****/
  $selectOrder = "SELECT DISTINCT order_id FROM orders";
  $oResult = mysqli_query($conn, $selectOrder);

  if(mysqli_num_rows($oResult) > 0) {
    $orders = array();
    while($row = mysqli_fetch_object($oResult)) {
      array_push($orders, $row);
    }
  }
  
  /***** Place the Order And Delete All Items in Cart *****/
  if(isset($_POST['order-submit'])) {
    $orderCount = count($orders);
    $orderIncrement = $orderCount+1;
    foreach ($cartProduct as $cProduct) {
      $submitOrder = "INSERT INTO orders (order_id, user_id, product_id, quantity, completed) VALUES ('$orderIncrement', '{$_SESSION[u_id]}', '$cProduct', '{$cartQuantity[$cProduct][$row['quantity']]}', 0)";
      $submitResult = mysqli_query($conn, $submitOrder);
    }
    
    $deleteProducts = "DELETE FROM cart WHERE user_id='{$_SESSION[u_id]}'";
    $deleteResult = mysqli_query($conn, $deleteProducts);
    
    echo "<meta http-equiv='refresh' content='0'>";
  }

?>

  <!---------- Navbar ---------->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Tsarbucks</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="customer_home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='customer_menu.php'><i class='fa fa-book' aria-hidden='true'></i>Menu</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='customer_orders.php'><i class="fa fa-archive" aria-hidden="true"></i>My Orders</a>
        </li>
      </ul>


      <ul class='navbar-nav'>
        <li class='nav-item'>
          <span class='nav-link'>Welcome <?php echo $_SESSION['dName'] ?></span>
        </li>
        <li class='nav-item active'>
          <a class='nav-link' href="customer_cart.php"><i class="fa fa-cart-plus" aria-hidden="true"></i>Cart</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='../logout.php'><i class='fa fa-sign-out' aria-hidden='true'></i>Logout</a>
        </li>
      </ul> 
      
    </div>
  </nav>

  <br><br>
  
  <div class="container">
    
    <h1><u>Your Cart</u></h1>
    
    <br><br>
    
    <?php 
      if(count($products) != 0) {
    ?>

        <table class="table table-bordered">
          <thead class="thead-light">
            <tr>
              <th scope="col">Product Name</th>
              <th scope="col">Size (oz)</th>
              <th scope="col">Quantity</th>
              <th scope="col">Price</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($products as $product) { ?>
              <tr>
                <th scope="row"><?php echo $product->display_name ?></th>
                <td><?php echo $product->size ?></td>
                <td><?php echo $cartQuantity[$product->product_id][$row['quantity']] ?></td>
                <td><?php echo $product->price ?></td>
                <td>
                <form method="post">
                  <input type="hidden" value="<?php echo $product->product_id ?>" name="product_id">
                  <button class="btn btn-danger" type="submit" name="delete"><i class="fa fa-minus-circle" aria-hidden="true" style="margin-right: 5px;"></i>Remove</button>
                </form>
              </td>
              </tr>
              
            <?php
              $quantityPrice = $cartQuantity[$product->product_id][$row['quantity']] * $product->price;
              $totalPrice += $quantityPrice;
              $size = $cartQuantity[$product->product_id][$row['quantity']] * $product->size;
              $totalSize += $size;
            } 
            ?>
          </tbody>
        </table>
        
        <p class="text-right" style="margin:0;">Total Price: <strong><?php echo "$" . money_format('%i', $totalPrice) . "\n"; ?></strong></p>
        <p class="text-right">Total Size: <strong><?php echo $totalSize . "oz" ?></strong></p>
    
        <form method="post">
          <input type="hidden" value="<?php ?>" name="">
          <button class="btn btn-primary" type="submit" name="order-submit"><i class="fa fa-plus-circle" aria-hidden="true" style="margin-right: 5px;"></i>Submit Order</button>
        </form>
      
      <?php 
        }
        else {
          echo "<p>No items in your cart!</p>";
        }
      ?>
    
  </div>