var toggleButtons = document.querySelectorAll('td.dt-control h5');

toggleButtons.forEach(function (toggleButton) {
    toggleButton.addEventListener('click', function () {
        var tr = toggleButton.closest('tr');
        var nextRow = tr.nextElementSibling;

        let isVisible = false;
        while (nextRow && !nextRow.classList.contains('parent-row')) {
            if (nextRow.classList.contains('child-row')) {
                isVisible = nextRow.style.display === 'table-row';
                nextRow.style.display = isVisible ? 'none' : 'table-row';
            }
            nextRow = nextRow.nextElementSibling;
        }

        toggleButton.textContent = isVisible ? 'Pokaż' : 'Ukryj';
    });
});