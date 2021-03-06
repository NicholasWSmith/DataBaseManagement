<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>My bookings</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/stylish-portfolio.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
<body>



 <?php
  //Create a user session or resume an existing one
 session_start();

 ?>


    <!-- Fixed toolbar -->
    <nav class="navbar navbar-toolbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"> Home </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li ><a href="profile.php">Account Details</a></li>
            <li><a href="search.php">Search for a property</a></li> 
            <li class="active" ><a href="my-bookings.php"> Bookings</a></li> 
            <li><a href="my-confirmations.php">Manage rental requests</a></li>
            <li><a href="my-properties.php"> Properties</a></li> 
            <li><a href="index.php?logout=1">Log out</a></li>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <!-- Callout -->
    <aside class="callout2">
        <div class="text-vertical-center">
        <br> 
            <br>
            <br>
            <br>
        </div>
    </aside>


<?php
    if(!isset($_SESSION['member_id'])){
        header("Location: index.php");
        die();
    }

    include_once 'config/connection.php'; 
    
    // SELECT query
    $query = "SELECT * FROM Booking INNER JOIN Properties ON Booking.property_id = Properties.property_id WHERE Booking.member_id = ?";
    // prepare query for execution
    if($stmt = $con->prepare($query)){
        
        $stmt->bind_Param("s", $_SESSION['member_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                $propertyName = $row["address"];
                $start = $row["start_date"];
                $end = $row["end_date"];
                echo "<li>$propertyName - From $start to $end</li>";
            }
            echo "</ul>";
        }
        else {
            echo "<h2>You have no bookings.</h2>";

        }
    }
?>


</body>