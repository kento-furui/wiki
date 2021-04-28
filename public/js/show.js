get_images();
get_wikipedia();

/*
const input = document.querySelector("[name=jp");
input.addEventListener("change", function () {
    const data = { id: input.id, jp: input.value };
    fetch("/api/eol/jp", {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json",
        },
    }).then((response) => {
        location.reload();
    });
});
*/

async function get_wikipedia() {
    const ja = document.querySelector("[name=jp]").value;
    if (ja == "") return false;
    const url =
        "https://ja.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=" +
        ja +
        "&formatversion=2&redirects&origin=*";
    const response = await fetch(url);
    const json = await response.json();
    document
        .getElementById("wikipedia")
        .insertAdjacentHTML("afterbegin", json.parse.text);
}

async function get_images() {
    const id = document.querySelector("#EOLid").value;
    const response = await eol_detail(id);
    //console.log(response);
    response.taxonConcept.dataObjects.forEach((element) => {
        //console.log(element);
        //let a = document.createElement('a');
        let img = document.createElement("img");
        //a.href = element.eolMediaURL;
        img.title = element.title;
        img.style.height = "68px";
        img.src = element.eolThumbnailURL;
        //a.appendChild(img);
        /*
        img.addEventListener("click", function () {
            //console.log(element.eolThumbnailURL);
            fetch("/api/eol/img", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: id,
                    img: element.eolThumbnailURL,
                }),
            }).then((response) => {
                location.reload();
            });
        });
        */
        document.querySelector("#images").appendChild(img);
    });
}

async function eol_detail(id) {
    const url =
        "https://eol.org/api/pages/1.0/" +
        id +
        ".json?details=true&images_per_page=30";
    const response = await fetch(url);
    return await response.json();
}
