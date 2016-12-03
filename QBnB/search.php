<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>qbnb - Search Properties</title>

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
            <li class="active"><a href="search.php">Search for a property</a></li> 
            <li><a href="my-bookings.php"> Bookings</a></li> 
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

    <div class="bd-example">
        <form name="search" id="search" action="search.php" method="post">
            <fieldset class="form-group">
                <label for="district">District</label>
                <select class="form-control" name="district" id="district">
                    <option></option>
                    <option>Azeroth</option>
                    <option>Hellfire Peninsula</optionHellfire Peninsula>
                    <option>Goldshire</option>
                    <option>Kingston</option>
                    <option>University</option>
                    <option>Other</option>
                </select>
            </fieldset>
            <fieldset class="form-group">
                <label for="bedroom">Desired # of rooms? </label>
                <input type="number" class="form-control" name="bedroom" id="bedroom" placeholder="0"/>
            </fieldset>
            <fieldset class="form-group">
                <label for="lodging">Property Type</label>
                <select class="form-control" name="lodging" id="lodging">
                    <option></option>
                    <option>House</option>
                    <option>Semi-Detached</option>
                    <option>Duplex</option>
                    <option>Apartment</option>
                    <option>Condo</option>
                    <option>Other</option>
                </select>
            </fieldset>
            <fieldset class="form-inline">
                <label for="minprice">Minimum Price:</label>
                <input type="number" class="form-control" name="minprice" id="minprice" placeholder="0">
                <label for="maxprice">Maximum Price:</label>
                <input type="number" class="form-control" name="maxprice" id="maxprice" placeholder="0">
            </fieldset><br>
        <button name="searchBtn" id="searchBtn" type="submit" class="btn btn-primary btn-block">Search Properties</button>
        </form>
    </div>
    <br>
<?php 
if(isset($_POST['searchBtn'])){
 
    // include database connection
    include_once 'config/connection.php'; 
    
    $wheres = array();

    // SELECT query
    $query = "SELECT * FROM Properties";

    if (!empty($_POST['district'])) {
        $wheres[] = 'district_name = "' . $_POST['district'] . '"';
        $distCheck = true;
    }
    if (!empty($_POST['bedroom'])) {
        $wheres[] = 'bedrooms = ' . $_POST['bedroom'];
        $bedCheck = true;
    }
    if (!empty($_POST['lodging'])) {
        $wheres[] = 'lodging = "' . $_POST['lodging'] . '"';
        $lodgeCheck = true;
    }
    if (!empty($_POST['minprice']) AND !empty($_POST['maxprice'])) {
        $wheres[] = '(price BETWEEN ' . $_POST['minprice'] . ' AND ' . $_POST['maxprice'] . ')';
        $priceCheck = true;
    }
    if (!empty($wheres)){
        $query .= " WHERE " . implode(" AND ", $wheres);
    }

    // prepare query for execution
    if($stmt = $con->prepare($query)){

        // Execute the query
        $stmt->execute();
 
        /* resultset */
        $result = $stmt->get_result();

        // Get the number of rows returned
        $num = $result->num_rows;
        echo '<div class="container-fluid">';
            for ($i = 0; $i < $num/4; $i++){
                $j = 0;
                echo '<div class="row">';
                while($j < 4){
                    $row = $result->fetch_assoc();
                    if(!empty($row)){
                        echo '<div class="col-sm-3 text-vertical-center-s">';
                        echo $row['address'] . "<br>";
                        echo $row['district_name'] . "<br>";
                        echo $row['bedrooms'] . "<br>";
                        echo $row['lodging'] . "<br>";
                        echo $row['price'] . "<br>";
                        echo '<form method="post" action="property.php?property_id=' . $row['property_id'] . '">';
                        echo '<button name="infoBtn" id="infoBtn" type="submit" class="btn btn-primary">More Info</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                $j++;
                }
                echo '</div>';
            }
        echo '</div>';
    }
    else{ echo 'error with sql statement';}
}
?>
</body>
</html>