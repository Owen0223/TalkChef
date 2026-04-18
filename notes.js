function showRecipe(catId) {
    var container = document.getElementById("recipes-container");
    container.style.display = "none";

    var backgroundColor, buttonColor, textColor;

    switch (catId) {
        case 1:
            backgroundColor = "#A8ED84";
            buttonColor = "#4A8E27";
            textColor = "#fff";
            break;
        case 2:
            backgroundColor = "#FF8484";
            buttonColor = "#CD0F0F";
            textColor = "#fff";
            break;
        case 3:
            backgroundColor = "#DE84ED";
            buttonColor = "#A137B3";
            textColor = "#fff";
            break;
        case 4:
            backgroundColor = "#87CEFA";
            buttonColor = "#377CA7";
            textColor = "#fff";
            break;
        default:
            backgroundColor = "#EAD2A8";
            buttonColor = "#fd5426";
            textColor = "#fff";
            break;
    }

    document.body.style.backgroundColor = backgroundColor;

    var buttons = document.querySelectorAll("#category button");

    buttons.forEach(button => {
        button.style.backgroundColor = buttonColor;
        button.style.color = textColor;
    });

    fetchRecipe(catId, container);
}

function fetchRecipe(catId, container) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            container.innerHTML = xhr.responseText;
            container.style.display = "block";
        }
    };
    xhr.open("GET", "notes-category.php?catId=" + catId, true);
    xhr.send();
}