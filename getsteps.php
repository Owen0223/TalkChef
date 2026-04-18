<?php
require_once 'Connection.php';
require_once 'calculation.php';
require_once 'fraction.php';
require_once 'session.php';

if (isset($_POST['recipeId']) && isset($_POST['no'])) {
    $recipeId = $_POST['recipeId'];
    $no = $_POST['no'];

    $updatedServing = isset($_POST['updatedServing']) ? intval($_POST['updatedServing']) : $_SESSION['updatedServing'];
    $updatedMultipliers = isset($_POST['updatedMultipliers']) ? json_decode($_POST['updatedMultipliers'], true) : $_SESSION['updatedMultipliers'];

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

    $sql = "SELECT * from step JOIN recipe ON recipe.recipeId = step.recipeId WHERE step.recipeId = ? AND no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $recipeId, $no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fetch_step = mysqli_fetch_assoc($result);
        $preInstruction = $fetch_step['preInstruction'];
        $postInstruction = $fetch_step['postInstruction'];
        $highlight = $fetch_step['highlight'];
        $response = [
            'no' => $fetch_step['no'],
            'preInstruction' => !empty($preInstruction) ? $preInstruction : '',
            'postInstruction' => !empty($postInstruction) ? $postInstruction : '',
            'duration' => $fetch_step['duration'],
            'instructions' => !empty($instructions) ? $instructions : '',
            'highlight' => !empty($highlight) ? $highlight : '',
            'userId' => !empty($userId) ? $userId : 0,
        ];
    } else {
        $response = ['error' => 'No instruction for this step.'];
    }

    $stepNoteSql = "SELECT * FROM step_notes WHERE recipeId = ? AND stepNo = ? AND userId = ?";
    $stepNoteStmt = $conn->prepare($stepNoteSql);
    $stepNoteStmt->bind_param("iii", $recipeId, $no, $userId);
    $stepNoteStmt->execute();
    $stepNoteResult = $stepNoteStmt->get_result();

    if ($stepNoteResult->num_rows > 0) {
        $stepNote = $stepNoteResult->fetch_assoc();
        $noteId = $stepNote['id'];
        $note = $stepNote['note'];

        $response = array_merge($response, [
            'noteId' => !empty($noteId) ? $noteId : '',
            'note' => !empty($note) ? $note : ''
        ]);
    }

    echo json_encode($response);
}