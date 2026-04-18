<!DOCTYPE html>
<?php
require_once 'Connection.php';
require_once 'session.php';
require_once 'calculation.php';
require_once 'fraction.php';
?>

<script src="home.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="notebook.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=print" />
    <title>Your Notebook</title>
</head>

<?php
$recipeId = isset($_POST['recipeId']) ? $_POST['recipeId'] : (isset($_GET['recipeId']) ? $_GET['recipeId'] : null);

if ($recipeId) {
    $sql = "SELECT * FROM recipe WHERE recipeId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fetch_recipe = mysqli_fetch_assoc($result);
?>

<header>
    <a href="notes.php"><img src="../TalkChef/image/left.png" alt="Back button"></a>
    <h1>Your <?php echo htmlspecialchars($fetch_recipe['recipeName']); ?> Notebook</h1>
    <button class="btn-print" type="button" onclick="print()">
        <span class="material-symbols-outlined">
            print
        </span>
    </button>
</header>

<html>

<body>

    <div class="recipe-infobox">
        <h1 class="recipe-title"><?php echo htmlspecialchars($fetch_recipe['recipeName']); ?> (per serving)</h1>
        <p class="recipe-description"><?php echo htmlspecialchars($fetch_recipe['recipeDesc']); ?></p>

        <?php
                $noteSql = "SELECT * FROM recipe_notes WHERE recipeId = ? AND userId = ?";
                $noteStmt = $conn->prepare($noteSql);
                $noteStmt->bind_param("ii", $recipeId, $userId);
                $noteStmt->execute();
                $noteResult = $noteStmt->get_result();

                if ($noteResult->num_rows > 0) {
                    $note = mysqli_fetch_assoc($noteResult);
                ?>
        <div class="note">
            <form method="POST" action="edit_note.php">
                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                <input type="hidden" name="noteId" value="<?php echo $note['id']; ?>">
                <input type="hidden" name="source" value="notebook">
                <textarea name="recipeNote"><?php echo htmlspecialchars($note['note']); ?></textarea>
                <?php if (isset($stepNo)) { ?>
                <input type="hidden" name="stepNo" value="<?php echo $stepNo; ?>">
                <?php } ?>
                <div class="note-actions">
                    <button type="submit">Edit</button>
                </div>
            </form>
            <form method="POST" action="delete_note.php">
                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                <input type="hidden" name="noteId" value="<?php echo $note['id']; ?>">
                <input type="hidden" name="source" value="notebook">
                <?php if (isset($stepNo)) { ?>
                <input type="hidden" name="stepNo" value="<?php echo $stepNo; ?>">
                <?php } ?>
                <div class="note-actions">
                    <button type="submit">Delete</button>
                </div>
            </form>
        </div>
        <?php
                } else {
                ?>
        <form method="POST" action="save_note.php">
            <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
            <input type="hidden" name="source" value="notebook">
            <textarea name="recipeNote" placeholder="Leave a note for this recipe..."></textarea>
            <div class="note-actions">
                <button type="submit">Save</button>
            </div>
        </form>
        <?php
                }
            }

            $nutritionDetails = calculateNutrition($recipeId);

            if (isset($nutritionDetails['steps'])) {
                $instructions = [];
                foreach ($nutritionDetails['steps'] as $steps) {
                    $riId = $steps['riId'];
                    $baseQuantity = $steps['quantity'];
                    $ingredientType = $steps['type'];
                    $unit = $steps['unit'];
                    $ingredientName = $steps['ingreName'];
                    $action = $steps['action'];
                    $step = $steps['no'];

                    if (empty($steps['action']) || empty($steps['no'])) {
                        continue;
                    }

                    if (!isset($instructions[$step][$action])) {
                        $instructions[$step][$action] = [];
                    }

                    $instructions[$step][$action][] = decimalToFraction($baseQuantity) . " $unit $ingredientName";
                }
            }

            $sql = "SELECT * FROM step WHERE recipeId = ? ORDER BY no";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $recipeId);
            $stmt->execute();
            $steps = $stmt->get_result();

            if ($steps->num_rows > 0) {
                while ($step = mysqli_fetch_assoc($steps)) {
                    $stepNo = $step['no'];
                    $preInstruction = $step['preInstruction'] ?? '';
                    $postInstruction = $step['postInstruction'] ?? '';
                ?>
        <h2>Step <?php echo $stepNo; ?></h2>
        <?php
                    echo $preInstruction;

                    if (isset($instructions[$stepNo])) {
                        foreach ($instructions[$stepNo] as $action => $ingredients) {
                            echo ' ' . $action . ' ' . implode(', ', $ingredients) . '.';
                        }
                    }

                    echo ' ' . $postInstruction;

                    $stepNoteSql = "SELECT * FROM step_notes WHERE recipeId = ? AND stepNo = ? AND userId = ?";
                    $stepNoteStmt = $conn->prepare($stepNoteSql);
                    $stepNoteStmt->bind_param("iii", $recipeId, $stepNo, $userId);
                    $stepNoteStmt->execute();
                    $stepNoteResult = $stepNoteStmt->get_result();

                    if ($stepNoteResult->num_rows > 0) {
                        $stepNote = mysqli_fetch_assoc($stepNoteResult);
                    ?>
        <div class="note">
            <form method="POST" action="edit_note.php">
                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                <input type="hidden" name="noteId" value="<?php echo $stepNote['id']; ?>">
                <input type="hidden" name="source" value="notebook">
                <textarea name="recipeNote"><?php echo htmlspecialchars($stepNote['note']); ?></textarea>
                <?php if (isset($stepNo)) { ?>
                <input type="hidden" name="stepNo" value="<?php echo $stepNo; ?>">
                <?php } ?>
                <div class="note-actions">
                    <button type="submit">Edit</button>
                </div>
            </form>
            <form method="POST" action="delete_note.php">
                <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                <input type="hidden" name="noteId" value="<?php echo $stepNote['id']; ?>">
                <input type="hidden" name="source" value="notebook">
                <?php if (isset($stepNo)) { ?>
                <input type="hidden" name="stepNo" value="<?php echo $stepNo; ?>">
                <?php } ?>
                <div class="note-actions">
                    <button type="submit">Delete</button>
                </div>
            </form>
        </div>
        <?php
                    } else {
                    ?>
        <form method="POST" action="save_note.php">
            <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
            <input type="hidden" name="stepNo" value="<?php echo $stepNo; ?>">
            <input type="hidden" name="source" value="notebook">
            <textarea name="stepNote" placeholder="Leave a note for this step..."></textarea>
            <div class="note-actions">
                <button type="submit">Save</button>
            </div>
        </form>
        <?php
                    }
                }
            }
        } else {
            echo "Recipe not found.";
        }
        ?>
    </div>
</body>

</html>