let stepNo = 1;
let time = 0;
let oritime;
let initime;
let interval;
let isPaused = false;

const highlightElement = document.getElementById("highlight");

const motivationalPhrases = [
    "Good job!",
    "Excellent!",
    "Well done!",
    "Keep it up!",
    "You're doing great!",
    "Fantastic work!",
    "You're on the right track!",
    "Awesome!"
];

function getRandomMotivationalPhrase() {
    return Math.random() > 0.5 ? motivationalPhrases[Math.floor(Math.random() * motivationalPhrases.length)] : '';
}

window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

const recognition = new window.SpeechRecognition();
recognition.interimResults = true;

recognition.addEventListener('result', (e) => {
    const text = Array.from(e.results)
        .map(result => result[0])
        .map(result => result.transcript)
        .join('');

    if (e.results[0].isFinal) {
        if (text.includes('Next')) {
            changeStep(1);
        }
        if (text.includes('Previous') || text.includes('Back')) {
            changeStep(-1);
        }
        if (time != 0) {
            if (text.includes('Start timer') || text.includes('Start')) {
                startCountdown();
            }
        }
        if (text.includes('Pause timer') || text.includes('Pause')) {
            pauseCountdown();
        }
        if (text.includes('Cancel timer') || text.includes('Cancel')) {
            cancelCountdown();
        }
        if (text.includes('Exit')) {
            exitStep();
        }
        if (stepNo == maxNo && text.includes('Done')) {
            doneStep();
        }
    }

    console.log(e);
});

recognition.addEventListener('end', () => {
    recognition.start();
});

recognition.start();

window.onload = function () {
    changeStep(0);
}

function playInstruction(text) {
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 1;
    speechSynthesis.speak(utterance);
}

