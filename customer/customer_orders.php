<?php
  include('../header.php');
  include('../connection.php');
  session_start();

  /***** Order Info. *****/
  $query = "SELECT orders.order_id, GROUP_CONCAT(' ', orders.quantity, '<br>' SEPARATOR '') AS quantity, GROUP_CONCAT(' ', orders.completed) AS completed, GROUP_CONCAT(' ', products.display_name , '<br>' SEPARATOR '') AS name, GROUP_CONCAT(' ', products.price, '<br>' SEPARATOR '') AS price, GROUP_CONCAT(' ', products.size, '<br>' SEPARATOR '') AS size FROM orders INNER JOIN products ON orders.product_id=products.product_id WHERE user_id='{$_SESSION[u_id]}' GROUP BY orders.order_id";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0) {
    $userOrders = array();
    while($row = mysqli_fetch_object($result)) {
      array_push($userOrders, $row);
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
          <li class='nav-item'>
            <a class='nav-link' href='customer_menu.php'><i class='fa fa-book' aria-hidden='true'></i>Menu</a>
          </li>
          <li class='nav-item active'>
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

    <br><br>
    
    <div class="container">
      <h1><u>My Orders</u></h1>
      
      <br><br>
      
      <?php if(count($userOrders) != 0) { ?>

        <?php foreach($userOrders as $userOrder) { ?>
            <h4>Order <?php echo $userOrder->order_id ?> </h4>
            <table class="table table-bordered">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Products</th>
                  <th scope="col">Size (oz)</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Price</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row"><?php echo $userOrder->name ?></th>
                  <td><?php echo $userOrder->size ?></td>
                  <td><?php echo $userOrder->quantity ?></td>
                  <td><?php echo $userOrder->price ?></td>
                  <td><?php foreach(explode(",", $userOrder->completed) as $order) { 
                              if($order == 1) {
                      ?>
                                <span class="badge badge-success">Completed</span>
                                <br>
                      <?php   } 
                              else {
                      ?>  
                                <span class="badge badge-secondary">Pending</span>
                                <br>
                      <?php
                              }
                            }
                      ?>
                  </td>
                </tr>
              </tbody>
            </table>
            <?php

                $size = 0;
                $i = 0;

                $j = 0;
                $total = 0;

                $quantities = explode("<br>", $userOrder->quantity);
                $sizes = explode("<br>", $userOrder->size);
                $productPrices = explode("<br>", $userOrder->price);                                  



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
            }
            else {
              echo "<p>You have no pending orders!";
            }
        ?>

    </div>
    
    