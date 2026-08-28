/* Client-side validation (server still validates) */
document.querySelectorAll('form[data-validate]').forEach(function(f){
  f.addEventListener('submit', function(e){
    let ok = true;
    f.querySelectorAll('[required]').forEach(function(el){
      if (!el.value || (el.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value))){
        ok = false;
        el.classList.add('is-invalid');
        setTimeout(()=> el.classList.remove('is-invalid'), 2500);
      }
    });
    // Date validation
    const ci = f.querySelector('[name="check_in"]');
    const co = f.querySelector('[name="check_out"]');
    if (ci && co && ci.value && co.value && new Date(co.value) <= new Date(ci.value)){
      ok = false;
      window.toast && window.toast('Check-out must be after check-in.', 'error');
    }
    // Password confirm
    const p1 = f.querySelector('[name="password"]');
    const p2 = f.querySelector('[name="password_confirm"]');
    if (p1 && p2 && p1.value !== p2.value){
      ok = false;
      window.toast && window.toast('Passwords do not match.', 'error');
    }
    if (!ok) e.preventDefault();
  });
});
