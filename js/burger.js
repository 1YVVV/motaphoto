document.querySelector('.menu-burger').addEventListener('click', function() {
    document.querySelector('.menu-lateral').classList.toggle('actif');
    document.querySelector('.menu-burger').classList.toggle('actif'); // Pour la croix
});