<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Signup</title>

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


 <?php
  //Create a user session or resume an existing one
 session_start();

 ?>

<?php
 //check if the user is already logged in and has an active session
if(isset($_SESSION['member_id'])){
    //Redirect the browser to the profile editing page and kill this page.
    header("Location: profile.php");
    die();
}
?>

<?php
//check if the signup form has been submitted
if(isset($_POST['signupBtn'])){
 
    // include database connection
    include_once 'config/connection.php'; 
    
    // SELECT query
    $query = "INSERT INTO ServiceMembers VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)";
    // prepare query for execution
    if($stmt = $con->prepare($query)){
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ssssssss",
            $_POST['firstName'], 
            $_POST['lastName'],
            $_POST['email'],
            $_POST['phone'], 
            $_POST['year'],
            $_POST['faculty'],
            $_POST['degree'], 
            $_POST['password']
            );
        // Execute the query
        $result = $stmt->execute();

        // successful sign up
        if($result){
            $query = "SELECT * FROM ServiceMembers WHERE email=?";
            $stmt = $con->prepare($query);
            $stmt->bind_Param("s", $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            $myrow = $result->fetch_assoc();
            $_SESSION['member_id'] = $myrow['member_id'];
            header("Location: profile.php");
            die();
        }
        else {
            //If the username/password doesn't matche a user in our database
            // Display an error message and the login form
            $_SESSION['error_msg'] = "A user with the same email address already exists.";
        }
    }
    else {
        $_SESSION['error_msg'] = "Failed to prepare the SQL";  
    }
 }
?>


<body>

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
        <form name='signup' id='signup' action='signup.php' method='post'>
            <fieldset class="form-group">
                <label for="firstName">First name</label>
                <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter your first name" required autofocus/>
            </fieldset>
            <fieldset class="form-group">
                <label for="lastName">Last name</label>
                <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter your last name" required/>
            </fieldset>
            <fieldset class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required />
                <small class="text-muted">We'll never share your email with anyone else.</small>
            </fieldset>
            <fieldset class="form-group">
                <label for="phone">Phone number</label>
                <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter phone number" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="year">Graduating year</label>
                <input type="number" class="form-control" name="year" id="year" placeholder="Enter graduating year" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="faculty">Faculty</label>
                <input type="text" class="form-control" name="faculty" id="faculty" placeholder="Enter faculty" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="degree">Degree</label>
                <input type="text" class="form-control" name="degree" id="degree" placeholder="Enter degree" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
            </fieldset>
            <button name="signupBtn" id="signupBtn" type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>


