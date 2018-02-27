<?php

  include('header.php');
  session_start();
  
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
          <a class="nav-link" href="home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
        </li>
      </ul>
      
      <ul class='navbar-nav'>
        <li class='nav-item'>
          <a class='nav-link' href='index.php'><i class='fa fa-sign-in' aria-hidden='true'></i>Login</a>
        </li>
      </ul>
    </div>
  </nav>

  <br><br>

  <div class="container">
    <p>You Must Be Logged In</p>
  </div>
