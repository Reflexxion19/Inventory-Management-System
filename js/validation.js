// Second password input field pattern
const i1 = document.getElementById('password');
const i2 = document.getElementById('repeated_password');

i1.addEventListener('change', (ev) => {
  i2.setAttribute('pattern', ev.target.value);
});