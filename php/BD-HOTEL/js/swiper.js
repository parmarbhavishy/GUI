/* Swiper sliders */
document.addEventListener('DOMContentLoaded', function(){
  if (typeof Swiper === 'undefined') return;

  if (document.querySelector('.hero-slider')){
    new Swiper('.hero-slider', {
      loop:true, effect:'fade',
      autoplay:{ delay:6000, disableOnInteraction:false },
      pagination:{ el:'.hero-pagination', clickable:true },
      speed:1400,
    });
  }

  if (document.querySelector('.reviews-slider')){
    new Swiper('.reviews-slider', {
      slidesPerView:1, spaceBetween:30, loop:true,
      autoplay:{ delay:5000, disableOnInteraction:false },
      pagination:{ el:'.reviews-pagination', clickable:true },
      breakpoints:{ 768:{ slidesPerView:2 }, 1200:{ slidesPerView:3 } }
    });
  }

  if (document.querySelector('.rooms-slider')){
    new Swiper('.rooms-slider', {
      slidesPerView:1.1, spaceBetween:16,
      breakpoints:{ 768:{ slidesPerView:2.1 }, 1200:{ slidesPerView:3.1 } }
    });
  }
});
