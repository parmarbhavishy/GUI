/* BD Hotel main script */
(function(){
  // Page loader
  window.addEventListener('load', function(){
    const l = document.getElementById('page-loader');
    if (l) setTimeout(()=> l.classList.add('hide'), 250);
    if (window.AOS) AOS.init({ duration:800, once:true, offset:60 });
  });

  // Sticky navbar
  const nav = document.getElementById('mainNav');
  const onScroll = () => {
    if (!nav) return;
    if (window.scrollY > 30) nav.classList.add('scrolled'); else nav.classList.remove('scrolled');
    const p = document.getElementById('scroll-progress');
    if (p){
      const h = document.documentElement;
      const scrolled = (h.scrollTop || document.body.scrollTop);
      const max = (h.scrollHeight - h.clientHeight) || 1;
      p.style.width = (scrolled / max * 100) + '%';
    }
    const btn = document.getElementById('backToTop');
    if (btn){ btn.classList.toggle('show', window.scrollY > 500); }
  };
  document.addEventListener('scroll', onScroll, { passive:true });
  onScroll();

  // Back to top
  const btn = document.getElementById('backToTop');
  if (btn) btn.addEventListener('click', () => window.scrollTo({ top:0, behavior:'smooth' }));

  // Animated counters
  document.querySelectorAll('[data-counter]').forEach(el => {
    const target = parseFloat(el.getAttribute('data-counter')) || 0;
    let cur = 0;
    const io = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (en.isIntersecting){
          const step = target / 60;
          const t = setInterval(() => {
            cur += step;
            if (cur >= target){ cur = target; clearInterval(t); }
            el.textContent = Number.isInteger(target) ? Math.round(cur) : cur.toFixed(1);
          }, 20);
          io.disconnect();
        }
      });
    }, { threshold:.4 });
    io.observe(el);
  });

  // Typing effect
  document.querySelectorAll('[data-typing]').forEach(el => {
    const text = el.getAttribute('data-typing');
    let i = 0;
    const write = () => {
      if (i <= text.length){ el.textContent = text.slice(0, i++); setTimeout(write, 80); }
    };
    write();
  });

  // Countdown timer
  document.querySelectorAll('[data-countdown-to]').forEach(el => {
    const target = new Date(el.getAttribute('data-countdown-to')).getTime();
    const tick = () => {
      const t = target - Date.now();
      if (t < 0){ el.textContent = 'Offer ended'; return; }
      const d = Math.floor(t/86400000), h = Math.floor(t/3600000)%24, m = Math.floor(t/60000)%60, s = Math.floor(t/1000)%60;
      el.textContent = `${d}d ${h}h ${m}m ${s}s`;
      setTimeout(tick, 1000);
    };
    tick();
  });

  // Toast helper
  window.toast = function(msg, kind){
    const t = document.createElement('div');
    t.className = 'toast align-items-center border-0 show position-fixed';
    t.style.cssText = 'top:100px;right:24px;z-index:2000;background:'+(kind==='error'?'#dc3545':'#212529')+';color:#fff;min-width:260px;';
    t.innerHTML = '<div class="d-flex"><div class="toast-body">'+msg+'</div></div>';
    document.body.appendChild(t);
    setTimeout(()=> t.remove(), 4200);
  };

  // Gallery filter + lightbox
  document.querySelectorAll('.gallery-filter-btn').forEach(b => {
    b.addEventListener('click', function(){
      document.querySelectorAll('.gallery-filter-btn').forEach(x => x.classList.remove('active'));
      this.classList.add('active');
      const f = this.getAttribute('data-filter');
      document.querySelectorAll('.gallery-item').forEach(g => {
        g.style.display = (f === 'All' || g.getAttribute('data-cat') === f) ? '' : 'none';
      });
    });
  });
  document.querySelectorAll('.gallery-item').forEach(g => {
    g.addEventListener('click', function(e){
      e.preventDefault();
      const src = this.getAttribute('data-src') || this.querySelector('img').src;
      const box = document.createElement('div');
      box.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:2000;display:flex;align-items:center;justify-content:center;padding:1.5rem;';
      box.innerHTML='<img src="'+src+'" style="max-height:90vh;max-width:90vw;object-fit:contain;"><button style="position:absolute;top:1.5rem;right:1.5rem;background:none;border:none;color:#fff;font-size:2rem;">&times;</button>';
      box.addEventListener('click', () => box.remove());
      document.body.appendChild(box);
    });
  });

  // Live room search
  const rs = document.getElementById('roomsSearch');
  if (rs) rs.addEventListener('input', function(){
    const q = this.value.toLowerCase();
    document.querySelectorAll('.room-card-wrap').forEach(w => {
      const n = w.getAttribute('data-name').toLowerCase();
      w.style.display = n.includes(q) ? '' : 'none';
    });
  });

  // Total-price calculator on booking form
  const bf = document.getElementById('bookingForm');
  if (bf){
    const price = parseFloat(bf.getAttribute('data-price') || '0');
    const ci = bf.querySelector('[name="check_in"]');
    const co = bf.querySelector('[name="check_out"]');
    const out = document.getElementById('bookingTotal');
    const nightsOut = document.getElementById('bookingNights');
    const calc = () => {
      if (!ci.value || !co.value) return;
      const d1 = new Date(ci.value), d2 = new Date(co.value);
      const n = Math.max(1, Math.round((d2 - d1) / 86400000));
      if (out) out.textContent = '$' + (price * n).toFixed(2);
      if (nightsOut) nightsOut.textContent = n + ' night' + (n===1?'':'s');
    };
    ci && ci.addEventListener('change', calc);
    co && co.addEventListener('change', calc);
    calc();
  }
})();
