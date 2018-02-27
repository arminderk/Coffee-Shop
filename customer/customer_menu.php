<?php
  include('../header.php');
  include('../connection.php');
  session_start();

//  if($_SESSION['dName'] != null) {
    
    $productQuery = "SELECT * FROM products";
    $productResult = mysqli_query($conn, $productQuery);
    
    if(mysqli_num_rows($productResult) > 0) {
      
      $products = array();
      
      while($row = mysqli_fetch_object($productResult)) {
        array_push($products, $row);
      }
    }

    if(isset($_POST['insert'])) { 
      $qQuery = "SELECT quantity FROM cart WHERE product_id='{$_POST[product_id]}'";
      $qResult = mysqli_query($conn, $qQuery);
      
      if(mysqli_num_rows($qResult) == 0) {
        
        $cartQuery = "INSERT INTO cart (user_id, cart_id, product_id) VALUES ('{$_POST[user_id]}', '{$_POST[cart_id]}', '{$_POST[product_id]}')";
        
        $cartResult = mysqli_query($conn, $cartQuery);
      }
      else {
        while($row = mysqli_fetch_assoc($qResult)) {
          $q = $row['quantity'];
        }
        $increment = $q+1;
        $updateQuantity = "UPDATE cart SET quantity='$increment' WHERE product_id='{$_POST[product_id]}'";
        $updateResult = mysqli_query($conn, $updateQuantity);
      }
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
          <li class='nav-item active'>
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
          <li class='nav-item'>
            <a class='nav-link' href="customer_cart.php"><i class="fa fa-cart-plus" aria-hidden="true"></i>Cart</a>
          </li>
          <li class='nav-item'>
            <a class='nav-link' href='../logout.php'><i class='fa fa-sign-out' aria-hidden='true'></i>Logout</a>
          </li>
        </ul> 

      </div>
    </nav>

    <div class="container">
      
      <br><br>
      
      <?php
        if($cartQuery || $updateQuantity) {
          echo "<div class='alert alert-success'>Item Added to Cart</div>";
        }
      ?>

      <h1><u>Menu</u></h1>

      <br><br>
      
      <?php 
        if(count($products) != 0) {
      ?>

          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th scope="col">Product Name</th>
                <th scope="col">Size (oz)</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($products as $product) { ?>
                <tr>
                  <th scope="row"><?php echo $product->display_name ?></th>
                  <td><?php echo $product->size ?></td>
                  <td><?php echo $product->price ?></td>
                  <td>
                  <form method="post">
                    <input type="hidden" value="<?php echo $product->product_id?>" name="product_id">
                    <input type="hidden" value="<?php echo $_SESSION['u_id'] ?>" name="user_id">
                    <input type="hidden" value="<?php echo $_SESSION['cart_id'] ?>" name="cart_id">
                    <button class="btn btn-primary" type="submit" name="insert"><i class="fa fa-plus-circle" aria-hidden="true" style="margin-right: 5px;"></i>Add to Cart</button>
                  </form>
                </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
      
      <?php 
        }
      ?>