<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="header.css" />
</head>

<header>
    <div class="header-container">
        <div class="logo">
            <a href="home.php"><img src="../TalkChef/image/TCHeaderLogo.png" alt="Logo" class="logo-img"></a>
        </div>

        <nav class="nav-links">
            <a href="home.php">Home</a>
            <a href="notes.php">Notebook</a>
            <a href="favorites.php">Favorites</a>
            <a href="feedback.php">Feedback</a>
            <a href="how-to-talk.php">How to Talk</a>
        </nav>

        <div class="user-info">
            <?php
            if ($userId != 0) {
            ?>
            <div class="user-nav">
                <a href="user.php"><?php echo $username; ?></a>
            </div>
            <?php
            } else {
            ?>
            <div class="user-name">
                <?php
                    echo $username;
                    ?>
            </div>
            <?php
            }

            if (!isset($_SESSION['userId'])) {
            ?>
            <a href="signin.php"><img src="../TalkChef/image/log-in.png" alt="Log in" class="log"></a>
            <?php
            } else {
            ?>
            <a href="?clear_cookie=1"><img src="../TalkChef/image/log-out.png" alt="Log out" class="log"></a>
            <?php
            }

            if (isset($_GET['clear_cookie'])) {
                setcookie("username", "", time() - 3600, "/");

                session_destroy();
                echo "<script>alert('Sign Out Successfully.'); window.close(); </script>";
                header("Location: ../TalkChef/welcome.html");
                exit;
            }
            ?>

        </div>
    </div>
</header>