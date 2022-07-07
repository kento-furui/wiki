async function update(id, body) {
    await fetch(`/api/eol/update/${id}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(body),
    });
}

async function saveIucn(elements) {
    for (let e of elements) {
        try {
            const id = e.id;
            const url = `/api/iucn/store/${id}`;
            const response = await fetch(url);
            const data = await response.json();
            console.log(data);
            e.replaceWith(data.status);
        } catch (err) {
            console.error(err);
        }
    }
}

async function saveJpn(elements) {
    for (let e of elements) {
        try {
            const id = e.id;
            const url = `/api/cname/jp/${id}`;
            const response = await fetch(url);
            const data = await response.json();
            console.log(data);
            e.replaceWith(data.jp);
        } catch (err) {
            console.error(err);
        }
    }
}

async function saveEng(elements) {
    for (let e of elements) {
        try {
            const id = e.id;
            const url = `/api/cname/en/${id}`;
            const response = await fetch(url);
            const data = await response.json();
            console.log(data);
            e.replaceWith(data.en);
        } catch (err) {
            console.error(err);
        }
    }
}

async function saveImg(elements) {
    for (let e of elements) {
        try {
            const id = e.id;
            const url = `/api/image/store/${id}`;
            const response = await fetch(url);
            const data = await response.json();
            console.log(data);
            if (data.eolThumbnailURL != undefined) {
                e.src = data.eolThumbnailURL;
            }
        } catch (err) {
            console.error(err);
        }
    }
}

let page = 1;

function next() {
    fetchImg(document.querySelector('#EOLid'), page++);
}

async function fetchImg(element) {
    if (element == null) return false;
    if (element.value == undefined) return false;
    const id = element.value;
    const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=75&images_page=${page}`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.taxonConcept.dataObjects == undefined) return false;
        for (const dataObject of data.taxonConcept.dataObjects) {
            //console.log(dataObject);
            let img = document.createElement("img");
            img.style.margin = "2px";
            img.className = "thumbnail";
            img.src = dataObject.eolThumbnailURL;
            img.id = dataObject.dataObjectVersionID;
            img.addEventListener("click", async function () {
                const url = `https://eol.org/api/data_objects/1.0/${dataObject.dataObjectVersionID}.json`;
                const response = await fetch(url);
                const data = await response.json();
                //console.log(data.taxon.dataObjects[0]);
                await fetch(`/api/image/update/${id}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data.taxon.dataObjects[0]),
                });
                location.reload();
            });
            document.querySelector("#images").appendChild(img);
        }
    } catch (err) {
        console.error(err);
    }
}

async function fetchSpec(row) {
    const value = row.value;
    const url = `https://species.wikimedia.org/w/api.php?action=parse&prop=text&page=${value}&formatversion=2&format=json&origin=*`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        console.log(data);
        document.querySelector("#wikispecies").innerHTML = data.parse.text;
    } catch (err) {
        console.error(err);
    }
}

async function fetchWiki(row) {
    if (row == undefined) return false;

    const lang = row.id;
    const value = row.value;
    const url = `https://${lang}.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=${value}&formatversion=2&redirects&origin=*`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.parse == undefined) return false;
        let text = data.parse.text;
        text = text.replaceAll(
            'href="/wiki/',
            `target="_blank" href="//${lang}.wikipedia.org/wiki/`
        );
        text = text.replaceAll(
            'href="/w/',
            `target="_blank" style="color:red" href="//${lang}.wikipedia.org/w/`
        );
        document.querySelector("#wikipedia_" + lang).innerHTML = text;
        //console.log(text);
    } catch (err) {
        console.error(err);
    }
}
