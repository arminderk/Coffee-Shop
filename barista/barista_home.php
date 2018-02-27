<?php
  include('../header.php');
  session_start();

  if($_SESSION['dName'] != null) {
    
?>

    <!---------- Navbar ---------->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="#">Tsarbucks</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="barista_home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
          </li>
          <li class='nav-item'>
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
      <h5>Welcome Barista! Please select the icons above to navigate to the appropriate page</h5>
    </div>

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
    
  
