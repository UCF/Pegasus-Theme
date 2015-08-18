/* Detect touch-enabled browsers. (Modernizr check) */
function isTouchDevice() {
    return ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch;
}

/* Mobile devices have a hard time with the animated svg; try to kill it here */
if (isTouchDevice()) {
    $('#travel-time-interactive').hide();
    $('#travel-time-img').show();
}
