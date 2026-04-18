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
    <link rel="stylesheet" href="how-to-talk.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>How to Talk</title>
</head>

<body>
    <?php
    require_once 'header.php';
    ?>

    <div class="position">
        <div class="container">
            <h2>How to Talk?</h2>
            <table>
                <tr class="table-header">
                    <td>Action</td>
                    <td colspan="4">Command</td>
                </tr>
                <tr>
                    <td>Next Step</td>
                    <td colspan="4">"Next"</td>
                </tr>
                <tr>
                    <td>Previous Step</td>
                    <td colspan="2">"Previous"</td>
                    <td colspan="2">"Back"</td>
                </tr>
                <tr>
                    <td>Start Timer</td>
                    <td colspan="2">"Start timer"</td>
                    <td colspan="2">"Start"</td>
                </tr>
                <tr>
                    <td>Pause Timer</td>
                    <td colspan="2">"Pause timer"</td>
                    <td colspan="2">"Pause"</td>
                </tr>
                <tr>
                    <td>Cancel Timer</td>
                    <td colspan="2">"Cancel timer"</td>
                    <td colspan="2">"Cancel"</td>
                </tr>
                <tr>
                    <td>Exit Cooking Mode</td>
                    <td colspan="4">"Exit"</td>
                </tr>
                <tr>
                    <td>Done Cooking at Last Step</td>
                    <td colspan="4">"Done"</td>
                </tr>
                <tr>
                    <td>Stop Alarm</td>
                    <td>"Stop alarm"</td>
                    <td>"Stop"</td>
                    <td>"Cancel alarm"</td>
                    <td>"Cancel"</td>
                </tr>
            </table>
        </div>
    </div>

    <?php
    require_once 'footer.php';
    ?>
</body>

</html>