function changeStep(signal) {
    speechSynthesis.cancel();
    stepNo += signal;

    if (stepNo < 1) {
        stepNo = 1;
    }

    if (stepNo === 1) {
        document.getElementById("step-button left-button").style.display = 'none';
    } else {
        document.getElementById("step-button left-button").style.display = 'block';
    }

    if (stepNo > maxNo) {
        stepNo = maxNo;
    }

    if (stepNo === maxNo) {
        document.getElementById("done").style.display = 'block';
        document.getElementById("step-button right-button").style.display = 'none';
    } else {
        document.getElementById("done").style.display = 'none';
        document.getElementById("step-button right-button").style.display = 'block';
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "getsteps.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.instructions && Object.keys(response.instructions).length > 0) {
                let actionKey = Object.keys(response.instructions)[0];

                if (response.instructions[actionKey]) {
                    document.getElementById("step-instruction").innerHTML = response.no + '. ' + response.preInstruction + ' ' + response.instructions[actionKey] + ' ' + response.postInstruction;
                } else {
                    document.getElementById("step-instruction").innerHTML = response.no + '. ' + response.preInstruction + ' ' + response.postInstruction;
                }
            } else {
                document.getElementById("step-instruction").innerHTML = response.no + '. ' + response.preInstruction + ' ' + response.postInstruction;
            }

            if (stepNo === 1) {
                playInstruction("Let's Start Cooking!");
                playInstruction(document.getElementById("step-instruction").innerHTML);
            } else if (stepNo == maxNo) {
                playInstruction("Last Step!");
                playInstruction(document.getElementById("step-instruction").innerHTML);
            } else {
                let motivationText = getRandomMotivationalPhrase();
                playInstruction(motivationText);
                playInstruction(document.getElementById("step-instruction").innerHTML);
            }

            if (response.duration > 0) {
                document.getElementById("side-container").style.display = 'block';
                document.getElementById("timer").style.display = 'block';
                document.getElementById("pausetimer").style.display = 'block';
                document.getElementById("canceltimer").style.display = 'block';
                document.getElementById("countdown").style.display = 'block';

                time = response.duration * 60;
                initime = time;

                let minutes = Math.floor(time / 60);
                let seconds = Math.floor(time % 60);

                let formattedMinutes = String(minutes).padStart(2, '0');
                let formattedSeconds = String(seconds).padStart(2, '0');

                document.getElementById("countdown").innerText =
                    `${formattedMinutes}:${formattedSeconds}`;
            } else {
                document.getElementById("side-container").style.display = 'none';
                document.getElementById("timer").style.display = 'none';
                document.getElementById("pausetimer").style.display = 'none';
                document.getElementById("canceltimer").style.display = 'none';
                document.getElementById("countdown").style.display = 'none';
            }

            if (response.userId > 0) {
                document.getElementById("step-notes").style.display = "block";
                document.getElementById("no").innerHTML = "Step " + response.no + " Notes";

                document.querySelectorAll("input[name='stepNo']").forEach((input) => {
                    input.value = response.no;
                });

                if (response.noteId) {
                    document.getElementById("editNote").value = response.note;
                    document.getElementById("editId").value = response.noteId;
                    document.getElementById("deleteId").value = response.noteId;
                    document.getElementById("have-note").style.display = "block";
                    document.getElementById("dont-have-note").style.display = "none";
                } else {
                    document.getElementById("stepNote").value = "";
                    document.getElementById("have-note").style.display = "none";
                    document.getElementById("dont-have-note").style.display = "block";
                }
            } else {
                document.getElementById("step-notes").style.display = "none";
            }

            if (response.highlight != '') {
                document.getElementById("highlight").style.display = 'block';
                document.getElementById("highlight").innerHTML = '<h2>Reminder!!!</h2>' + response.highlight;

                highlightElement.classList.add("shake");

                const sound = new Audio('../TalkChef/mp3/ding-sound.mp3');
                sound.play();

                setTimeout(() => {
                    highlightElement.classList.remove("shake");
                }, 500);
            } else {
                document.getElementById("highlight").style.display = 'none';
                document.getElementById("highlight").innerHTML = '';
            }
        }
    };

    var recipeId = document.getElementById("recipeId").value;

    xhr.send("recipeId=" + recipeId + "&no=" + stepNo + "&updatedServing=" + updatedServing + "&updatedMultipliers=" + JSON.stringify(updatedMultipliers));
}

function updateCountdown() {
    const countdownEl = document.getElementById('countdown');

    interval = setInterval(function () {
        if (!isPaused) {
            const minutes = Math.floor(time / 60);
            let seconds = time % 60;

            let formattedMinutes = String(minutes).padStart(2, '0');
            let formattedSeconds = String(seconds).padStart(2, '0');

            countdownEl.innerHTML = `${formattedMinutes}:${formattedSeconds}`;

            time--;

            if (time < 0) {
                clearInterval(interval);
                if (Notification.permission === "granted") {
                    const alarmSound = new Audio("../TalkChef/mp3/clock-alarm.mp3");
                    alarmSound.loop = true;
                    document.getElementById("timer").style.display = 'none';
                    document.getElementById("pausetimer").style.display = 'none';
                    document.getElementById("canceltimer").style.display = 'none';
                    const stopAlarm = document.getElementById("stopAlarm");
                    stopAlarm.style.display = 'block';

                    const notification = new Notification("TalkChef's Reminder", {
                        body: "Time's Up! Come Back!",
                        requireInteraction: true
                    });

                    alarmSound.play();

                    stopAlarm.addEventListener("click", () => {
                        alarmSound.loop = false;
                        alarmSound.pause();
                        alarmSound.currentTime = 0;
                        document.getElementById("timer").style.display = 'block';
                        document.getElementById("pausetimer").style.display = 'block';
                        document.getElementById("canceltimer").style.display = 'block';
                        stopAlarm.style.display = 'none';
                    });

                    recognition.addEventListener('result', (e) => {
                        const text = Array.from(e.results)
                            .map(result => result[0])
                            .map(result => result.transcript)
                            .join('');

                        if (e.results[0].isFinal) {
                            if (text.includes('Stop alarm') || text.includes('Cancel alarm') || text.includes('Stop') || text.includes('Cancel')) {
                                alarmSound.loop = false;
                                alarmSound.pause();
                                alarmSound.currentTime = 0;
                                document.getElementById("timer").style.display = 'block';
                                document.getElementById("pausetimer").style.display = 'block';
                                document.getElementById("canceltimer").style.display = 'block';
                                stopAlarm.style.display = 'none';
                                cancelCountdown();
                            }
                        }

                        console.log(e);
                    });

                    notification.addEventListener("error", e => {
                        alert("Error 404");
                        alarmSound.loop = false;
                        alarmSound.pause();
                        alarmSound.currentTime = 0;
                    })
                }
            }
        }
    }, 1300);
}

