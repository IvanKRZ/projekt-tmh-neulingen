const hamburger = document.getElementById('hamburgerMenu');
const mobileMenu = document.getElementById('mobileMenu');

hamburger.addEventListener('click', () => {
    const isOpen = hamburger.classList.toggle('is-open');
    mobileMenu.classList.toggle('is-open');
    hamburger.setAttribute('aria-expanded', isOpen);
    mobileMenu.setAttribute('aria-hidden', !isOpen);
});

// LOADING SCREEN FROM HERE | LOADING SCREAM FROM HERE
if (performance.getEntriesByType('navigation')[0]?.type === 'reload') {
    sessionStorage.removeItem('loaded');
}

const loadingScreen = document.getElementById('loadingScreen');

if (sessionStorage.getItem('loaded')) {
    loadingScreen.classList.add('is-done');
} else {
    const loadingImg = document.getElementById('loadingImg');
    const baseUrl = loadingImg.src.replace('loading-1.webp', '');
    const frames = [1, 2, 3, 4];
    let current = 0;
    let pageLoaded = false;
    let hiding = false;

    // Preloading the 4 imgs
    const preloaded = frames.map(n => {
        const img = new Image();
        img.src = baseUrl + 'loading-' + n + '.webp';
        return img;
    });

    const hideLoading = () => {
        if (hiding) return;
        hiding = true;
        clearInterval(interval);
        loadingScreen.classList.add('is-done');
        sessionStorage.setItem('loaded', 'true');
    };

    const interval = setInterval(() => {
        current = (current + 1) % frames.length;
        loadingImg.src = preloaded[current].src;

        if (pageLoaded && frames[current] === 3) {
            setTimeout(hideLoading, 500);
        }
    }, 350);

    document.addEventListener('DOMContentLoaded', () => { pageLoaded = true; });

    setTimeout(hideLoading, 5000);

    window.addEventListener('load', () => { pageLoaded = true; });
}

let randomTextNumber = Math.floor((Math.random() * 17) + 1);
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
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });
});

// TRAINING PAGE
const trainingWrapper = document.getElementById('trainingWrapper');
if (trainingWrapper) {
    const sections = Array.from(document.querySelectorAll('.training-section'));
    const total = sections.length;
    let currentIndex = 0;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                currentIndex = sections.indexOf(entry.target);
            }
        });
    }, { root: trainingWrapper, threshold: 0.6 });
    sections.forEach(s => observer.observe(s));

    function goToSection(index) {
        if (index < 0 || index >= total) return;
        trainingWrapper.scrollTo({ top: sections[index].offsetTop, behavior: 'smooth' });
    }

    document.querySelectorAll('.training-nav-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            goToSection(currentIndex + parseInt(btn.dataset.dir));
        });
    });

    document.querySelectorAll('.training-menu a[data-index]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            goToSection(parseInt(link.dataset.index));
            closeTrainingMenu();
        });
    });

    const trainingMenu = document.getElementById('trainingMenu');

    function openTrainingMenu() {
        trainingMenu.classList.add('is-open');
        trainingMenu.setAttribute('aria-hidden', 'false');
        document.querySelectorAll('.training-nav-menu-btn').forEach(b => b.setAttribute('aria-expanded', 'true'));
        document.getElementById('trainingMenuClose').focus();
    }
    function closeTrainingMenu() {
        trainingMenu.classList.remove('is-open');
        trainingMenu.setAttribute('aria-hidden', 'true');
        document.querySelectorAll('.training-nav-menu-btn').forEach(b => b.setAttribute('aria-expanded', 'false'));
    }
    document.querySelectorAll('.training-nav-menu-btn').forEach(btn => btn.addEventListener('click', openTrainingMenu));
    document.getElementById('trainingMenuClose').addEventListener('click', closeTrainingMenu);

    const trainingDetail = document.getElementById('trainingDetail');
    let lastDetailTrigger = null;

    function openDetail(btn) {
        lastDetailTrigger = btn;
        document.getElementById('trainingDetailTitle').textContent = btn.dataset.title;
        document.getElementById('trainingDetailDesc').textContent = btn.dataset.desc;
        trainingDetail.classList.add('is-open');
        trainingDetail.setAttribute('aria-hidden', 'false');
        btn.setAttribute('aria-expanded', 'true');
        document.getElementById('trainingDetailClose').focus();
    }

    function closeDetail() {
        trainingDetail.classList.remove('is-open');
        trainingDetail.setAttribute('aria-hidden', 'true');
        if (lastDetailTrigger) {
            lastDetailTrigger.setAttribute('aria-expanded', 'false');
            lastDetailTrigger.focus();
        }
    }
    document.querySelectorAll('.training-mehr').forEach(btn => btn.addEventListener('click', () => openDetail(btn)));
    document.getElementById('trainingDetailClose').addEventListener('click', closeDetail);

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        if (trainingDetail.classList.contains('is-open')) closeDetail();
        else if (trainingMenu.classList.contains('is-open')) closeTrainingMenu();
    });
}

if (typeof lightbox !== 'undefined') {
    lightbox.option({
        fitImagesInViewport: true,
        maxWidth: window.innerWidth * 0.92,
        maxHeight: window.innerHeight * 0.88,
        positionFromTop: 40,
        wrapAround: true,
        albumLabel: "Bild %1 von %2"
    });
}