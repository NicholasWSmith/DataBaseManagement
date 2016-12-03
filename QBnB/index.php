<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Bed & Breakfast for Queen's Alumni</title>

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
 //check if the user clicked the logout link and set the logout GET parameter
if(isset($_GET['logout'])){
	//Destroy the user's session.
	$_SESSION['member_id']=null;
	session_destroy();
}
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
//check if the login form has been submitted
if(isset($_POST['loginBtn'])){
 
    // include database connection
    include_once 'config/connection.php'; 
    
    // SELECT query
    $query = "SELECT member_id, password, email FROM ServiceMembers WHERE email=? AND password=?";
    // prepare query for execution
    if($stmt = $con->prepare($query)){
        
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ss", $_POST['inputEmail'], $_POST['inputPassword']);

        // Execute the query
        $stmt->execute();
 
        /* resultset */
        $result = $stmt->get_result();

        // Get the number of rows returned
        $num = $result->num_rows;
        if($num>0){
            //If the username/password matches a user in our database
            //Read the user details
            $myrow = $result->fetch_assoc();
            //Create a session variable that holds the user's id - or admin id if the admin logs in
            $_SESSION['member_id'] = $myrow['member_id'];
            if($myrow['member_id'] == -1){
                //Redirect the browser to the admin dash page and kill this page.
                header("Location: admin-dash.php");
                die();
            }
            else{
                //Redirect the browser to the profile editing page and kill this page.
                header("Location: profile.php");
                die();
            }
        }
        else {
            //If the username/password doesn't matche a user in our database
            // Display an error message and the login form
            $_SESSION['error_msg'] = "Login failed";
        }
    }
    else {
        $_SESSION['error_msg'] = "Failed to prepare the SQL";  
    }
 }

 if (isset($_POST['signupBtn'])) {
    header("Location: signup.php");
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
          <a class="navbar-brand" href="#">qbnb</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Log in</a></li>
            <li><a href="signup.php">Sign up</a></li>
            <li><a href="search.php">Search</a></li>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>


    <!-- Header -->
    <header id="top" class="header">
        <div class="text-vertical-center">
            <h1>Welcome!</h1>
            <br>
            <div class='container'>
            <form class='form-signin' name='login' id='login' action='index.php' method='post'>
                <?php
                    if (isset($_SESSION["error_msg"])) {
                        $msg = $_SESSION["error_msg"];
                        echo "<h3 class='error-msg'>$msg</h2>";
                        unset($_SESSION["error_msg"]);
                    }
                    else {
                        echo '<h2 class="form-signin-heading">Log in to get started</h2>';
                    }
                ?>
                <label for="inputEmail" class="sr-only">Email address : </label>
                <input type="email" name='inputEmail' id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputPassword" class="sr-only">Password : </label>
                <input type="password" name='inputPassword' id="inputPassword" class="form-control" placeholder="Password" required>
                <button name='loginBtn' id='loginBtn' class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
                <button name='signupBtn' id='signupBtn' class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
            </form>
            </div>
        </div>
    </header>

</body>
</html>