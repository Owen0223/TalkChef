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
    <link rel="stylesheet" href="user.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>User Details</title>
</head>

<body>
    <?php
    require_once 'header.php';

    if ($userId != 0) {
        $sql = "SELECT * FROM user WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            $email = $user['userEmail'];
            $name = $user['userName']
    ?>

    <div class="position">
        <div class="container">
            <h2>User Information</h2>
            <form action="edit-user-name.php" method="POST">
                <table>
                    <tr>
                        <td>Email:</td>
                        <td><?php echo $email; ?></td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="name" value="<?php echo $name; ?>"></td>
                    </tr>
                </table>
                <button type="submit">Change Name</button>
            </form>
        </div>
    </div>

    <?php
        }
    }
    require_once 'footer.php';
    ?>
</body>

</html>