<?php
  include('../header.php');
  include('../connection.php');
  session_start();

  if($_SESSION['dName'] != null) {

    $orderQuery = "SELECT orders.order_id, GROUP_CONCAT(' ', products.product_id) AS product_id, GROUP_CONCAT(' ', orders.quantity, '<br>', '<br>', '<br>' SEPARATOR '') AS quantity, GROUP_CONCAT(' ', products.display_name , '<br>', '<br>', '<br>' SEPARATOR '') AS name, GROUP_CONCAT(' ', products.price, '<br>', '<br>', '<br>' SEPARATOR '') AS price, GROUP_CONCAT(' ', products.size, '<br>', '<br>', '<br>' SEPARATOR '') AS size FROM orders INNER JOIN products ON orders.product_id=products.product_id WHERE completed=0 GROUP BY orders.order_id";
    
    // The result of the connection and query
    $result = mysqli_query($conn, $orderQuery);

    if(mysqli_num_rows($result) > 0) {
      
      $orders = array();

      while($row = mysqli_fetch_object($result)) {  
       array_push($orders, $row);
      }

    }

    if(isset($_POST['update'])) {        
      $completeQuery = "UPDATE orders SET completed=1 WHERE product_id='{$_POST[product_id]}'";
      $completeResult = mysqli_query($conn, $completeQuery);

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
            <a class="nav-link" href="barista_home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
          </li>
          <li class='nav-item active'>
            <a class='nav-link' href='barista_pending.php'><i class='fa fa-book' aria-hidden='true'></i>Pending Orders</a>
          </li>
        </ul>


        <ul class='navbar-nav'>
          <li class='nav-item'>
            <span class='nav-link'>Welcome <?php echo $_SESSION['dName'] ?></span>
          </li>
          <li class='nav-item'>
            <a class='nav-link' href='../logout.php'><i class='fa fa-sign-out' aria-hidden='true'></i>Logout</a>
          </li>
        </ul> 

      </div>
    </nav>

    <br><br>

    <div class="container">

      <h1><u>Pending Orders</u></h1>

      <br><br>
      
      <?php 
        if(count($orders) != 0) {
      ?>

        <?php foreach($orders as $order) { ?>
          <h4>Order <?php echo $order->order_id ?> for Customer</h4>
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
              <tr>
                <th scope="row"><?php echo $order->name ?></th>
                <td><?php echo $order->size ?></td>
                <td><?php echo $order->quantity ?></td>
                <td><?php echo "$" . $order->price ?></td>
                <td>
                  <?php foreach(explode(",", $order->product_id) as $product) { ?>
                          <form method="post">
                            <input type="hidden" value="<?php echo $product?>" name="product_id">
                            <button class="btn btn-success btn-sm" type="submit" name="update">Mark Complete</button>
                          </form>
                  <?php } ?>
                </td>
              </tr>
            </tbody>
          </table>

          <?php

            $size = 0;
            $i = 0;

            $j = 0;
            $total = 0;

            $quantities = explode("<br>", $order->quantity);
            $sizes = explode("<br>", $order->size);
            $productPrices = explode("<br>", $order->price);                                  



            while($i < count($sizes)) {
              $size = $size + ($quantities[$i] * $sizes[$i]);        
              $i++;
            }

            while($j < count($productPrices)) {
              $total = $total + ($quantities[$j] * $productPrices[$j]);
              $j++;
            }                          

            $totalPrice = $total;
            $totalSize = $size;
        ?>

            <p class="text-right" style="margin:0;">Total Price: <strong><?php echo "$" . money_format('%i', $totalPrice) . "\n"; ?></strong></p>
            <p class="text-right">Total Size: <strong><?php echo $totalSize . "oz" ?></strong></p>

          <?php
            }
          ?>

        </div>
      <?php
            
        }
        else {
          echo "<p>There are no pending orders!</p>";
        }
      ?>
  <?php
      
    }

    else {
  ?>
      <div class="container">
        <br><br>
        <h3>Must Be Logged In</h3>
        <a href="../index.php">Click here to Login</a>
      </div>
  <?php } ?>
