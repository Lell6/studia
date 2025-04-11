const updateCells = document.getElementsByClassName("update");

Array.from(updateCells).forEach(cell => {
    cell.addEventListener('click', (event) => {
        var recordToUpdate;
        var clickedElement = event.target;

        if (clickedElement.tagName == 'TD') { recordToUpdate = clickedElement.parentElement.getElementsByClassName("record")[0]; }
        else { recordToUpdate = clickedElement.parentElement.parentElement.getElementsByClassName("record")[0]; }

        var recordUpdateForm = recordToUpdate.querySelector('form');
        var recordUpdateValue = recordToUpdate.querySelector('p');

        const allForms = document.querySelectorAll(".record form");
        allForms.forEach(form => {
            if (form !== recordUpdateForm) {
                form.style.display = 'none';
            }
        });

        const allRecordValues = document.querySelectorAll(".record p");
        allRecordValues.forEach(recordValue => {
            if (recordValue !== recordUpdateValue) {
                recordValue.style.display = 'inline';
            }
        });

        if (recordUpdateForm.style.display == 'none') {
            recordUpdateForm.style.display = 'inline';
            recordUpdateValue.style.display = 'none';
        } else {
            recordUpdateForm.style.display = 'none';
            recordUpdateValue.style.display = 'inline';
        }
    });
});