<?php
/**
 * Template Name: 404
 */
get_header();
?>
<style>
body.error404 {
    margin: 0;
    padding: 0;
    overflow: hidden;
    background: #FFF8F0;
}
body.error404 .navigation-menu,
body.error404 .floating-buttons {
    display: none !important;
}
#canvas-bg,
#canvas-fg {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    touch-action: none;
}
#canvas-bg { z-index: 0; }
#canvas-fg { z-index: 1; pointer-events: none; }

.e404-overlay {
    position: fixed;
    left: 0; right: 0;
    bottom: 195px;
    z-index: 2;
    text-align: center;
    padding: 0 20px;
    box-sizing: border-box;
}
.e404-subtitle {
    font-family: Arial, sans-serif;
    font-size: clamp(1rem, 2.5vw, 1.6rem);
    font-weight: 700;
    color: #FF6EB5;
    margin: 0 0 1rem;
}
.e404-btn {
    display: inline-block;
    padding: 0.75rem 2.2rem;
    background: #F0B429;
    color: #fff;
    text-decoration: none;
    border-radius: 50px;
    font-family: Arial, sans-serif;
    font-size: clamp(0.85rem, 1.5vw, 1.1rem);
    font-weight: 700;
    white-space: nowrap;
    box-shadow: 0 4px 18px rgba(240,180,41,.4);
    transition: transform .15s ease, box-shadow .15s ease;
}
.e404-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 22px rgba(240,180,41,.55);
    color: #fff;
}
@media (max-height: 500px) {
    .e404-overlay { bottom: 120px; }
    .e404-subtitle { font-size: 0.85rem; margin-bottom: 0.5rem; }
    .e404-btn { padding: 0.5rem 1.4rem; font-size: 0.82rem; }
}
@media (max-height: 380px) {
    .e404-overlay { display: none; }
}
</style>

<canvas id="canvas-bg"></canvas>
<canvas id="canvas-fg"></canvas>

<div class="e404-overlay">
    <p class="e404-subtitle">Diese Seite existiert nicht!</p>
    <a class="e404-btn" href="<?php echo esc_url( home_url('/') ); ?>">← Zurück zur Startseite</a>
</div>

