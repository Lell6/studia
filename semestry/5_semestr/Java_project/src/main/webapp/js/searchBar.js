const searchbar = document.getElementById("searchbar");
const searchValueInput = document.getElementById("searchValue");
const searchResult = document.getElementById("searchResult");

async function sendSearchRequest(searchValue) {
    const data = await fetch("http://localhost:8080/Lista9_10/search?searchValue=" + searchValue);
    const obj =  await data.json();
    
    obj.slice(0,10).forEach(result => {
        var link = document.createElement('a');
        link.classList.add("searchLink");
        link.href = "http://localhost:8080/Lista9_10/post?id=" + result.id;
        link.innerText = result.question;
        searchResult.append(link);
    });
}

searchValueInput.addEventListener('input', event => {
    var searchValue = searchValueInput.value; 

    if (searchValue.length > 1) {
        searchResult.innerHTML = "";
        sendSearchRequest(searchValue);
    }
    else {
        searchResult.innerHTML = "";
    }
});