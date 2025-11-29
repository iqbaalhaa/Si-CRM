const body = document.body;
let theme = localStorage.getItem('theme');

if (!theme) {
  theme = 'dark';
  localStorage.setItem('theme', theme);
}

document.documentElement.setAttribute('data-bs-theme', theme);
