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

const triggerHeroAnimation = () => {
    const heroSection = document.querySelector('.landing-page-hero-section');
    if (heroSection) heroSection.classList.add('hero-animate');
};

if (sessionStorage.getItem('loaded')) {
    loadingScreen.classList.add('is-done');
    requestAnimationFrame(() => requestAnimationFrame(triggerHeroAnimation));
} else {
    const loadingImg = document.getElementById('loadingImg');
    const baseUrl = loadingImg.src.replace('loading-1.webp', '');
    const frames = [1, 2, 3, 4];
    let current = 0;
    let pageLoaded = false;
    let hiding = false;

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
        triggerHeroAnimation();
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


// FRONT PAGE SCROLL ANIMATIONS
const frontPageAnimated = document.querySelectorAll('.homepage-content, .homepage-latest');
if (frontPageAnimated.length) {
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                scrollObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    frontPageAnimated.forEach(el => scrollObserver.observe(el));
}

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

    // Android/browser theme-color frissítése
    const metaThemeColor = document.querySelector('meta[name="theme-color"]') || (() => {
        const m = document.createElement('meta');
        m.name = 'theme-color';
        document.head.appendChild(m);
        return m;
    })();

    function updateThemeColor() {
        metaThemeColor.content = getComputedStyle(sections[currentIndex]).backgroundColor;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                currentIndex = sections.indexOf(entry.target);
                updateThemeColor();
            }
        });
    }, { root: trainingWrapper, threshold: 0.6 });
    sections.forEach(s => observer.observe(s));
    updateThemeColor();

    function goToSection(index) {
        if (index < 0 || index >= total) return;
        trainingWrapper.scrollTo({ top: sections[index].offsetTop, behavior: 'smooth' });
    }

    document.querySelectorAll('.training-nav-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            goToSection(currentIndex + parseInt(btn.dataset.dir));
        });
    });

    // Edzések menü (nav-menu-btn)
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

    document.querySelectorAll('.training-menu a[data-index]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            goToSection(parseInt(link.dataset.index));
            closeTrainingMenu();
        });
    });

    // Oldalnavigáció menü (hamburger)
    const trainingPageMenu = document.getElementById('trainingPageMenu');
    const trainingPageMenuClose = document.getElementById('trainingPageMenuClose');
    const trainingHamburger = document.getElementById('trainingHamburger');

    function openPageMenu() {
        trainingPageMenu.style.display = 'flex';
        trainingPageMenu.setAttribute('aria-hidden', 'false');
        if (trainingHamburger) {
            trainingHamburger.classList.add('is-open');
            trainingHamburger.setAttribute('aria-expanded', 'true');
        }
        trainingPageMenuClose.focus();
    }

    function closePageMenu() {
        trainingPageMenu.style.display = 'none';
        trainingPageMenu.setAttribute('aria-hidden', 'true');
        if (trainingHamburger) {
            trainingHamburger.classList.remove('is-open');
            trainingHamburger.setAttribute('aria-expanded', 'false');
        }
    }

    if (trainingHamburger) {
        trainingHamburger.addEventListener('click', () => {
            if (trainingPageMenu.style.display === 'flex') {
                closePageMenu();
            } else {
                openPageMenu();
            }
        });
        trainingHamburger.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                trainingHamburger.click();
            }
        });
    }

    if (trainingPageMenuClose) {
        trainingPageMenuClose.addEventListener('click', closePageMenu);
    }

    // Mehr erfahren modal
    const trainingDetail = document.getElementById('trainingDetail');
    let lastDetailTrigger = null;

    function openDetail(btn) {
        lastDetailTrigger = btn;
        document.getElementById('trainingDetailTitle').textContent = btn.dataset.title;
        const desc = (window.trainingDescriptions || [])[parseInt(btn.dataset.index)] || '';
        document.getElementById('trainingDetailDesc').innerHTML = desc;
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

    // Escape
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        if (trainingDetail.classList.contains('is-open')) closeDetail();
        else if (trainingPageMenu.style.display === 'flex') closePageMenu();
        else if (trainingMenu.classList.contains('is-open')) closeTrainingMenu();
    });

    // Swipe
    let touchStartY = 0;
    trainingWrapper.addEventListener('touchstart', (e) => {
        touchStartY = e.touches[0].clientY;
    }, { passive: true });
    trainingWrapper.addEventListener('touchmove', (e) => {
        if (trainingDetail && trainingDetail.classList.contains('is-open')) return;
        e.preventDefault();
    }, { passive: false });
    trainingWrapper.addEventListener('touchend', (e) => {
        if (trainingDetail && trainingDetail.classList.contains('is-open')) return;
        const diff = touchStartY - e.changedTouches[0].clientY;
        if (Math.abs(diff) > 60) {
            goToSection(currentIndex + (diff > 0 ? 1 : -1));
        }
    }, { passive: true });
}


// LIGHTBOX FROM HERE
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