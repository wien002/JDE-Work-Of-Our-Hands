(function(){
  var prev = document.querySelector('.product-scroller .prev');
  var next = document.querySelector('.product-scroller .next');
  var track = document.querySelector('.product-track');
  var dotsBox = document.querySelector('.product-dots');
  if(!prev || !next || !track) return;
  var step = 320;
  function recalcStep(){
    var first = track.querySelector('.product-item');
    if(!first) return;
    var styles = window.getComputedStyle(track);
    var gap = parseInt(styles.columnGap || styles.gap || '24', 10) || 24;
    step = first.offsetWidth + gap;
  }
  recalcStep();

  function updateArrows(){
    var maxScroll = track.scrollWidth - track.clientWidth - 2;
    prev.disabled = track.scrollLeft <= 0;
    next.disabled = track.scrollLeft >= maxScroll;
    prev.style.opacity = prev.disabled ? 0.4 : 1;
    next.style.opacity = next.disabled ? 0.4 : 1;
    prev.style.pointerEvents = prev.disabled ? 'none' : 'auto';
    next.style.pointerEvents = next.disabled ? 'none' : 'auto';
  }

  function buildDots(){
    if(!dotsBox) return;
    dotsBox.innerHTML = '';
    var items = track.querySelectorAll('.product-item');
    items.forEach(function(_, i){
      var dot = document.createElement('span');
      dot.className = 'product-dot' + (i === 0 ? ' active' : '');
      dot.addEventListener('click', function(){
        var offset = i * step;
        track.scrollTo({left: offset, behavior: 'smooth'});
      });
      dotsBox.appendChild(dot);
    });
  }

  function updateDots(){
    if(!dotsBox) return;
    var dots = dotsBox.querySelectorAll('.product-dot');
    var index = Math.round(track.scrollLeft / step);
    dots.forEach(function(d, i){ d.classList.toggle('active', i === index); });
  }

  prev.addEventListener('click', function(){ track.scrollBy({left: -step, behavior: 'smooth'}); });
  next.addEventListener('click', function(){ track.scrollBy({left: step, behavior: 'smooth'}); });
  track.addEventListener('scroll', function(){ updateArrows(); updateDots(); }, {passive: true});
  window.addEventListener('resize', function(){
    recalcStep();
    updateArrows();
    buildDots();
    updateDots();
  });
  buildDots();
  updateArrows();
  updateDots();
})();

(function(){
  var sections = ['Services','Products','About','Contact'].map(function(id){
    return document.getElementById(id);
  });
  var links = Array.prototype.slice.call(document.querySelectorAll('.navbar-nav .nav-link'));
  if(sections.length === 0 || links.length === 0) return;
  function onScroll(){
    var scrollPos = window.scrollY + 120;
    var currentIndex = 0;
    sections.forEach(function(sec, idx){
      if(sec && sec.offsetTop <= scrollPos) currentIndex = idx;
    });
    links.forEach(function(a){ a.classList.remove('active'); });
    if(links[currentIndex]) links[currentIndex].classList.add('active');
  }
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();
})();


