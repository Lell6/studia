const links = document.getElementsByClassName('linkButton');

Array.from(links).forEach(currentLink => {
    var icon = currentLink.querySelector('i');
    var description = currentLink.querySelector('p');

    currentLink.addEventListener('mouseover', event => {
        icon.style.display = 'none';
        description.style.display = "block";
    });

    currentLink.addEventListener('mouseout', event => {
        icon.style.display = 'block';
        description.style.display = "none";
    });
});