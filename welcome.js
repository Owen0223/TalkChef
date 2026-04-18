const images = [
    "../TalkChef/image/welcomeBG.jpg",
    "../TalkChef/image/welcomeBG2.jpg",
    "../TalkChef/image/welcomeBG3.jpg"
];

let currentIndex = 0;
const slideshowElement = document.getElementById("slideshow");

function changeImage() {
    currentIndex = (currentIndex + 1) % images.length;
    slideshowElement.src = images[currentIndex];
}

setInterval(changeImage, 5000);