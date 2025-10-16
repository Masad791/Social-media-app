import VanillaTilt from 'vanilla-tilt';


//----- VanillaTilt for welcome page ----//
VanillaTilt.init(document.querySelectorAll('[data-tilt]'), {
    max: 5,
    speed: 400,
    perspective: 1000,
    glare: true,
    'max-glare': 0.5,
});

 const menuBtn = document.getElementById('menu-btn');
  const closeMenu = document.getElementById('close-menu');
  const menu = document.getElementById('menu');

  // Open menu
  menuBtn.addEventListener('click', () => {
    menu.classList.remove('translate-x-full');
  });

  // Close menu
  closeMenu.addEventListener('click', () => {
    menu.classList.add('translate-x-full');
  });

  // Optional: Close menu when clicking outside
  window.addEventListener('click', (e) => {
    if (!menu.contains(e.target) && !menuBtn.contains(e.target)) {
      menu.classList.add('translate-x-full');
    }
  });