(function () {
    var serviceList = document.querySelector('[data-service-list]'),
        serviceStatTarget = document.querySelector('[data-service-stat-target]'),
        serviceBlurbTarget = document.querySelector('[data-service-blurb-target]'),
        services = document.querySelectorAll('[data-service]');

    if (serviceList == null || serviceStatTarget == null || serviceBlurbTarget == null || services.length == 0) {
        return;
    }


    var startingValue = 50,
        targetValue = 0,
        duration = 800; // Animation delay, in milliseconds.

    function easeInOutQuad(t) {
        return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
    }

    function updateCounter(progress) {
        var value = startingValue + (targetValue - startingValue) * progress;

        serviceStatTarget.innerText = Math.round(value) + '%';
    }

    function startStatPercentCounter() {
        const startTime = performance.now();

        function animate(timestamp) {
            var elapsed = timestamp - startTime,
                progress = Math.min(elapsed / duration, 1),
                easedProgress = easeInOutQuad(progress);

            updateCounter(easedProgress);

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    }

    services.forEach((item) => {
        item.addEventListener('mouseenter', (e) => {
            var serviceStat = e.target.getAttribute('data-service-stat'),
                serviceBlurb = e.target.getAttribute('data-service-blurb');

            targetValue = serviceStat;

            startStatPercentCounter();
            serviceBlurbTarget.innerText = serviceBlurb;
        });
    });
})();