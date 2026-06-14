const hamburger = document.getElementById('hamburgerMenu');
const mobileMenu = document.getElementById('mobileMenu');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('is-open');
    mobileMenu.classList.toggle('is-open');
});

// LOADING SCREEN FROM HERE | LOADING SCREAM FROM HERE
if (performance.navigation.type === 1) {
    sessionStorage.removeItem('loaded');
}

const loadingScreen = document.getElementById('loadingScreen');

if (sessionStorage.getItem('loaded')) {
    loadingScreen.classList.add('is-done');
} else {
    const loadingImg = document.getElementById('loadingImg');
    const baseUrl = loadingImg.src.replace('loading-1.png', '');
    const frames = [1, 2, 3, 4];
    let current = 0;
    let pageLoaded = false;

    const interval = setInterval(() => {
        current = (current + 1) % frames.length;
        loadingImg.src = baseUrl + 'loading-' + frames[current] + '.png';

        if (frames[current] === 4 && pageLoaded) {
            clearInterval(interval);
            setTimeout(() => {
                loadingScreen.classList.add('is-done');
                sessionStorage.setItem('loaded', 'true');
            }, 500);
        }
    }, 350);

    window.addEventListener('load', () => {
        pageLoaded = true;
    });
}

let randomTextNumber = Math.floor((Math.random()* 17) + 1);
let loadingTexts = [
    "Einen Moment bitte, der Hund schnuppert noch ...",
    "Leckerlis werden vorbereitet ...",
    "Ball wird gesucht ...",
    "Schwanzwedeln wird kalibriert ...",
    "Fast fertig, nur noch kurz schnüffeln!",
    "Pfoten werden startklar gemacht ...",
    "Training wird vorbereitet ...",
    "Gemeinsam zum Erfolg ...",
    "Fokus. Vertrauen. Training ...",
    "Der Weg zum entspannten Hund startet gleich ...",
    "Training lädt ...",
    "Ich komme gleich, ich schnuppere nur noch kurz! ...",
    "Wo sind meine Leckerlis? ...",
    "Gleich geht's los! ...",
    "Lade gutes Benehmen ...",
    "Sitz. Platz. Los ...",
    "Vertrauen wird aufgebaut ...",
];
document.getElementById("loadingText").innerHTML = loadingTexts[randomTextNumber - 1];

// GALLERY FILTER FROM HERE
const filterBtns = document.querySelectorAll('.filter-btn');
const galleryItems = document.querySelectorAll('.gallery-item');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');

        const selected = btn.dataset.album;

        galleryItems.forEach(item => {
            if (selected === 'all' || item.dataset.album.includes(selected)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});