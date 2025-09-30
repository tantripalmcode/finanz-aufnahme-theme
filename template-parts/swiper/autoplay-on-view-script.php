function updateProgress(timestamp) {
    if (!lastTime) lastTime = timestamp;
    const elapsed = timestamp - lastTime;
    
    if (isInView) {
        timeLeft -= elapsed;
        const progress = timeLeft / slideDuration;
        const dashOffset = 180 * progress;  // Ini yang diubah
        $progressBorder.css('stroke-dashoffset', Math.max(0, Math.min(180, dashOffset)));
        if (timeLeft <= 0) {
            timeLeft = slideDuration;
            if (swiper.isEnd) {
                swiper.slideTo(0); // Go to the first slide if it's the last slide
            } else {
                swiper.slideNext();
            }
        }
    }

    lastTime = timestamp;
    intervalId = requestAnimationFrame(updateProgress);
}

function startInterval() {
    if (!intervalId) {
        timeLeft = slideDuration;
        lastTime = null;
        intervalId = requestAnimationFrame(updateProgress);
    }
}

function stopInterval() {
    if (intervalId) {
        cancelAnimationFrame(intervalId);
        intervalId = null;
    }
    $progressBorder.css('stroke-dashoffset', 180);
}

function isElementInViewport(el) {
    // const rect = el.getBoundingClientRect();
    // return (
    //     rect.top >= 0 &&
    //     rect.left >= 0 &&
    //     rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
    //     rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    // );
    const rect = el.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    const windowWidth = window.innerWidth || document.documentElement.clientWidth;

    // Check if at least a part of the element is visible
    return (
        (rect.top <= windowHeight && rect.bottom >= 0) &&
        (rect.left >= 0 && rect.right <= windowWidth)
    );
}

function manageInterval() {
    isInView = isElementInViewport($slider[0]);
    if (isInView) {
        startInterval();
    } else {
        stopInterval();
    }
}

$(window).on('scroll resize', manageInterval);
manageInterval();

// Handle manual navigation
$($widget_id).find('.swiper-button-next, .swiper-button-prev').on('click', function() {
    timeLeft = slideDuration;
    $progressBorder.css('stroke-dashoffset', 180);
});