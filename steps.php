<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="steps.css" />
    <link rel="icon" type="image/png" href="../TalkChef/image/TCtab.png">
    <title>Cooking Mode</title>
</head>

<?php
require_once 'Connection.php';
require_once 'session.php';

$recipeId = $_POST['recipeId_step'];

$sql_max = "SELECT MAX(no) as maxno FROM step WHERE recipeId = ?";
$stmt_max = $conn->prepare($sql_max);
$stmt_max->bind_param('i', $recipeId);
$stmt_max->execute();
$result_max = $stmt_max->get_result();
$fetch_max = $result_max->fetch_assoc();
$maxno = $fetch_max['maxno'];
?>

<body>
    <h1>Cooking Mode</h1>

    <button id="step-button left-button" class="step-button left-button" onclick="changeStep(-1)">&lt;</button>
    <div class="step-container">


        <div class="instruction-container">
            <span id="step-instruction">
                <?php
                require_once 'calculation.php';
                require_once 'fraction.php';

                $no = 1;

                if (isset($_POST['recipeId_step'])) {
                    $recipeId = $_POST['recipeId_step'];

                    $updatedServing = $_POST['updatedServing'];
                    $updatedMultipliers = json_decode($_POST['updatedMultipliers'], true);

                    if (isset($recipeId) && isset($updatedServing) && isset($updatedMultipliers)) {
                        $_SESSION['updatedServing'] = $updatedServing;
                        $_SESSION['updatedMultipliers'] = $updatedMultipliers;
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

                            $key = $action . '_' . $step;

                            if ($step == $no) {

                                $multiplier = isset($updatedMultipliers[$ingredientType]) ? $updatedMultipliers[$ingredientType] : 1;
                                $adjustedQuantity = $baseQuantity * $multiplier * $updatedServing;

                                if (!isset($instructions[$key])) {
                                    $instructions[$key] = "$action ";
                                }

                                $instructions[$key] .= decimalToFraction($adjustedQuantity) . " $unit $ingredientName, ";

                                foreach ($instructions as $key => $instruction) {
                                    $instructions[$key] = rtrim($instruction, ',');
                                }
                            }
                        }
                    }
                }

                $sql = "SELECT * from step JOIN recipe ON recipe.recipeId = step.recipeId WHERE step.recipeId = ? AND no = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ii', $recipeId, $no);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $fetch_step = mysqli_fetch_assoc($result);
                    $duration = $fetch_step['duration'];
                    $preInstruction = $fetch_step['preInstruction'];
                    $postInstruction = $fetch_step['postInstruction'];
                    $highlight = $fetch_step['highlight'];

                    if ($preInstruction == null) {
                        $preInstruction == '';
                    }

                    if ($postInstruction == null) {
                        $postInstruction == '';
                    }

                    if (isset($instructions)) {
                        echo $no . '. ' . $preInstruction . ' ' . implode(' ', $instructions) . ' ' . $postInstruction;
                    } else {
                        echo $no . '. ' . $preInstruction . ' ' . $postInstruction;
                    }
                } else {
                    echo "No instruction for this step.";
                }
                ?>
            </span>
        </div>

    </div>

    <button id="step-button right-button" class="step-button right-button" onclick="changeStep(1)">&gt;</button></br>

    <script>
    let maxNo = <?php echo $maxno; ?>;
    let duration = <?php echo $duration; ?>;
    var updatedServing = <?php echo $_SESSION['updatedServing']; ?>;
    var updatedMultipliers = <?php echo json_encode($_SESSION['updatedMultipliers']); ?>;
    </script>


    <input type="hidden" id="recipeId" name="recipeId" value="<?php echo $_POST['recipeId_step']; ?>">

    <div class="container">
        <div id="side-container" class="side-container">
            <p id="countdown"></p></br>
            <button onclick="startCountdown()" id="timer" class="timer-button" style="display: none;">Start</button>
            <button onclick="pauseCountdown()" id="pausetimer" class="timer-button"
                style="display: none;">Pause</button>
            <button onclick="cancelCountdown()" id="canceltimer" class="timer-button"
                style="display: none;">Cancel</button>
            <button onclick="cancelCountdown()" id="stopAlarm" class="timer-button" style="display: none;">Stop
                Alarm</button>
        </div>

        <div class="right-side-container">
            <div class="view-container">
                <div id="highlight" class="highlight-reminder" style="display: none;">
                    <?php
                    echo $highlight;
                    ?>
                </div>

                <div id="step-notes" class="step-notes">
                    <h2 id="no"></h2>
                    <div id="have-note" class="have-note">
                        <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                        <input type="hidden" id="editId" name="noteId" value="">
                        <input type="hidden" name="source" value="steps">
                        <textarea id="editNote" name="editNote"></textarea>
                        <input type="hidden" name="stepNo" value="">
                        <div class="note-actions">
                            <button onclick="editStepNote()">Edit</button>
                        </div>
                        <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                        <input type="hidden" id="deleteId" name="noteId" value="">
                        <input type="hidden" name="source" value="steps">
                        <input type="hidden" name="stepNo" value="">
                        <div class="note-actions">
                            <button onclick="deleteStepNote()">Delete</button>
                        </div>
                    </div>
                    <div id="dont-have-note" class="dont-have-note">
                        <input type="hidden" name="recipeId" value="<?php echo $recipeId; ?>">
                        <input type="hidden" id="stepNo" name="stepNo" value="">
                        <textarea id="stepNote" name="stepNote" placeholder="Leave a note for this step..."></textarea>
                        <div class="note-actions">
                            <button onclick="saveStepNote()">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="steps.js"></script>

    <div class="button-container">
        <form action="details.php" method="POST" id="exit-form" class="exit-form">
            <input type="hidden" id="recipeId" name="recipeId" value="<?php echo $recipeId; ?>">
            <button type="submit" id="exit" class="exit-button" onclick="exitStep()">Exit Cooking
                Mode</button>
        </form>

        <button id="done" class="done-button" style="display: none;" onclick="doneStep()">Done</button>
    </div>
</body>

</html>