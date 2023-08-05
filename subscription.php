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
    <title>Subscription</title>
</head>

<body>
    <?php include 'nav.php' ?>
    <?php 

            $conn = mysqli_connect("localhost", "root", "", "sfms", 3306);
        
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, "SELECT sub_id FROM subscriptions WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
        mysqli_stmt_execute($stmt);
    
        mysqli_stmt_bind_result($stmt, $sub_id);
        mysqli_stmt_fetch($stmt);

        if($sub_id){
            echo "<h2>You are already a premium user.</h2>";
        }else{
            ?>
    <div>
    <div style="display:flex; justify-content: center; margin-top:15px">
            <div class="premium-plan">
                <h2>Premium Plan Features</h2>
                <ul>
                    <li>Send and receive unlimited messages</li>
                    <li>Advanced search filters</li>
                    <li>Highlighted profile in search results</li>
                    <li>Access to exclusive premium profiles</li>
                    <li>Enhanced privacy and security features</li>
                    <li>Personalized matchmaking services</li>
                    <li>24/7 customer support</li>
                </ul>
            </div>
        </div>
        <div style="display:flex; justify-content: center; margin-top:15px">
            <form action="../controllers/subscription_c.php" method="post" novalidate onsubmit="return login();" id="login_form" class="form" autocomplete="off">

                <label for="amount">Amount (Tk)</label><br>
                <input type="number" name="amount" id="amount" value="5000" readonly>

                <br><label for="method">Payment Method</label><br>
                <select name="method" id="method">
                    <option value="bKash">bKash</option>
                    <option value="Nagad">Nagad</option>
                    <option value="Rocket">Rocket</option>
                    <option value="Vis">Visa</option>
                    <option value="Mastercard">Mastercard</option>
                </select>
                <br><input type="submit" value="SUBSCRIBE">
            </form>
        </div>
    </div>
            <?php
        }
    ?>



</body>

</html>