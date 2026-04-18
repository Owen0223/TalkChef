function gcd(a, b) {
    return (b == 0) ? a : gcd(b, a % b);
}

function decimalToFraction(decimal) {
    const wholeNumber = Math.floor(decimal);
    let fraction = decimal - wholeNumber;

    if (Math.floor(decimal) === decimal) {
        return decimal.toString();
    }

    const tolerance = 1.e-2;
    let numerator = 1;
    let denominator = 1;
    let approximation = numerator / denominator;

    while (Math.abs(fraction - approximation) > tolerance) {
        if (approximation < fraction) {
            numerator++;
        } else {
            denominator++;
            numerator = Math.round(fraction * denominator);
        }

        approximation = numerator / denominator;
    }

    const gcdValue = gcd(numerator, denominator);
    numerator /= gcdValue;
    denominator /= gcdValue;

    if (wholeNumber === 0) {
        return `${numerator}/${denominator}`;
    } else {
        return `${wholeNumber} ${numerator}/${denominator}`;
    }
}

const baseAmounts = {};
const ingredientDetails = {};

let totalNutrients = {
    calories: 0,
    protein: 0,
    carbs: 0,
    fat: 0
};

let serving = 1;
let ingredientMultipliers = {};

function updateValue() {
    totalNutrients = {
        calories: 0,
        protein: 0,
        carbs: 0,
        fat: 0
    };

    for (const riId in ingredientDetails) {
        const ingredient = ingredientDetails[riId];
        const ingredientType = ingredient.type;
        const baseQuantity = baseAmounts[riId];
        const ingredientAdjustable = ingredient.adjustable;

        let currentMultiplier = 1;

        if (ingredientAdjustable == 1) {
            currentMultiplier = ingredientMultipliers[ingredientType] || 1;
        }
        const adjustedNutrients = baseQuantity * currentMultiplier;
        const adjustedQuantity = adjustedNutrients * serving;

        const unit = ingredient.unit;
        const ingredientName = ingredient.name;

        document.getElementById(riId).innerText = `${decimalToFraction(adjustedQuantity)} ${unit} ${ingredientName}\n`;


        totalNutrients.calories += (ingredient.calories / baseQuantity) * adjustedNutrients;
        totalNutrients.protein += (ingredient.protein / baseQuantity) * adjustedNutrients;
        totalNutrients.carbs += (ingredient.carbs / baseQuantity) * adjustedNutrients;
        totalNutrients.fat += (ingredient.fat / baseQuantity) * adjustedNutrients;
    }

    document.getElementById("totalCalories").innerText = `Total Calories: ${totalNutrients.calories.toFixed(2)} kcal`;
    document.getElementById("totalProtein").innerText = `Total Protein: ${totalNutrients.protein.toFixed(2)} g`;
    document.getElementById("totalCarbs").innerText = `Total Carbohydrates: ${totalNutrients.carbs.toFixed(2)} g`;
    document.getElementById("totalFat").innerText = `Total Fat: ${totalNutrients.fat.toFixed(2)} g`;

    if (totalNutrients.calories.toFixed(2) >= 800) {
        document.getElementById("totalCalories").style.color = "red";
    } else if (totalNutrients.calories.toFixed(2) < 800 && totalNutrients.calories.toFixed(2) >= 650) {
        document.getElementById("totalCalories").style.color = "black";
    } else if (totalNutrients.calories.toFixed(2) < 650 && totalNutrients.calories.toFixed(2) >= 500) {
        document.getElementById("totalCalories").style.color = "green";
    } else {
        document.getElementById("totalCalories").style.color = "red";
    }

    if (totalNutrients.protein.toFixed(2) >= 40) {
        document.getElementById("totalProtein").style.color = "green";
    } else if (totalNutrients.protein.toFixed(2) >= 20 && totalNutrients.protein.toFixed(2) < 40) {
        document.getElementById("totalProtein").style.color = "black";
    } else {
        document.getElementById("totalProtein").style.color = "red";
    }

    if (totalNutrients.carbs.toFixed(2) >= 100) {
        document.getElementById("totalCarbs").style.color = "red";
    } else if (totalNutrients.carbs.toFixed(2) >= 60 && totalNutrients.carbs.toFixed(2) < 100) {
        document.getElementById("totalCarbs").style.color = "black";
    } else if (totalNutrients.carbs.toFixed(2) >= 30 && totalNutrients.carbs.toFixed(2) < 60) {
        document.getElementById("totalCarbs").style.color = "green";
    } else {
        document.getElementById("totalCarbs").style.color = "red";
    }

    if (totalNutrients.fat.toFixed(2) >= 30) {
        document.getElementById("totalFat").style.color = "red";
    } else if (totalNutrients.fat.toFixed(2) >= 15 && totalNutrients.fat.toFixed(2) < 30) {
        document.getElementById("totalFat").style.color = "black";
    } else {
        document.getElementById("totalFat").style.color = "green";
    }
}


function adjustServing(amount) {
    serving = Math.max(1, serving + amount);
    document.getElementById("serving").innerText = serving;

    updateValue();
}

function adjustNutrients(slider, type) {
    const multiplier = parseFloat(slider.value);
    ingredientMultipliers[type] = multiplier;
    document.getElementById(slider.id + "Amount").innerText = "x" + multiplier;

    updateValue();
}

function resetValues() {
    for (const type in ingredientMultipliers) {
        ingredientMultipliers[type] = 1;
        document.getElementById(type).value = 1;
        document.getElementById(type + "Amount").innerText = "x1";
    }

    serving = 1;
    document.getElementById("serving").innerText = serving;

    updateValue();

    document.getElementById("totalCalories").style.color = "black";
    document.getElementById("totalProtein").style.color = "black";
    document.getElementById("totalCarbs").style.color = "black";
    document.getElementById("totalFat").style.color = "black";
}

function prepareInstructions() {
    document.getElementById('updatedServing').value = serving;
    document.getElementById('updatedMultipliers').value = JSON.stringify(ingredientMultipliers);
    return true;
}

function toggleFavorite() {
    var heart = document.getElementById('heart-icon');
    var button = heart.parentElement;
    var recipeId = button.getAttribute('data-recipe-id');

    button.classList.toggle('loved');

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_favorite.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    var isFavorite = button.classList.contains('loved');
    xhr.send("recipeId=" + recipeId + "&isFavorite=" + isFavorite);

    if (isFavorite) {
        button.innerHTML = '<span id="heart-icon" class="heart">&#10084;&#65039;</span> Added to Favorites';
    } else {
        button.innerHTML = '<span id="heart-icon" class="heart">&#10084;&#65039;</span> Add to Favorites';
    }
}