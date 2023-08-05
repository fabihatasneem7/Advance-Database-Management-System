<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Profile</title>
</head>

<body>
    <?php require "nav.php" ?>

    <?php
    function getUserName($id){
        $conn = mysqli_connect("localhost", "root", "", "sfms", 3306);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, "SELECT name FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $uname);
        mysqli_stmt_fetch($stmt);

        echo $uname;
    }
        



            
    $conn = mysqli_connect("localhost", "root", "", "sfms", 3306);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Matches Info.

    ?>

        <div class="form_container">
            <form action="../controllers/education_c.php" method="post" novalidate onsubmit="return profile();" id="profile_form" class="form" autocomplete="off">
                <h2>Messages</h1>

                <?php 
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, "SELECT user1, user2 FROM matches");
                mysqli_stmt_execute($stmt);
            
                mysqli_stmt_bind_result($stmt, $user1, $user2);
                while(mysqli_stmt_fetch($stmt)){
                    if($user1 == $_SESSION["user_id"]){
                        ?>
                        <input type="text" value="<?php echo getUserName($user2) ?>" readonly>
                        <?php
                    }
                    if($user2 == $_SESSION["user_id"]){
                        ?>
                        <input type="text" value="<?php echo getUserName($user1) ?>" readonly>
                        <?php
                    } 
                }
                ?>

                
            </form>
        </div>



    <script src="js/profile.js"></script>
</body>

</html>