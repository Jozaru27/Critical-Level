document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.stats')) {
      AOS.init({
        duration: 800,
        easing: 'ease',
        offset: 200,
        delay: 100,
        once: true
      });
    }
  });
  