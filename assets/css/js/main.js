// ── Navbar scroll ──
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 10);
});

// ── Banner carrusel ──
let currentSlide = 0;
const totalSlides = 4;
const track = document.getElementById('bannerTrack');
const dots = document.querySelectorAll('.dot');

function goSlide(n) {
  currentSlide = (n + totalSlides) % totalSlides;
  track.style.transform = `translateX(-${currentSlide * 100}%)`;
  dots.forEach((d, i) => d.classList.toggle('active', i === currentSlide));
}

function nextSlide() { goSlide(currentSlide + 1); }
function prevSlide() { goSlide(currentSlide - 1); }

// Exponer funciones al scope global para los botones inline del HTML
window.goSlide = goSlide;
window.nextSlide = nextSlide;
window.prevSlide = prevSlide;

// Auto-play banner
setInterval(nextSlide, 4500);

// ── Carrusel Campañas ──
let campIdx = 0;
const campTrack = document.getElementById('campTrack');
const campCards = campTrack.querySelectorAll('.camp-card');
const campVisible = () => window.innerWidth < 700 ? 1 : 2;

function moveCamp(dir) {
  const max = campCards.length - campVisible();
  campIdx = Math.max(0, Math.min(campIdx + dir, max));
  campTrack.style.transform = `translateX(-${campIdx * 270}px)`;
}

document.getElementById('campPrev').addEventListener('click', () => moveCamp(-1));
document.getElementById('campNext').addEventListener('click', () => moveCamp(1));

// ── Carrusel Testimonios ──
let testIdx = 0;
const testTrack = document.getElementById('testTrack');
const testCards = testTrack.querySelectorAll('.test-card');

function moveTest(dir) {
  testIdx = (testIdx + dir + testCards.length) % testCards.length;
  testTrack.style.transform = `translateX(-${testIdx * 100}%)`;
}

document.getElementById('testPrev').addEventListener('click', () => moveTest(-1));
document.getElementById('testNext').addEventListener('click', () => moveTest(1));

// Auto-play testimonios
setInterval(() => moveTest(1), 5500);

// ── Contador estadísticas ──
function animateCount(el) {
  const target = parseInt(el.dataset.target);
  const prefix = el.dataset.prefix || '';
  let cur = 0;
  const step = target / 55;
  const t = setInterval(() => {
    cur = Math.min(cur + step, target);
    el.textContent = prefix + Math.floor(cur).toLocaleString();
    if (cur >= target) clearInterval(t);
  }, 22);
}

const obs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      animateCount(e.target);
      obs.unobserve(e.target);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-num').forEach(el => obs.observe(el));