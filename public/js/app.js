get_en();
get_jp();
get_status();
get_images();
get_thumbnail();
get_wikipedia();
get_wikipedia_en();

/*
const images = document.querySelectorAll(".thumb");
images.forEach((image) => {
    image.addEventListener("click", async () => {
        const url = "/api/eol/image/" + image.id;
        //console.log(url);
        const response = await fetch(url);
        const json = await response.json();
        console.log(json);
        image.src = json.img;
    });
});
*/

const jp = document.querySelector("[name=edit_jp]");
jp.addEventListener("change", async () => {
    const id = jp.id;
    const param = { jp: jp.value };
    await eol_update(id, param);
    $(jp).fadeOut().fadeIn();
});

const en = document.querySelector("[name=edit_en]");
en.addEventListener("change", async () => {
    const id = en.id;
    const param = { en: en.value };
    await eol_update(id, param);
    $(en).fadeOut().fadeIn();
});

const st = document.querySelector("[name=edit_status]");
st.addEventListener("change", async () => {
    const id = st.id;
    const value = st.value;
    $.post("/api/iucn/store", { id: id, value: value }, function () {
        $(st).fadeOut().fadeIn();
    });
});

async function get_status() {
    const token = "25ef48b3629d17b58768363e36c5d7ce34130df6ca7bf81a52667ab63320471b";
    $("[name=nostatus]").each(function (index, element) {
        const id = element.id;
        const value = element.value;
        //console.table(id, value);
        const url = "https://apiv3.iucnredlist.org/api/v3/species/" + value + "?token=" + token;
        $.get(url, function (json) {
            //console.log(json);
            if (json.result[0].category != "") {
                $.post("/api/iucn/store", { id: id, value: json.result[0].category }, function () {
                    element.replaceWith(json.result[0].category);
                });
            }
        });
    });
}

async function get_en() {
    const noens = document.querySelectorAll("[name=noen]");
    noens.forEach(async (noen) => {
        //console.log(noen.value);
        const id = noen.value;
        if (id == "") return false;
        const json = await eol_names(id);
        //console.log(json);
        if (json.taxonConcept.vernacularNames == undefined) return;
        //console.log(json.taxonConcept.vernacularNames);
        json.taxonConcept.vernacularNames.forEach(async (vn) => {
            if (vn.eol_preferred && vn.language == "en") {
                //console.log(vn);
                const param = {
                    en: vn.vernacularName,
                };
                await eol_update(id, param);
                noen.replaceWith(vn.vernacularName);
                return false;
            }
        });
    });
}

async function get_jp() {
    const noens = document.querySelectorAll("[name=nojp]");
    noens.forEach(async (noen) => {
        const id = noen.value;
        if (id == "") return false;
        const json = await eol_names(id);
        if (json.taxonConcept.vernacularNames == undefined) return false;
        json.taxonConcept.vernacularNames.forEach(async (vn) => {
            if (vn.eol_preferred && vn.language == "jp") {
                //console.log(vn);
                const param = {
                    jp: vn.vernacularName,
                };
                await eol_update(id, param);
                noen.replaceWith(vn.vernacularName);
                return false;
            }
        });
    });
}

async function get_wikipedia() {
    const obj = document.querySelector("[name=title]");
    if (obj === null) return false;
    const url =
        "https://ja.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=" +
        obj.value +
        "&formatversion=2&redirects&origin=*";
    const response = await fetch(url);
    const json = await response.json();
    if (json.parse == undefined) return false;
    let text = json.parse.text;
    text = text.replaceAll(
        'href="/wiki/',
        'target="_blank" href="//ja.wikipedia.org/wiki/'
    );
    text = text.replaceAll(
        'href="/w/',
        'target="_blank" style="color:red" href="//ja.wikipedia.org/w/'
    );
    document.getElementById("japanese").insertAdjacentHTML("afterbegin", text);
}

async function get_wikipedia_en() {
    const obj = document.querySelector("[name=canonical]");
    if (obj === null) return false;
    const url =
        "https://en.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=" +
        obj.value +
        "&formatversion=2&redirects&origin=*";
    const response = await fetch(url);
    const json = await response.json();
    if (json.parse == undefined) return false;
    let text = json.parse.text;
    text = text.replaceAll(
        'href="/wiki/',
        'target="_blank" href="//en.wikipedia.org/wiki/'
    );
    text = text.replaceAll(
        'href="/w/',
        'target="_blank" style="color:red" href="//en.wikipedia.org/w/'
    );
    document.getElementById("english").insertAdjacentHTML("afterbegin", text);
}

async function get_images() {
    const obj = document.querySelector("#EOLid");
    if (obj == undefined) return false;
    const id = obj.value;
    const response = await eol_detail(id);
    //console.log(response);
    response.taxonConcept.dataObjects.forEach(async (element) => {
        //console.log(element);
        let img = document.createElement("img");
        img.title = element.title;
        img.style.height = "68px";
        img.src = element.eolThumbnailURL;
        img.addEventListener("click", async function () {
            //console.log(img);
            const param = {
                img: img.src,
            };
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
        if (id == "") return false;
        const json = await eol_pages(id);
        if (json.taxonConcept.dataObjects === undefined) return false;
        const param = {
            img: json.taxonConcept.dataObjects[0].eolThumbnailURL,
        };
        const response = await eol_update(id, param);
        const new_img = document.createElement("img");
        new_img.src = response.img;
        //new_img.style.width = "91px";
        noimg.replaceWith(new_img);
        console.log(json.taxonConcept.dataObjects[0]);
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
    const url =
        "https://eol.org/api/pages/1.0/" +
        id +
        ".json?details=true&images_per_page=1";
    const response = await fetch(url);
    return await response.json();
}

async function eol_names(id) {
    const url =
        "https://eol.org/api/pages/1.0/" +
        id +
        ".json?details=true&common_names=true";
    const response = await fetch(url);
    return await response.json();
}
