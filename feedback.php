<!DOCTYPE html>
<?php
require_once 'Connection.php';
require_once 'session.php';
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="feedback.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Feedback</title>
</head>

<body>
    <?php
    require_once 'header.php';
    ?>

    <div class="position">
        <div class="container">
            <h2>Feedback</h2>
            <form action="feedback.php" method="POST">
                <textarea name="feedback" id="feedback" rows="6" cols="50"
                    placeholder="Leave your feedback here..."></textarea>
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
    </div>

    <?php
    require_once 'footer.php';
    ?>
</body>

</html>

<?php
if (isset($_POST['submit'])) {
    if (!empty($_POST['feedback'])) {
        $feedback = $_POST['feedback'];

        $insert_query = "INSERT INTO feedback (userId, FBdesc) 
                VALUES ('$userId','$feedback')";
        $result = mysqli_query($conn, $insert_query);
        echo "<script>alert('Thank you for the Feedback!')</script>";
        echo "<script>location.href='feedback.php';</script>";
    } else {
        echo "<script>alert('Please fill in the text box before you submit!')</script>";
        echo "<script>location.href='feedback.php';</script>";
    }
}
?>