document.addEventListener('DOMContentLoaded', function () {
    console.log('Autokool JS loaded!');

    // Находим все карточки курсов в каталоге
    const cards = document.querySelectorAll('.course-card');

    if (cards.length === 0) {
        return;
    }

    cards.forEach(card => {
        // Устанавливаем курсор pointer при наведении
        card.addEventListener('mouseenter', () => {
            card.style.cursor = 'pointer';
            card.style.transition = 'none';
        });

        // 3D-наклон при движении мыши
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Вычисляем угол поворота (12 — коэффициент чувствительности наклона)
            const rotateX = -((y - centerY) / 12);
            const rotateY = (x - centerX) / 12;

            // Применяем трансформацию с сохранением перспективы
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
        });

        // Плавный возврат в исходное положение, когда мышь уходит
        card.addEventListener('mouseleave', () => {
            card.style.transition = 'transform 0.5s ease-in-out';
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        });
    });
});