function startCountdown() {
    if (interval) {
        return;
    }

    oritime = time;
    isPaused = false;

    Notification.requestPermission().then(perm => {
        if (perm === "granted") {
            updateCountdown();
        } else {
            alert("Please Give Permission for Notification!");
        }
    });
}

function pauseCountdown() {
    isPaused = true;
    clearInterval(interval);
    interval = null;
}

function cancelCountdown() {
    clearInterval(interval);
    interval = null;

    time = initime;

    const minutes = Math.floor(time / 60);
    let seconds = time % 60;

    let formattedMinutes = String(minutes).padStart(2, '0');
    let formattedSeconds = String(seconds).padStart(2, '0');

    document.getElementById("countdown").innerText =
        `${formattedMinutes}:${formattedSeconds}`;
}

function doneStep() {
    speechSynthesis.cancel();
    window.location.href = "done.php";
}

function exitStep() {
    speechSynthesis.cancel();
    document.getElementById("exit-form").submit();
}

function saveStepNote() {

    const recipeId = document.getElementById("recipeId").value;
    const stepNo = document.querySelector("input[name='stepNo']").value;
    const stepNote = document.getElementById("stepNote").value;
    const source = "steps";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("have-note").style.display = "block";
            document.getElementById("dont-have-note").style.display = "none";
            document.getElementById("editNote").value = stepNote;
        }
    };

    xhr.send("recipeId=" + encodeURIComponent(recipeId) +
        "&stepNo=" + encodeURIComponent(stepNo) +
        "&stepNote=" + encodeURIComponent(stepNote) +
        "&source=" + encodeURIComponent(source));
}

function editStepNote() {
    const recipeId = document.getElementById("recipeId").value;
    const stepNo = document.querySelector("input[name='stepNo']").value;
    const noteId = document.getElementById("editId").value;
    const recipeNote = document.getElementById("editNote").value;
    const source = "steps";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "edit_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("have-note").style.display = "block";
            document.getElementById("dont-have-note").style.display = "none";
        }
    };

    xhr.send("recipeId=" + encodeURIComponent(recipeId) +
        "&stepNo=" + encodeURIComponent(stepNo) +
        "&noteId=" + encodeURIComponent(noteId) +
        "&recipeNote=" + encodeURIComponent(recipeNote) +
        "&source=" + encodeURIComponent(source));
}

function deleteStepNote() {
    const recipeId = document.getElementById("recipeId").value;
    const stepNo = document.querySelector("input[name='stepNo']").value;
    const noteId = document.getElementById("deleteId").value;
    const source = "steps";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "delete_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("stepNote").value = "";
            document.getElementById("have-note").style.display = "none";
            document.getElementById("dont-have-note").style.display = "block";
        }
    };

    xhr.send("recipeId=" + encodeURIComponent(recipeId) +
        "&stepNo=" + encodeURIComponent(stepNo) +
        "&noteId=" + encodeURIComponent(noteId) +
        "&source=" + encodeURIComponent(source));
}