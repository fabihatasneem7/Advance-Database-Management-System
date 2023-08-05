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
    <title>Match</title>
</head>

<body>
    <?php require "nav.php" ?>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "sfms", 3306);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Subscription Info.
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "SELECT sub_id FROM subscriptions WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $sub_id);
    mysqli_stmt_fetch($stmt);

    // Profile Info.
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "SELECT profile_id, age, gender, image, religion, occupation, height, weight, pdesc, hobby, m_status FROM profiles WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $profile_id, $age, $gender,$image, $religion, $occupation, $height, $weight, $pdesc, $hobby, $m_status);
    mysqli_stmt_fetch($stmt);

    // Preferences Info.
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, "SELECT pre_religion, pre_occupation, pre_height, pre_weight, pre_age, pre_city, pre_degree FROM preferences WHERE profile_id = ?");

    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $pre_religion, $pre_occupation, $pre_height, $pre_weight, $pre_age, $pre_city, $pre_degree);
    mysqli_stmt_fetch($stmt);

    $isTrue = true;

    if($age == 0 || $gender == "" || $religion == "" || $occupation == "" || $height == 0 || $weight == 0){
        echo "<p style='text-align:center;font-weight:bold; font-size:18px; margin-top: 15px'>Please Complete Your Profile Information.</p>";
        $isTrue = false;
    }

    if(!$sub_id){
        echo "<p style='text-align:center;font-weight:bold; font-size:18px; margin-top: 15px'>Please Subscribe to Premium Plan to See Your Matches.</p>";
        $isTrue = false;
    }

    if($isTrue){
        // Fetch all profiles.
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, "SELECT profile_id, name, age, gender, image, religion, occupation, height, weight, pdesc, hobby, m_status FROM profiles");
        // mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $p_profile_id, $p_name, $p_age, $p_gender,$p_image, $p_religion, $p_occupation, $p_height, $p_weight, $p_pdesc, $p_hobby, $p_m_status);

        echo "<div class='match_container'>";
        while(mysqli_stmt_fetch($stmt)){
            if((strtolower($gender) == 'male' && strtolower($p_gender) == 'female') || (strtolower($gender) == 'female' && strtolower($p_gender) == 'male')){

                if(strtolower($religion) == strtolower($p_religion)){
                    ?>
                    <div class="match_profile">
                        <img src="<?php echo $p_image ?>" alt="">
                        <h2><?php echo $p_name ?></h2>
                        <p><?php echo $p_pdesc ?></p><br>
                        <p><?php echo "Age: " .$p_age. " | Religion: ". $p_religion ?></p>
                        <p><?php echo "Height: " .floor($p_height/12). " ft ". $p_height%12 . " in | Weight: ". $p_weight . " kg" ?></p>
                        <p><?php echo "Hobby: " .$p_hobby. " | M Status: ". $p_m_status ?></p>

                        <br>
                        <form method="POST" action="../controllers/send_message_c.php">
                            <input type="hidden" name="user2" value="<?php echo $p_profile_id?>">
                            <input type="submit" value="Send Message">
                        </form>
                    </div>
                    <?php
                }
                
            }
        }
    }
    ?>

    

    <script src="js/profile.js"></script>
</body>

</html>