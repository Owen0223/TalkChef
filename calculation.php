<?php
require_once "Connection.php";

function findIngredientIndex($ingredients, $ingreName)
{
    foreach ($ingredients as $index => $ingredient) {
        if ($ingredient['ingreName'] === $ingreName) {
            return $index;
        }
    }
    return -1;
}

function calculateNutrition($recipeId)
{
    global $conn;

    $totalcalorie = 0;
    $totalprotein = 0;
    $totalcarbs = 0;
    $totalfat = 0;
    $proteintag = 0;
    $carbstag = 0;
    $fattag = 0;

    $sql = "SELECT * FROM recipe_ingredient 
            JOIN ingredient ON ingredient.ingreId = recipe_ingredient.ingreId
            JOIN recipe ON recipe.recipeId = recipe_ingredient.recipeId 
            WHERE recipe_ingredient.recipeId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipeId);
    $stmt->execute();
    $result = $stmt->get_result();

    $nutritionalData = [];
    $nutritionalData['ingredients'] = [];
    $nutritionalData['steps'] = [];



    if ($result->num_rows > 0) {
        while ($fetch_data = mysqli_fetch_assoc($result)) {
            $ingredientName = $fetch_data['ingreName'];

            $index = findIngredientIndex($nutritionalData['ingredients'], $ingredientName);

            if ($index !== -1) {
                $nutritionalData['ingredients'][$index]['quantity'] += $fetch_data['quantity'];
                $nutritionalData['ingredients'][$index]['calories'] += $fetch_data['calories'] * $fetch_data['quantity'];
                $nutritionalData['ingredients'][$index]['protein'] += $fetch_data['protein'] * $fetch_data['quantity'];
                $nutritionalData['ingredients'][$index]['carbs'] += $fetch_data['carbs'] * $fetch_data['quantity'];
                $nutritionalData['ingredients'][$index]['fat'] += $fetch_data['fat'] * $fetch_data['quantity'];
            } else {
                $nutritionalData['ingredients'][] = [
                    'type' => $fetch_data['type'],
                    'quantity' => $fetch_data['quantity'],
                    'unit' => $fetch_data['unit'],
                    'ingreName' => $fetch_data['ingreName'],
                    'riId' => $fetch_data['riId'],
                    'protein' => $fetch_data['protein'] * $fetch_data['quantity'],
                    'carbs' => $fetch_data['carbs'] * $fetch_data['quantity'],
                    'fat' => $fetch_data['fat'] * $fetch_data['quantity'],
                    'calories' => $fetch_data['calories'] * $fetch_data['quantity'],
                    'adjustable' => $fetch_data['adjustable'],
                ];
            }

            $nutritionalData['steps'][] = [
                'type' => $fetch_data['type'],
                'quantity' => $fetch_data['quantity'],
                'unit' => $fetch_data['unit'],
                'ingreName' => $fetch_data['ingreName'],
                'riId' => $fetch_data['riId'],
                'action' => $fetch_data['action'],
                'no' => $fetch_data['no']
            ];

            $totalcalorie += $fetch_data['calories'] * $fetch_data['quantity'];
            $totalprotein += $fetch_data['protein'] * $fetch_data['quantity'];
            $totalcarbs += $fetch_data['carbs'] * $fetch_data['quantity'];
            $totalfat += $fetch_data['fat'] * $fetch_data['quantity'];
        }

        $proteintag = $totalprotein;
        $carbstag = $totalcarbs;
        $fattag = $totalfat;

        $nutritionalData['totals'] = [
            'totalCalorie' => number_format($totalcalorie, 2),
            'totalProtein' => number_format($totalprotein, 2),
            'totalCarbs' => number_format($totalcarbs, 2),
            'totalFat' => number_format($totalfat, 2)
        ];

        $nutritionalData['tags'] = [
            'proteinTag' => $proteintag >= 30 ? "High in Protein" : "Low in Protein",
            'carbsTag' => $carbstag >= 80 ? "High in Carbohydrates" : "Low in Carbohydrates",
            'fatTag' => $fattag >= 30 ? "High in Fat" : "Low in Fat"
        ];
    }

    return $nutritionalData;
}