<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Make a Booking</title>

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
if (isset($_POST['bookBtn'])) {
    include_once 'config/connection.php';

    $query = "INSERT INTO Booking(b_status, start_date, end_date, member_id, property_id)
             VALUES ('requested',?,?,?,?)";

    if($stmt = $con->prepare($query)){
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ssss", $_POST['startDate'], $_POST['endDate'], $_SESSION['member_id'], $_GET['property_id']);

        // Execute the query
        $stmt->execute();

        header("Location: my-confirmations.php");
        die();
    }
    else { echo "<h2 class='error-msg'>There was an error placing your booking!</h2>"; }
}
if(isset($_GET['property_id'])){
    // include database connection
    include_once 'config/connection.php'; 

    $query = "SELECT * FROM Properties WHERE Properties.property_id = ?";
    $query2 = "SELECT start_date, end_date FROM Booking NATURAL JOIN Properties WHERE property_id = ? AND Booking.b_status = 'confirmed'";

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
        
        $startend = array();
        $week = array();
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt2->bind_Param("i", $_GET['property_id']);

        // Execute the query
        $stmt2->execute();
 
        /* resultset */
        $result2 = $stmt2->get_result();

        $row2 = $result2->fetch_assoc();
        
        while(!empty($row2)){
            $startend['start'] = $row2['start_date'];
            $startend['end'] = $row2['end_date'];
            array_push($week, $startend);
            $row2 = $result2->fetch_assoc();
        }
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
            <li><a href="profile.php">Account Details</a></li>
            <li><a href="search.php">Search for a property</a></li> 
            <li class="active"><a href="my-bookings.php"> Bookings</a></li> 
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
    
<div class='row'>
    <div class='col-md-9 text-vertical-center-b'>
        <form name='book' id='book' action='booking.php?property_id=<?php echo $_GET['property_id']; ?>' method='post'>
            <fieldset class="form-inline">
            <br>
                <label for="startDate"> When would you like to start your stay? :</label>
                <input type="date" class="form-control" name="startDate" id="startDate" placeholder="YYYY-MM-DD">
                <label for="endDate"> When will you be moving out? : </label>
                <input type="date" class="form-control" name="endDate" id="endDate" placeholder="YYYY-MM-DD">
            </fieldset>
            <br>
            <button name="bookBtn" id="bookBtn" type="submit" class="btn btn-primary">Book Property</button>
        </form>
    </div>
    <div class='col-md-3'>
    <h2> Current unavailable dates are :</h2>
    <?php
        while(!empty($week)){
            $thisweek = array_shift($week);
            echo $thisweek['start'] . " to " . $thisweek['end'];
            echo '<br>';

        }
    ?>
    </div>
</div>


</body>
</html>