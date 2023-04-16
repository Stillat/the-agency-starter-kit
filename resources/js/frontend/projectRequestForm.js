(function () {
    var containerForm = document.getElementById('projectRequestForm');

    if (containerForm == null) {
        return;
    }

    var budgetSlider = document.querySelector('[data-project-budget]'),
        budgetPreview = document.querySelector('[data-budget-preview]'),
        budgetStrokeSpans = document.querySelectorAll('[data-budget-stroke]'),
        projectTypeButtons = document.querySelectorAll('[data-project-type]'),
        projectTypeInput = document.querySelector('[name=project_type]'),
        projectBudgetInput = document.querySelector('[name=budget_range]');

    function updateProjectTypeButtons(curValue) {
        projectTypeButtons.forEach((btn) => {
            var btnValue = btn.getAttribute('data-value');

            if (btnValue == curValue) {
                if (!btn.classList.contains('bg-gray-50')) {
                    btn.classList.add('bg-gray-50');
                }

                if (!btn.classList.contains('shadow-subtle')) {
                    btn.classList.add('shadow-subtle');
                    btn.classList.remove('shadow-sm');
                }
            } else {
                btn.classList.remove('bg-gray-50');
                btn.classList.remove('shadow-subtle');

                if (!btn.classList.contains('shadow-sn')) {
                    btn.classList.add('shadow-sm');
                }
            }
        });
    }

    projectTypeButtons.forEach((el) => {
        el.addEventListener('click', () => {
            var curValue = el.getAttribute('data-value');

            projectTypeInput.value = curValue;
            updateProjectTypeButtons(curValue);
        });
    });

    projectTypeInput.addEventListener('input', () => {
        updateProjectTypeButtons(projectTypeInput.value);
    });

    function updateBudgetSlider(value) {
        var text = window._budgetRanges[value].label;

        budgetPreview.innerText = text;
        budgetStrokeSpans.forEach((el) => {
            var strokeIndex = el.getAttribute('data-budget-stroke');

            if (strokeIndex <= value) {
                if (!el.classList.contains('text-pink-500')) {
                    el.classList.add('text-pink-500');
                    el.classList.remove('text-slate-500');
                }
            } else {
                el.classList.remove('text-pink-500');

                if (!el.classList.contains('text-slate-500')) {
                    el.classList.add('text-slate-500');
                }
            }
        });
    }

    budgetSlider.addEventListener('input', () => {
        var value = budgetSlider.value - 1,
            selectValue = window._budgetRanges[value].value;

        projectBudgetInput.value = selectValue;
        updateBudgetSlider(value);
    });

    projectBudgetInput.addEventListener('input', () => {
        var selectedIndex = null;

        for (var i = 0; i < window._budgetRanges.length; i++) {
            var range = window._budgetRanges[i];

            if (range.value == projectBudgetInput.value) {
                selectedIndex = i;
                break;
            }
        }

        if (selectedIndex == null || selectedIndex < 0) {
            return;
        }

        budgetSlider.value = selectedIndex + 1;
        updateBudgetSlider(selectedIndex);
    });
})();
