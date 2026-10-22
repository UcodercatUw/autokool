document.addEventListener('DOMContentLoaded', function () {
    console.log('Autokool JS loaded!');

    // Пример JS-эффекта: подтверждение перед действием или динамическая подсвечивание
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.cursor = 'pointer';
        });
    });
});