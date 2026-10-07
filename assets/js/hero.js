// MoodBuddy - homepage hero preview: cycles through example moods
(function () {
    var chips = document.querySelectorAll('#heroMoodChips [data-mood]');
    var nameEl = document.getElementById('heroMoodName');
    var barEl = document.getElementById('heroMoodBar');
    var scoreEl = document.getElementById('heroMoodScore');
    if (!chips.length || !nameEl || !barEl || !scoreEl) return;

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var index = 1; // "Calm" is shown first

    function show(i) {
        chips.forEach(function (chip) { chip.classList.remove('is-active'); });
        var chip = chips[i];
        chip.classList.add('is-active');
        nameEl.style.opacity = 0;
        setTimeout(function () {
            nameEl.textContent = chip.getAttribute('data-mood');
            nameEl.style.opacity = 1;
        }, 200);
        scoreEl.textContent = chip.getAttribute('data-score');
        barEl.style.width = chip.getAttribute('data-score') + '%';
    }

    if (!reduceMotion) {
        setInterval(function () {
            index = (index + 1) % chips.length;
            show(index);
        }, 2800);
    }
})();
