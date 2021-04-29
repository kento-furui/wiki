get_en();
get_images();
get_thumbnail();
get_wikipedia();

async function get_en() {
    const noens = document.querySelectorAll("[name=noen]");
    noens.forEach(async (noen) => {
        //console.log(noen.value);
        const id = noen.value;
        if (id == "") return false;
        const json = await eol_names(id);
        //console.log(json);
        if (json.taxonConcept.vernacularNames == undefined) return false;
        //console.log(json.taxonConcept.vernacularNames);
        json.taxonConcept.vernacularNames.forEach(async (vn) => {
            if (vn.eol_preferred && vn.language == "en") {
                console.log(vn);
                const param = {
                    en : vn.vernacularName,
                }
                await eol_update(id, param);
                noen.replaceWith(vn.vernacularName);
                return false;
            }
        });
    });
}

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
    if (id == "") return false;
    const response = await eol_detail(id);
    //console.log(response);
    response.taxonConcept.dataObjects.forEach(async (element) => {
        //console.log(element);
        let img = document.createElement("img");
        img.title = element.title;
        img.style.height = "68px";
        img.src = element.eolThumbnailURL;
        img.addEventListener('click', async function() {
            console.log(img);
            const param = {
                img : img.src
            }
            await eol_update(id, param);
            location.reload();
        });
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

async function get_thumbnail() {
    const noimgs = document.querySelectorAll("[name=noimg]");

    noimgs.forEach(async (noimg) => {
        const id = noimg.value;
        //console.log(id);
        if (id == "") return false;
        const json = await eol_pages(id);
        if (json.taxonConcept.dataObjects === undefined) return false;
        //console.log(json);
        const param = {
            img : json.taxonConcept.dataObjects[0].eolThumbnailURL
        };
        const response = await eol_update(id, param);
        //console.log(response);
        const new_img = document.createElement('img');
        new_img.src = response.img;
        new_img.style.width = "91px";
        noimg.replaceWith(new_img);
    });
}

async function eol_update(id, param) {
    const response = await fetch("/api/eol/update/" + id, {
        mode: "cors",
        method: "POST",
        cache: "no-cache",
        redirect: "follow",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
        },
        referrerPolicy: "no-referrer",
        body: JSON.stringify(param),
    });
    return await response.json();
}

async function eol_pages(id) {
    const url = "https://eol.org/api/pages/1.0/" + id + ".json?details=true&images_per_page=1";
    const response = await fetch(url);
    return await response.json();
}

async function eol_names(id) {
    const url = "https://eol.org/api/pages/1.0/" + id + ".json?details=true&common_names=true";
    const response = await fetch(url);
    return await response.json();
}

