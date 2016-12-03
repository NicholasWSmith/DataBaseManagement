<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>My Account</title>

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
 
 if(isset($_POST['updateBtn']) && isset($_SESSION['member_id'])){
    // include database connection
    include_once 'config/connection.php'; 
	
	$query = "UPDATE ServiceMembers SET first_name=?, last_name=?, email=?, phone_number=?, year=?, faculty=?, degree=?, password=? WHERE member_id=?";
 
	$stmt = $con->prepare($query);
    $stmt->bind_param('sssssssss', 
            $_POST['firstName'], 
            $_POST['lastName'],
            $_POST['email'],
            $_POST['phone'], 
            $_POST['year'],
            $_POST['faculty'],
            $_POST['degree'], 
            $_POST['password'],
            $_SESSION['member_id']);
	    // Execute the query
    $result = $stmt->execute();
 }

 if (isset($_POST['deleteBtn']) && isset($_SESSION['member_id'])) {
    $query = "DELETE FROM ServiceMembers WHERE member_id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_Param("s", $_SESSION['member_id']);
    $stmt->execute();
    $_SESSION['member_id']=null;
    session_destroy();
    header("Location: index.php");
 }
 ?>
 
 <?php
if(isset($_SESSION['member_id'])){
   // include database connection
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT * FROM ServiceMembers WHERE member_id=?";
 
        // prepare query for execution
        $stmt = $con->prepare($query);
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_SESSION['member_id']);

        // Execute the query
		$stmt->execute();
 
		// results 
		$result = $stmt->get_result();
		
		// Row data
		$myrow = $result->fetch_assoc();
		
} else {
	//User is not logged in. Redirect the browser to the login index.php page and kill this page.
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


    <div class="bd-example">

    <?php
        if (isset($_SESSION['error_msg'])) {
            $msg = $_SESSION['error_msg'];
            echo "<h3 class='error-msg'>$msg</h2>";
            unset($_SESSION['error_msg']);
        }
    ?>

    <h2>
    <?php 
        if (isset($_POST['updateBtn']) && isset($_SESSION['member_id'])) {
            if ($result) {
                echo "Your profile has been updated.";
            }
            else {
                echo "Profile update failed.";
            }
        }
        else {
            $fname = $myrow['first_name'];
            echo "Welcome, $fname.";
        }
    ?>
    </h2>

    <script type="text/javascript">
    function clicked() {
       if (confirm('Are you sure?')) {
           deleteBtn.submit();
       } else {
           return false;
       }
    }
    </script>

        <form name='signup' id='signup' action='profile.php' method='post'>
            <fieldset class="form-group">
                <label for="firstName">First name</label>
                <input type="text" class="form-control" name="firstName" id="firstName" value="<?php echo $myrow['first_name']; ?>" required autofocus/>
            </fieldset>
            <fieldset class="form-group">
                <label for="lastName">Last name</label>
                <input type="text" class="form-control" name="lastName" id="lastName" value="<?php echo $myrow['last_name']; ?>" required/>
            </fieldset>
            <fieldset class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo $myrow['email']; ?>" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="phone">Phone number</label>
                <input type="tel" class="form-control" name="phone" id="phone" value="<?php echo $myrow['phone_number']; ?>" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="year">Graduating year</label>
                <input type="number" class="form-control" name="year" id="year" value="<?php echo $myrow['year']; ?>" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="faculty">Faculty</label>
                <input type="text" class="form-control" name="faculty" id="faculty" value="<?php echo $myrow['faculty']; ?>" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="degree">Degree</label>
                <input type="text" class="form-control" name="degree" id="degree" value="<?php echo $myrow['degree']; ?>" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" value="<?php echo $myrow['password']; ?>" required />
            </fieldset>
            <button name="updateBtn" id="updateBtn" type="submit" class="btn btn-primary">Update information</button>
            <button name="deleteBtn" id="deleteBtn" type="submit" onclick="clicked();" class="btn btn-primary">Delete your account</button>
        </form>
    </div>
</body>
</html>