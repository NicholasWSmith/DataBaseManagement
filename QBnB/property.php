<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Creating a Property</title>

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
 
<?php
 //check if the user is logged in - if not, redirect them to the home page
if(!isset($_SESSION['member_id'])){
    //Redirect the browser to the home page and kill this page.
    header("Location: index.php");
    die();
}
?>

<?php
if(isset($_GET['property_id'])){
    // include database connection
    include_once 'config/connection.php'; 
    
    // SELECT query
    $query = "SELECT * FROM Properties WHERE Properties.property_id = ?";
    $query2 = "SELECT * FROM (property_comment NATURAL JOIN ServiceMembers) INNER JOIN Comments ON Comments.comment_id = property_comment.comment_id WHERE property_comment.property_id = ?";
    $query3 = "SELECT Features.feature FROM Features LEFT JOIN Properties ON Features.property_id = Properties.property_id WHERE Properties.property_id=?";

    if($stmt = $con->prepare($query)){
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("i", $_GET['property_id']);

        // Execute the query
        $stmt->execute();
 
        /* resultset */
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
    }
    if($stmt2 = $con->prepare($query2)){
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt2->bind_Param("i", $_GET['property_id']);

        // Execute the query
        $stmt2->execute();
 
        /* resultset */
        $result2 = $stmt2->get_result();
    }
    if($stmt3 = $con->prepare($query3)){
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt3->bind_Param("i", $_GET['property_id']);

        // Execute the query
        $stmt3->execute();
 
        /* resultset */
        $result3 = $stmt3->get_result();
    }

}
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
            <li><a href="my-bookings.php"> Bookings</a></li> 
            <li><a href="my-confirmations.php">Manage rental requests</a></li>
            <li class="active"><a href="my-properties.php"> Properties</a></li> 
            <li><a href="index.php?logout=1">Log out</a></li>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <!-- Callout -->
    <aside class="callout2">
        <div class="text-vertical-center">
            <h2><?php echo $row['address']; ?></h1>
        </div>
    </aside>

<div class="container text-vertical-center-b">
    <h2>District: <?php echo $row['district_name']; ?></h2>
    <h2>Bedrooms: <?php echo $row['bedrooms']; ?></h2>
    <h2>Property Type: <?php echo $row['lodging']; ?></h2>
    <h2>Price: $<?php echo $row['price']; ?>/week</h2>
</div>
<div class="row">
    <div class="col-md-6">
        <h2>Features:</h2>
        <ul>
        <?php
        $row3 = $result3->fetch_assoc();
        while(!empty($row3)){
            echo "<li>" . $row3['feature'] . "</li>";
            $row3 = $result3->fetch_assoc();
        }
        ?>
        </ul>
    </div>
    <div class="col-md-6">
        <h2>Comments:</h2>
        <?php 
        $row2 = $result2->fetch_assoc();
        while(!empty($row2)){
            echo $row2['first_name'] . " " . $row2['last_name'] . " commented: " . $row2['comment_text'];
            if(!empty($row2['rating'])) {
                echo " - Rating: " . $row2['rating'] . "/5 stars<br>";
            }
            $row2 = $result2->fetch_assoc();
        }
        ?>


    </div>
</div>
<div class="container text-vertical-center-b">
    <br>
    <form method="post" action="booking.php?property_id=<?php echo $row['property_id']; ?>">
    <button name="infoBtn" id="infoBtn" type="submit" class="btn btn-primary">Book This Property</button>
    </form>

    <br> 

    <form method="post" action= "searching.php" >

    <br>
    <br>
    <br>
    <br>
    Add a comment! <br> 
    <input type = "text" name ="comment"><br>

    Enter a rating! <br> 
    <input type = "text" name ="rating"><br>

    <button name="infoBtn" id="infoBtn" type="submit" class="btn btn-primary">Submit comment and rating!</button>
    </form>
</div>
</body>
</html>