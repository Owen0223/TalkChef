<!DOCTYPE html>
<html>
<?php
require_once 'Connection.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>User's Feedback</title>
</head>

<body>
    <div class="back-button">
        <a href="admin.html">
            <img src="../TalkChef/image/left.png" alt="Back" class="back-image">
        </a>
    </div>
    <h1>User's Feedback</h1>

    <?php
    $sql = "SELECT * FROM feedback JOIN user ON feedback.userId = user.userId";
    $allFeedback = mysqli_query($conn, $sql);

    if (mysqli_num_rows($allFeedback) > 0) {
        while ($fetch_feedback = mysqli_fetch_assoc($allFeedback)) {
    ?>
    <div class="view-position">
        <div class="view-feedback">
            <div class="user-info">
                <?php
                        echo "Email: " . $fetch_feedback['userEmail'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "Name: " . $fetch_feedback['userName'];
                        ?>
            </div>
            <div class="desc-container">
                <h4>Feedback:</h4>
                <?php echo $fetch_feedback['FBdesc']; ?>
            </div>
        </div>
    </div>
    <?php
        }
    }
    ?>
</body>

</html>