<?php
// Start the PHP session
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {

?>

<!DOCTYPE html>
<html>
<head>
    <title>HOME</title>
    <!-- Link to external stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
    <form action="logout.php" method="post">

    <div class="container">
    <!-- Display a greeting message with the username -->
    <h1 class="text-center mt-5 w" >Hello, <?php echo $_SESSION['username']; ?></h1>

    <!-- Logout button -->
    <div class="text-center mt-3">
        <form action="logout.php" method="post" style="display: inline;">
            <button class="btn btn-primary" type="submit">Logout</button>
        </form>
    </div>
</div>


</div>
    </form>

</body>
</html>

<?php
}else {
    // If the user is not logged in, redirect to the login page
    header("Location: Loginform.php");
    exit();
}
?>