<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>My properties</title>

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
            <li class="active"><a href="profile.php">Account Details</a></li>
            <li><a href="search.php">Search for a property</a></li> 
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

<?php
    if(!isset($_SESSION['member_id'])){
        header("Location: index.php");
        die();
    }

    else if (isset($_POST['updateBtn'])) {
        include_once 'config/connection.php';
        $query = "UPDATE Properties SET address = ?, district_name = ?, bedrooms = ?, lodging = ?, price = ? WHERE property_id=?";
 
        // prepare query for execution
        $stmt = $con->prepare($query);
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ssssss",
                          $_POST['address'],
                          $_POST['dName'],
                          $_POST['bedrooms'],
                          $_POST['lodging'],
                          $_POST['price'],
                          $_GET['property_id']
            );

        // Execute the query
        $stmt->execute();
    }
    else if (isset($_POST['addBtn'])){
        include_once 'config/connection.php';

        $query = "INSERT INTO Properties(address, district_name, bedrooms, lodging, price, property_id)
                 VALUES (?,?,?,?,?, NULL)";

        if($stmt = $con->prepare($query)){
            
            // bind the parameters. This is the best way to prevent SQL injection hacks.
            $stmt->bind_Param("ssisi", $_POST['address'], $_POST['dName'], $_POST['bedrooms'], $_POST['lodging'], $_POST['price']);

            // Execute the query
            $stmt->execute();
            $id = $con->insert_id;

            $query2 = "INSERT INTO rents_out(member_id, property_id) VALUES (?,?)";
            if($stmt2 = $con->prepare($query2)){
            
                // bind the parameters. This is the best way to prevent SQL injection hacks.
                $stmt2->bind_Param("ii", $_SESSION['member_id'], $id);

                // Execute the query
                $stmt2->execute();

                $result2 = $stmt2->get_result();

                header("Location: my-confirmations.php");
                die();
            }
        }
        else { echo "<h2 class='error-msg'>There was an error.</h2>"; }

    }
    else if (isset($_POST['newBtn'])) { //add a property
        include_once 'config/connection.php';

        echo "<form name='addProperty' id='addProperty' action='my-properties.php?' method='post'>";
        echo "<fieldset class='form-group'>
                <label for='address'>Address</label>
                <input type='text' class='form-control' name='address' id='address' placeholder='123 Happily St' required/>
            </fieldset>";
        echo "<fieldset class='form-group'>
                <label for='dName'>District</label>
                <select class='form-control' name='dName' id='dName'>
                    <option>Azeroth</option>
                    <option>Hellfire Peninsula</optionHellfire Peninsula>
                    <option>Goldshire</option>
                    <option>Kingston</option>
                    <option>University</option>
                    <option>Other</option>
                </select>
            </fieldset>";
        echo "<fieldset class='form-group'>
                <label for='bedrooms'>Bedrooms</label>
                <input type='text' class='form-control' name='bedrooms' id='bedrooms' placeholder='0' required/>
            </fieldset>";
        echo "<label for='lodging'>Lodging Type</label>
                <select class='form-control' name='lodging' id='lodging'>
                    <option>House</option>
                    <option>Semi-Detached</option>
                    <option>Duplex</option>
                    <option>Apartment</option>
                    <option>Condo</option>
                    <option>Other</option>
                </select>";
        echo "<fieldset class='form-group'>
                <label for='price'>Price</label>
                <input type='number' class='form-control' name='price' id='price' placeholder='0' required/>
            </fieldset>";
        echo "<button name='addBtn' id='addBtn' type='submit' class='btn btn-primary'>Add Property</button>";
        echo "</form>";
    }
    else if (isset($_GET['property_id']) && isset($_POST['editBtn'])) { //edit a property
   // include database connection
    include_once 'config/connection.php'; 
    
    // SELECT query
        $query = "SELECT * FROM Properties WHERE property_id=?";
 
        // prepare query for execution
        $stmt = $con->prepare($query);
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_GET['property_id']);

        // Execute the query
        $stmt->execute();
 
        // results 
        $result = $stmt->get_result();
        
        // Row data
        $myrow = $result->fetch_assoc();


        echo "<form name='editProperty' id='editProperty' action='my-properties.php?property_id=" . $_GET['property_id'] . "' method='post'>";
        echo "<fieldset class='form-group'>
                <label for='address'>Address</label>
                <input type='text' class='form-control' name='address' id='address' value='" . $myrow["address"] . "' required/>
            </fieldset>";
        echo "<fieldset class='form-group'>
                <label for='district'>District</label>
                <select class='form-control' name='dName' id='dName'>
                    <option>" . $myrow['district_name'] . "</option>
                    <option>Azeroth</option>
                    <option>Hellfire Peninsula</optionHellfire Peninsula>
                    <option>Goldshire</option>
                    <option>Kingston</option>
                    <option>University</option>
                    <option>Other</option>
                </select>
            </fieldset>";
        echo "<fieldset class='form-group'>
                <label for='bedrooms'>Bedrooms</label>
                <input type='text' class='form-control' name='bedrooms' id='bedrooms' value='" . $myrow["bedrooms"] . "' required/>
            </fieldset>";
        echo "<label for='lodging'>Lodging Type</label>
                <select class='form-control' name='lodging' id='lodging'>
                    <option>" . $myrow['lodging'] . "</option>
                    <option>House</option>
                    <option>Semi-Detached</option>
                    <option>Duplex</option>
                    <option>Apartment</option>
                    <option>Condo</option>
                    <option>Other</option>
                </select>";
        echo "<fieldset class='form-group'>
                <label for='price'>Price</label>
                <input type='text' class='form-control' name='price' id='price' value='" . $myrow["price"] . "' required/>
            </fieldset>";
        echo "<button name='updateBtn' id='updateBtn' type='submit' class='btn btn-primary'>Update information</button>";
        echo "</form>";
    }
?>

<?php
    
    include_once 'config/connection.php'; 
    
    // SELECT query
    $query = "SELECT Properties.property_id, Properties.address FROM ServiceMembers "
             . "INNER JOIN rents_out ON ServiceMembers.member_id = rents_out.member_id "
             . "INNER JOIN Properties ON rents_out.property_id = Properties.property_id "
             . "WHERE ServiceMembers.member_id = ?";

    // prepare query for execution
    if($stmt = $con->prepare($query)){
        
        $stmt->bind_Param("s", $_SESSION['member_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $propertyName = $row["address"];
                $propertyId = $row["property_id"];
                echo "<h2>$propertyName</h2>";
                echo '<form method="post" action="my-properties.php?property_id=' . $propertyId . '">';
                echo '<button name="editBtn" id="editBtn" type="submit" class="btn btn-primary">edit</button>';
                echo '</form>';
            }
        }
        else {
            echo "<h2>You haven't listed any properties on qbnb yet.</h2>";
        }
    }
    echo '<form method="post" action="my-properties.php?">';
    echo '<button name="newBtn" id="newBtn" type="submit" class="btn btn-primary" style="float: right;">Add New Property</button>';
    echo '</form>';
?>

</body>