const foodImages = [
    '../TalkChef/image/rice.png',
    '../TalkChef/image/broccoli.png',
    '../TalkChef/image/meat.png',
    '../TalkChef/image/cheese.png',
    '../TalkChef/image/corn.png',
    '../TalkChef/image/carrot.png',
    '../TalkChef/image/tomato.png'
];

function createFoodItem() {
    const foodItem = document.createElement('img');
    foodItem.src = foodImages[Math.floor(Math.random() * foodImages.length)];
    foodItem.classList.add('food-item');

    foodItem.style.left = `${Math.random() * 200}vw`;

    document.getElementById('background-animation').appendChild(foodItem);

    foodItem.addEventListener('animationend', () => {
        foodItem.remove();
    });
}

setInterval(createFoodItem, 1800);
