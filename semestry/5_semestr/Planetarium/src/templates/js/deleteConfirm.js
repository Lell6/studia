const deleteUserButton = document.getElementById("deleteUser");
const cancelDeletButton = document.getElementById("deleteCancel");
const deleteForm = document.getElementById("deleteUserForm");

deleteUserButton.addEventListener('click', event => {
    if (deleteForm.style.display == 'inline') {
        deleteForm.style.display = 'none';
    }
    else {
        deleteForm.style.display = 'inline';
    }
});

cancelDeletButton.addEventListener('click', () => {
    deleteForm.style.display = 'none';
});