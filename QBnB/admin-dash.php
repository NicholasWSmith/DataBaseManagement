<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Dashboard</title>

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
          <a class="navbar-brand" href="admin-dash.php">Queens Bed and Brekkie</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="admin-dash.php">Admin panel</a></li>  
            <li><a href="index.php?logout=1">Log out</a></li>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<aside class="callout-s">
    <div class="text-vertical-center">
    </div>
</aside>

<div class="contained">
<?php
 //check if the user is already logged in and has an active session
if(!isset($_SESSION['member_id']) || $_SESSION['member_id'] != -1){ //change this to just admin id, not sure how to implement? - Kristian
    //Redirect the browser to the profile editing page and kill this page.
    header("Location: index.php");
    die();
}

if (isset($_GET['query']) AND $_GET['query'] == 1) {
    include_once 'config/connection.php'; 

    $query = "SELECT member_id FROM ServiceMembers WHERE member_id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_Param("s", $_POST['user']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows <= 0) {
        echo "<p>No such user exists.<p>";
    }
    else if (isset($_POST['deleteMemberBtn'])) {
        $query = "DELETE FROM ServiceMembers WHERE member_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_Param("s", $_POST['user']);
        $stmt->execute();
    }
    else if (isset($_POST['listSupplier'])) {
        $query = "SELECT *, avg(Comments.rating)  AS rating FROM ServiceMembers INNER JOIN rents_out ON ServiceMembers.member_id = rents_out.member_id
                                         INNER JOIN Properties ON Properties.property_id = rents_out.property_id
                                         INNER JOIN property_comment ON property_comment.property_id = rents_out.property_id
                                         INNER JOIN Comments ON Comments.comment_id = property_comment.comment_id
                                         WHERE Comments.rating IS NOT NULL AND ServiceMembers.member_id=?
                                         GROUP BY Properties.property_id";
        
        $query2 = "SELECT Properties.address, count(Booking.property_id) AS num_bookings FROM ServiceMembers
                       INNER JOIN rents_out ON ServiceMembers.member_id = rents_out.member_id
                       INNER JOIN Properties ON Properties.property_id = rents_out.property_id
                       INNER JOIN Booking ON Properties.property_id = Booking.property_id
                   WHERE ServiceMembers.member_id=?
                   GROUP BY Properties.property_id";

        $stmt = $con->prepare($query);
        $stmt->bind_Param("s", $_POST['user']);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = $_POST['user'];
        echo "<h2>Average property ratings for user $user:</h2>";
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            $property = $row['address'];
            $average = $row['rating'];
            echo "<li>$property - Average rating: $average</li>";
        }
        echo "</ul>";

        $stmt = $con->prepare($query2);
        $stmt->bind_Param("s", $_POST['user']);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h2>Number of bookings for user $user:</h2>";
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            $average = $row['num_bookings'];
            echo "<li>$average</li>";
        }
        echo "</ul>";
    }

    else if (isset($_POST['listConsumer'])) {

        $query = "SELECT * FROM Booking INNER JOIN ServiceMembers ON Booking.member_id = ServiceMembers.member_id
                    INNER JOIN Properties ON Properties.property_id = Booking.property_id
                    WHERE ServiceMembers.member_id=?";

        $stmt = $con->prepare($query);

        $stmt->bind_Param("s", $_POST['user']);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = $_POST['user'];

        echo "<h2>Bookings for user $user:</h2>";
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            $property = $row['address'];
            $status = $row['b_status'];
            $startDate = $row['start_date'];
            $endDate = $row['end_date'];
            echo "<li>$property - $startDate to $endDate - $status</li>";
        }
        echo "</ul>";
    }
    else {
        die();
    }
}
else if (isset($_GET['query']) AND $_GET['query'] == 2) {
    if (isset($_POST['deleteAccomodationBtn'])) {
        include_once 'config/connection.php';
        $query = "DELETE FROM Properties WHERE property_id=?";
        $stmt = $con->prepare($query);    
        $stmt->bind_Param("s", $_POST['property']);
        $stmt->execute();
    }
    else { echo "no property with that id";}
}

?>
</div>
<div class="bd-example">
    <form name='userStuff' id='userStuff' action="admin-dash.php?query=1" method='post'>
        <fieldset class="form-group">
            <label for="user">User ID</label>
            <input type="text" class="form-control" name="user" id="user" placeholder="Enter user id" required autofocus/>
        </fieldset>

        <button name="deleteMemberBtn" id="deleteMemberBtn" type="submit" class="btn btn-primary">Delete member</button>
        <button name="listConsumer" id="listConsumer" type="submit" class="btn btn-primary">List metrics for a consumer</button>
        <button name="listSupplier" id="listSupplier" type="submit" class="btn btn-primary">List metrics for a supplier</button>
    </form>
</div><br>
<div class="bd-example">
    <form name='deleteAccom' id='deleteAccom' action="admin-dash.php?query=2" method='post'>
        <fieldset class="form-group">
            <label for="property">Property ID</label>
            <input type="number" class="form-control" name="property" id="property" placeholder="Enter property id" required/>
        </fieldset>

        <button name="deleteAccomodationBtn" id="deleteAccomodationBtn" type="submit" class="btn btn-primary">Delete accomodation</button>
    </form>
</div>
</body>
</html>