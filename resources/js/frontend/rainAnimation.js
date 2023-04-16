(function () {
    var rainTrigger = document.getElementById('rain-trigger');

    if (rainTrigger == null || !rainTrigger.classList.contains('bg-pink-200')) {
        return;
    }
    var container = document.getElementById('rain-container');

    function spawnRain() {
        var rain = document.createElement('div');

        rain.classList.add('rain');
        rain.style.left = Math.random() * container.offsetWidth + 'px';

        container.appendChild(rain);

        setTimeout(() => {
            rain.remove();
        }, 3000);
    }

    setInterval(() => {
        spawnRain();
    }, 100);
})();