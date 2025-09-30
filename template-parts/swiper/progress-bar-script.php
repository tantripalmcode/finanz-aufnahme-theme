const $nextButton = $($widget_id).find(".swiper-button-next");
const $progressBorder = $nextButton.find(".progress-border");
let isInView = false;
let intervalId = null;
const slideDuration = 10000; // 10 seconds
let timeLeft = slideDuration;
let lastTime = null;