function next() {
    page++;
    document.querySelectorAll("#images img").forEach( e => e.remove() );
    fetchImages( document.querySelector('#EOLid') );
}

async function fetchImg(element) {
    try {
        const id = element.id;
        const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=1`;
        const response = await fetch(url);
        const data = await response.json();
        if (data.taxonConcept.dataObjects === undefined) return false;
        image(id, data.taxonConcept.dataObjects[0]);
        element.src = data.taxonConcept.dataObjects[0].eolThumbnailURL;
        console.log(data.taxonConcept.dataObjects[0]);
    } catch (err) {
        //console.error(err);
    }
};

async function fetchIucn(element) {
    const id = element.id;
    const value = element.value;
    const token = "25ef48b3629d17b58768363e36c5d7ce34130df6ca7bf81a52667ab63320471b";
    const url = `https://apiv3.iucnredlist.org/api/v3/species/${value}?token=${token}`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.result == undefined) return;
        if (data.result[0] == undefined) return;
        if (data.result[0].category == undefined) return;
        store(id, data.result[0].category);
        element.replaceWith(data.result[0].category);
        console.log(data.result[0]);    
    } catch (err) {
        //console.error(err);
    }
};

async function image(id, body) {
    await fetch(`/api/image/store`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: id,
            title: body.title,
            width: body.width,
            height: body.height,
            mediaURL: body.mediaURL,
            identifier: body.identifier,
            description: body.description,
            eolMediaURL: body.eolMediaURL,
            eolThumbnailURL: body.eolThumbnailURL,
            dataObjectVersionID: body.dataObjectVersionID,
        }),
    });
}

async function store(id, value) {
    await fetch(`/api/iucn/store`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: id,
            value: value,
        }),
    });
}

async function iucn(id, value) {
    await fetch(`/api/iucn/update/${id}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            value: value,
        }),
    });
}

async function update(id, body) {
    await fetch(`/api/eol/update/${id}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(body),
    });
}

async function fetchCname(element, lang) {
    const id = element.id;
    const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&common_names=true`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        for (const vn of data.taxonConcept.vernacularNames) {
            if (vn.eol_preferred && vn.language == lang) {
                const body = { [lang] : vn.vernacularName } 
                update(id, body);
                element.replaceWith(vn.vernacularName);
                console.log(vn);
                break;
            }
        }
    } catch (err) {
        console.error(err);
    }
}

async function fetchImages(element) {
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
            img.className = "thumb";
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
                //document.querySelector("#preferred").src = data.taxon.dataObjects[0].eolMediaURL;
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
        if (data.parse == undefined) return false;
        document.querySelector("#wikispecies").innerHTML = data.parse.text;
        //console.log(data);
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
