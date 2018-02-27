<?php

  if(isset($_POST['login'])) {
    
    function validateFormData($formData) {
        $formData = trim(stripslashes(htmlspecialchars($formData)));
        return $formData;
    }
    
    // Form Entries
    $formUser = validateFormData($_POST['username']);
    $formPass = validateFormData($_POST['password']);
    
    // Connect to Database
    include('connection.php');
    
    // The SQL Query
    $query = "SELECT * FROM users WHERE username='$formUser'";
    
    // The result of the connection and query
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
      
      while($row = mysqli_fetch_assoc($result)) {
        $userName = $row['username'];
        $hashedPassword = $row['password'];
        $displayName = $row['display_name'];
        $userID = $row['user_id'];
      }
      
      /********** Checking if Password is Correct **********/
      if($formPass == $hashedPassword) {
        session_start();
        
        $_SESSION["loggedInUser"] = $userName;
        $_SESSION["dName"] = $displayName;
        $_SESSION["u_id"] = $userID;
        
        if($userName == "customer") {
          $_SESSION["cart_id"] = $userID;
          header('Location: customer/customer_home.php');
        }
        else if($userName == "barista") {
          header('Location: barista/barista_home.php');
        }
        
      }
      else {   
          //error message
          $loginError = "<div class='alert alert-danger'>Wrong username / password combination. Please try again.</div>";
      }
    }
  }
  
  else {     
    //There are no results in the database
    $loginError = "<div class='alert alert-danger'>No such user in database. Please try again. <a class='close' data-dismiss='alert'>&times</a></div>";
  }

  include('header.php');

?>

  <!---------- Navbar ---------->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">TsarBucks</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
        </li>
      </ul>

      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.php"><i class='fa fa-sign-in' aria-hidden='true'></i>Login</a>
        </li>
      </ul>  
    </div>
  </nav>

  <br><br>
    
  <!---------- Login Form ---------->
  <div class="container">

    <h1 class="text-center">Login</h1>

    <br><br>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <div class="form-group">
        <label for="inputUserName">Username</label>
        <input type="text" class="form-control" id="inputUserName" placeholder="Enter Username" name="username">
        <small id="usernameHelp" class="form-text text-muted">We'll never share your username with anyone else.</small>
      </div>
      <div class="form-group">
        <label for="inputPassword">Password</label>
        <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password">
      </div>
      <button type="submit" class="btn btn-primary" name="login">Login</button>
    </form>
  </div>
