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
    $conn = mysqli_connect("localhost", "root", "", "sfms", 3306);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // User Info.
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "SELECT name, email FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $name, $email);
    mysqli_stmt_fetch($stmt);

    // Profile Info.
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "SELECT profile_id, age, gender, image, religion, occupation, height, weight, pdesc, hobby, m_status FROM profiles WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $profile_id, $age, $gender,$image, $religion, $occupation, $height, $weight, $pdesc, $hobby, $m_status);
    mysqli_stmt_fetch($stmt);

    // Address Info.
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "SELECT house_no, street_no, city FROM addresses WHERE add_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $house_no, $street_no, $city);
    mysqli_stmt_fetch($stmt);

    // Profile Info.
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "SELECT degree, institution, year FROM educations WHERE edu_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $degree, $institution, $year);
    mysqli_stmt_fetch($stmt);
    ?>
    <div class="form_container">
        <form id="profile_form" class="form" autocomplete="off">
            <div style="display:flex; justify-content: center">
                <img src="<?php echo $image ?>" alt="" width='100'>
            </div>
            
            <br><label for="name">Name</label><br>
            <input type="text" name="name" id="name" value="<?php echo $name ?>" readonly>

            <br><label for="email">Email</label><br>
            <input type="email" name="email" id="email" value="<?php echo $email ?>" readonly>
        </form>
    </div>

    <div class="form_container" >
        <form action="../controllers/profile_c.php" method="post" novalidate onsubmit="return profile();" id="profile_form" class="form" autocomplete="off" enctype="multipart/form-data">
            <h2>Profile Info.</h1>
            <?php
            if (isset($_SESSION["success"]) && !empty($_SESSION["success"])) {
                echo "<p class='success'>" . $_SESSION["success"] . "</p><br>";
                $_SESSION["success"] = "";
            }
            ?>

            <br><label for="age">Age</label><br>
            <input type="numer" name="age" id="age" value="<?php echo $age ?>">

            <br><label for="gender">Gender</label><br>
            <select name="gender" id="gender">
                <option value="Male" <?php if($gender == 'Male'){
                    echo 'selected';
                } ?>>Male</option>
                <option value="Female" <?php if($gender == 'Female'){
                    echo 'selected';
                } ?>>Female</option>
            </select>

            <br><label for="image">Image</label>
            <input type="file" name="image" id="image">

            <input type="hidden" name="pimage" value="<?php echo $image ?>">

            <br><label for="religion">Religion</label><br>
            <input type="text" name="religion" id="religion" value="<?php echo $religion ?>">

            <br><label for="occupation">Occupation</label><br>
            <input type="text" name="occupation" id="occupation" value="<?php echo $occupation ?>">

            <br><label for="pdesc">Description</label><br>
            <input type="text" name="pdesc" id="pdesc" value="<?php echo $pdesc ?>">

            <br><label for="hobby">Hobby</label><br>
            <input type="text" name="hobby" id="hobby" value="<?php echo $hobby ?>">

            <br><label>Height (Ft, In)</label>
            <div class="height_container">
                <div>
                    <input type="text" name="feet" id="feet" value="<?php echo floor($height/12) ?>">
                </div>
                <div>
                    <input type="text" name="inch" id="inch" value="<?php echo $height%12 ?>">
                </div>
            </div>

            <br><label for="weight">Weight</label><br>
            <input type="numer" name="weight" id="weight" value="<?php echo $weight ?>">
            
            <br><label for="m_status">Marital Status</label><br>
            <input type="text" name="m_status" id="m_status" value="<?php echo $m_status ?>">


            <br><input type="submit" value="UPDATE PROFILE">
        </form>
    </div>

        <div class="form_container" >
            <form action="../controllers/address_c.php" method="post" novalidate onsubmit="return profile();" id="profile_form" class="form" autocomplete="off">
                <h2>Address Info.</h1>

                <br><label for="house_no">House No</label><br>
                <input type="text" name="house_no" id="house_no" value="<?php echo $house_no ?>">

                <br><label for="street_no">Street Name</label><br>
                <input type="text" name="street_no" id="street_no" value="<?php echo $street_no ?>">

                <br><label for="city">City</label><br>
                <input type="text" name="city" id="city" value="<?php echo $city ?>">

                <br><input type="submit" value="UPDATE ADDRESS">
            </form>
        </div>
        <div class="form_container" >
            <form action="../controllers/education_c.php" method="post" novalidate onsubmit="return profile();" id="profile_form" class="form" autocomplete="off">
                <h2>Education Info.</h1>

                <br><label for="degree">Degree</label><br>
                <input type="text" name="degree" id="degree" value="<?php echo $degree ?>">

                <br><label for="institution">Institution</label><br>
                <input type="text" name="institution" id="institution" value="<?php echo $institution ?>">

                <br><label for="year">Year</label><br>
                <input type="number" name="year" id="year" value="<?php echo $year ?>">

                <br><input type="submit" value="UPDATE EDUCATION">
            </form>
        </div>



    <script src="js/profile.js"></script>
</body>

</html>