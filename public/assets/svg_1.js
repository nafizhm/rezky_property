function initializeSVGControls(svgElement, svgContainer, resetButton) {
    let scale = 1;
    let translateX = 0, translateY = 0;
    let startX, startY, isDragging = false;
    const zoomSpeed = 0.1;

    svgElement.addEventListener('wheel', function (e) {
        e.preventDefault();
        const oldScale = scale;
        scale += (e.deltaY < 0 ? zoomSpeed : -zoomSpeed);
        scale = Math.max(1, scale);

        const containerRect = svgContainer.getBoundingClientRect();
        const centerX = e.clientX - containerRect.left;
        const centerY = e.clientY - containerRect.top;

        const deltaX = (centerX - translateX) * (1 - oldScale / scale);
        const deltaY = (centerY - translateY) * (1 - oldScale / scale);

        translateX -= deltaX;
        translateY -= deltaY;

        applyTransform();
    });

    svgElement.addEventListener('mousedown', function (e) {
        e.preventDefault();
        isDragging = true;
        const containerRect = svgContainer.getBoundingClientRect();
        startX = e.clientX - translateX;
        startY = e.clientY - translateY;
        svgElement.style.cursor = 'grabbing';
    });

    window.addEventListener('mousemove', function (e) {
        if (isDragging) {
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            applyTransform();
        }
    });

    window.addEventListener('mouseup', function () {
        isDragging = false;
        svgElement.style.cursor = 'grab';
    });

    resetButton.addEventListener('click', function () {
        scale = 1;
        translateX = 0;
        translateY = 0;
        applyTransform();
    });

    function applyTransform() {
        svgElement.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
    }
}

// Loop semua container & SVG secara otomatis
document.querySelectorAll('.svg-container').forEach(container => {
    const svgElement = container.querySelector('svg');
    const resetButton = container.querySelector('.reset-button');
    if (svgElement && resetButton) {
        initializeSVGControls(svgElement, container, resetButton);
    }
});
