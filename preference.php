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
    <title>Preferences</title>
</head>

<body>
    <?php require "nav.php" ?>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "sfms", 3306);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Profile Info.
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "SELECT pre_religion, pre_occupation, pre_height, pre_weight, pre_age, pre_city, pre_degree FROM preferences WHERE profile_id = ?");

    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $pre_religion, $pre_occupation, $pre_height, $pre_weight, $pre_age, $pre_city, $pre_degree);
    mysqli_stmt_fetch($stmt);
    ?>

    <div class="form_container" style="margin-top:50px">
        <form action="../controllers/preferences_c.php" method="post" novalidate onsubmit="return profile();" id="profile_form" class="form" autocomplete="off" enctype="multipart/form-data">
            <h2>Preferences</h1>
            <?php
            if (isset($_SESSION["success"]) && !empty($_SESSION["success"])) {
                echo "<p class='success'>" . $_SESSION["success"] . "</p><br>";
                $_SESSION["success"] = "";
            }
            ?>

            <br><label for="religion">Religion</label><br>
            <input type="text" name="religion" id="religion" value="<?php echo $pre_religion ?>">

            <br><label for="occupation">Occupation</label><br>
            <input type="text" name="occupation" id="occupation" value="<?php echo $pre_occupation ?>">

            <br><label>Height (Ft, In)</label>
            <div class="height_container">
                <div>
                    <input type="text" name="feet" id="feet" value="<?php echo floor($pre_height/12) ?>">
                </div>
                <div>
                    <input type="text" name="inch" id="inch" value="<?php echo $pre_height%12 ?>">
                </div>
            </div>

            <br><label for="weight">Weight</label><br>
            <input type="numer" name="weight" id="weight" value="<?php echo $pre_weight ?>">

            <br><label for="age">Age</label><br>
            <input type="numer" name="age" id="age" value="<?php echo $pre_age ?>">

            <br><label for="city">City</label><br>
            <input type="text" name="city" id="city" value="<?php echo $pre_city ?>">

            <br><label for="degree">Degree</label><br>
            <input type="text" name="degree" id="degree" value="<?php echo $pre_degree ?>">


            <br><input type="submit" value="UPDATE PREFERENCES">
        </form>
    </div>
    <script src="js/profile.js"></script>
</body>

</html>