<script>
(function () {
    'use strict';

    const PAL = ['#F0B429', '#FF6EB5', '#4DAAEC', '#60ac60'];
    const BG  = '#FFF8F0';

    /* ── viewport méret (clientWidth megbízhatóbb mobilon) ── */
    const W   = document.documentElement.clientWidth;
    const H   = document.documentElement.clientHeight;
    const DPR = Math.min(window.devicePixelRatio || 1, 2);

    document.addEventListener('touchmove', function (e) {
        e.preventDefault();
    }, { passive: false });

    /* ── canvas setup (DPR-aware = éles retina kijelzőn) ─── */
    const bgC = document.getElementById('canvas-bg');
    const fgC = document.getElementById('canvas-fg');

    bgC.width  = fgC.width  = Math.round(W * DPR);
    bgC.height = fgC.height = Math.round(H * DPR);
    bgC.style.width  = fgC.style.width  = W + 'px';
    bgC.style.height = fgC.style.height = H + 'px';

    const bgX = bgC.getContext('2d');
    const fgX = fgC.getContext('2d');
    bgX.scale(DPR, DPR);
    fgX.scale(DPR, DPR);

    /* ── lebegő háttér blobs ─────────────────────────────── */
    const BLOBS = Array.from({ length: 20 }, () => ({
        x:  Math.random() * W,
        y:  Math.random() * H,
        r:  14 + Math.random() * 36,
        c:  PAL[Math.floor(Math.random() * 4)],
        vx: (Math.random() - .5) * .28,
        vy: (Math.random() - .5) * .28,
        ph: Math.random() * Math.PI * 2,
    }));

    /* ── 404 részecskefelhő ──────────────────────────────── */
    /*
     * FIX: Nagy fix méretű offscreen canvas + Arial (universális font).
     * Az Impact/Arial Black iOS-on nem elérhető → csendesen 10px-re esett vissza
     * → szinte 0 mintavételezett pont → mini cluster.
     * Megoldás: 1120×350 canvas, bold Arial 300px, STEP=16, akkor is működik.
     */
    const OFF_W = 1120, OFF_H = 350;
    const offC  = document.createElement('canvas');
    offC.width  = OFF_W;
    offC.height = OFF_H;
    const offX  = offC.getContext('2d');
    offX.fillStyle      = '#000';
    offX.font           = 'bold 300px Arial, sans-serif';
    offX.textAlign      = 'center';
    offX.textBaseline   = 'middle';
    offX.fillText('404', OFF_W / 2, OFF_H / 2);

    const pxData = offX.getImageData(0, 0, OFF_W, OFF_H).data;
    const rawPts = [];
    const STEP   = 16;
    for (let y = 0; y < OFF_H; y += STEP)
        for (let x = 0; x < OFF_W; x += STEP)
            if (pxData[(y * OFF_W + x) * 4 + 3] > 60)
                rawPts.push({ fx: x / OFF_W, fy: y / OFF_H });  /* 0-1 arányok */

    /* 404 mérete a képernyőhöz igazítva */
    const ftW = Math.min(W * 0.82, 520);
    const ftH = ftW * (OFF_H / OFF_W);   /* eredeti arány megtartva */
    const tOX = (W - ftW) / 2;
    const tOY = H * 0.05;
    const PR  = Math.max(2.5, ftW / 140); /* részecske mérete arányos */

    /* burst origó = a 404 közepe */
    const bOX = W / 2;
    const bOY = tOY + ftH / 2;

    const PARTS = rawPts.map((p, i) => {
        const a   = Math.random() * Math.PI * 2;
        const spd = 2.5 + Math.random() * 4;
        return {
            x:  bOX + (Math.random() - .5) * 30,
            y:  bOY + (Math.random() - .5) * 20,
            vx: Math.cos(a) * spd,
            vy: Math.sin(a) * spd,
            tx: tOX + p.fx * ftW,   /* cél = arányos koordináta → képernyőre vetítve */
            ty: tOY + p.fy * ftH,
            c:  PAL[i & 3],
            r:  PR + Math.random() * PR * .4,
            ph: Math.random() * Math.PI * 2,
        };
    });

    const T_FREE = 1600;
    const T_PULL = 2000;

    function tickParticles(t) {
        if (t < T_FREE) {
            PARTS.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0 || p.x > W) p.vx *= -1;
                if (p.y < 0 || p.y > H * .76) p.vy *= -1;
            });
        } else if (t < T_FREE + T_PULL) {
            PARTS.forEach(p => {
                p.x += (p.tx - p.x) * .055;
                p.y += (p.ty - p.y) * .055;
            });
        } else {
            PARTS.forEach(p => {
                p.x = p.tx + Math.sin(t * .0014 + p.ph) * 2;
                p.y = p.ty + Math.sin(t * .002  + p.ph * 1.4) * 1.5;
            });
        }
    }

    function drawBg(t) {
        bgX.fillStyle = BG;
        bgX.fillRect(0, 0, W, H);

        BLOBS.forEach(b => {
            b.x += b.vx; b.y += b.vy;
            if (b.x < -b.r - 50) b.x = W + b.r;
            if (b.x > W + b.r + 50) b.x = -b.r;
            if (b.y < -b.r - 50) b.y = H + b.r;
            if (b.y > H + b.r + 50) b.y = -b.r;
            bgX.save();
            bgX.globalAlpha = .10 + .04 * Math.sin(t * .001 + b.ph);
            bgX.beginPath();
            bgX.arc(b.x, b.y, b.r * (1 + .08 * Math.sin(t * .0015 + b.ph)), 0, Math.PI * 2);
            bgX.fillStyle = b.c;
            bgX.fill();
            bgX.restore();
        });

        PARTS.forEach(p => {
            bgX.beginPath();
            bgX.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            bgX.fillStyle = p.c;
            bgX.fill();
        });
    }

    /* ── Vizsla kutya ────────────────────────────────────── */
    const DOG_C = '#C87838';
    const DOG_D = '#A04F18';
    const DOG_N = '#2d0d02';
    const FLOOR = H - 55;

    const dog = {
        x:         -120,
        y:         FLOOR,
        dir:       1,
        fr:        0,
        state:     'walk',
        pTimer:    0,
        tilt:      0,
        bAlpha:    0,
        blinkCD:   80,
        blinkFr:   0,
        hasPaused: false,
    };
    const PAUSE_X  = W / 2;
    const PAUSE_FR = 220;
    const SPD      = 2.5;

    function tickDog() {
        if (dog.blinkFr > 0) {
            dog.blinkFr--;
        } else if (--dog.blinkCD <= 0) {
            dog.blinkFr = 6;
            dog.blinkCD = 65 + Math.floor(Math.random() * 105);
        }

        dog.fr += .12;

        if (dog.state === 'walk') {
            dog.x   += SPD * dog.dir;
            dog.tilt *= .88;
            if (dog.dir === 1 && !dog.hasPaused && dog.x >= PAUSE_X) {
                dog.state     = 'pause';
                dog.pTimer    = PAUSE_FR;
                dog.bAlpha    = 0;
                dog.hasPaused = true;
            }
            if (dog.x > W + 140) {
                dog.x         = -140;
                dog.hasPaused = false;
            }
        } else {
            dog.pTimer--;
            dog.tilt   = Math.sin(dog.fr * .038) * .18;
            dog.bAlpha = Math.min(1, dog.bAlpha + .04);
            if (dog.pTimer <= 0) {
                dog.state  = 'walk';
                dog.bAlpha = 0;
                dog.tilt   = 0;
            }
        }
    }

    function rrect(ctx, x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.lineTo(x + w - r, y);
        ctx.arcTo(x + w, y,     x + w, y + r,     r);
        ctx.lineTo(x + w, y + h - r);
        ctx.arcTo(x + w, y + h, x + w - r, y + h, r);
        ctx.lineTo(x + r, y + h);
        ctx.arcTo(x,     y + h, x,     y + h - r, r);
        ctx.lineTo(x,     y + r);
        ctx.arcTo(x,     y,     x + r, y,         r);
        ctx.closePath();
    }

    function drawDog() {
        fgX.clearRect(0, 0, W, H);

        const blink = dog.blinkFr > 0;
        const tail  = Math.sin(dog.fr * (dog.state === 'pause' ? .36 : .18)) * .52;

        fgX.save();
        fgX.translate(dog.x, dog.y);
        if (dog.dir < 0) fgX.scale(-1, 1);

        /* farok */
        fgX.save();
        fgX.translate(-36, -46);
        fgX.rotate(-.45 + tail);
        fgX.strokeStyle = DOG_C;
        fgX.lineWidth   = 8;
        fgX.lineCap     = 'round';
        fgX.beginPath();
        fgX.moveTo(0, 0);
        fgX.bezierCurveTo(-4, -16, 6, -28, 0, -42);
        fgX.stroke();
        fgX.restore();

        /* test */
        fgX.fillStyle = DOG_C;
        fgX.beginPath();
        fgX.ellipse(0, -48, 40, 20, 0, 0, Math.PI * 2);
        fgX.fill();

        /* négy láb */
        [[-18, 0], [-5, Math.PI], [8, Math.PI], [21, 0]].forEach(([lx, ph]) => {
            const s = dog.state === 'walk'
                ? Math.sin(dog.fr * .28 + ph) * .28
                : 0;
            fgX.save();
            fgX.translate(lx, -30);
            fgX.rotate(s);
            fgX.fillStyle = DOG_C;
            fgX.fillRect(-3.5, 0, 7, 16);
            fgX.translate(0, 16);
            fgX.rotate(-s * .55);
            fgX.fillRect(-3.5, 0, 7, 14);
            fgX.restore();
        });

        /* fej */
        fgX.save();
        fgX.translate(38, -60);
        fgX.rotate(dog.tilt);

        fgX.fillStyle = DOG_C;
        fgX.beginPath();
        fgX.ellipse(0, 0, 17, 14, 0, 0, Math.PI * 2);
        fgX.fill();

        fgX.fillStyle = DOG_D;
        fgX.beginPath();
        fgX.ellipse(-8, 8, 9, 13, -.2, 0, Math.PI * 2);
        fgX.fill();

        fgX.fillStyle = DOG_D;
        fgX.beginPath();
        fgX.ellipse(12, 5, 9, 7, .15, 0, Math.PI * 2);
        fgX.fill();
        fgX.fillStyle = DOG_C;
        fgX.beginPath();
        fgX.ellipse(10, 3.5, 7, 5.5, .15, 0, Math.PI * 2);
        fgX.fill();

        fgX.fillStyle = DOG_N;
        fgX.beginPath();
        fgX.ellipse(19, 4, 5, 3.5, .1, 0, Math.PI * 2);
        fgX.fill();

        if (blink) {
            fgX.strokeStyle = '#1a0800';
            fgX.lineWidth   = 2.5;
            fgX.lineCap     = 'round';
            fgX.beginPath();
            fgX.moveTo(3, -3); fgX.lineTo(11, -3);
            fgX.stroke();
        } else {
            fgX.fillStyle = '#1a0800';
            fgX.beginPath();
            fgX.arc(7, -4, 4.5, 0, Math.PI * 2);
            fgX.fill();
            fgX.fillStyle = 'rgba(255,255,255,.88)';
            fgX.beginPath();
            fgX.arc(9, -6, 1.5, 0, Math.PI * 2);
            fgX.fill();
        }

        fgX.restore(); /* fej */
        fgX.restore(); /* kutya transform */

        /* buborék – screen space */
        if (dog.bAlpha > 0) {
            const bx  = dog.x + (dog.dir > 0 ? 65 : -65);
            const by  = dog.y - 100;
            const txt = 'Wo bin ich?!';

            fgX.save();
            fgX.globalAlpha = dog.bAlpha;
            fgX.font        = 'bold 15px Arial, sans-serif';
            const tw  = fgX.measureText(txt).width;
                        const pad = 13, bw = tw + pad * 2, bh = 36;
            const bL  = bx - bw / 2, bT = by - bh / 2;

            fgX.fillStyle   = '#fff';
            fgX.strokeStyle = '#FF6EB5';
            fgX.lineWidth   = 2.5;
            rrect(fgX, bL, bT, bw, bh, 10);
            fgX.fill();
            fgX.stroke();

            const tipX = dog.dir > 0 ? bx - 16 : bx + 16;
            const tipY = bT + bh + 13;
            fgX.fillStyle = '#fff';
            fgX.beginPath();
            fgX.moveTo(bx - 7, bT + bh);
            fgX.lineTo(bx + 7, bT + bh);
            fgX.lineTo(tipX, tipY);
            fgX.closePath();
            fgX.fill();
            fgX.strokeStyle = '#FF6EB5';
            fgX.lineWidth   = 2.5;
            fgX.beginPath();
            fgX.moveTo(bx - 7, bT + bh); fgX.lineTo(tipX, tipY);
            fgX.moveTo(bx + 7, bT + bh); fgX.lineTo(tipX, tipY);
            fgX.stroke();

            fgX.fillStyle    = '#333';
            fgX.textAlign    = 'center';
            fgX.textBaseline = 'middle';
            fgX.fillText(txt, bx, by);
            fgX.restore();
        }
    }

    /* ── fő loop ─────────────────────────────────────────── */
    let t0 = null;
    function loop(ts) {
        if (!t0) t0 = ts;
        const t = ts - t0;
        tickParticles(t);
        tickDog();
        drawBg(t);
        drawDog();
        requestAnimationFrame(loop);
    }
    requestAnimationFrame(loop);
})();
</script>

<?php get_footer(); ?>