for (let thumb of document.querySelectorAll(".thumb")) {
    thumb.addEventListener('click', async () => {
        const identifier = thumb.id;
        const id = document.querySelector("#EOLid").value;
        await preferred(id, identifier);
        location.reload();
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

async function preferred(id, identifier) {
    await fetch('/api/image/preferred', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            EOLid: id,
            identifier: identifier,
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

const fetchMaps = async (element) => {
    if (element == null) return false;
    if (element.value == undefined) return false;
    const page = 1;
    const id = element.value;
    const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&maps_per_page=16&images_page=${page}`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.taxonConcept.dataObjects == undefined) return false;

        for (const dataObject of data.taxonConcept.dataObjects) {
            let p = document.createElement("p");
            let div = document.createElement("div");
            let img = document.createElement("img");

            div.className = "col-3";
            img.style.width = "100%";
            img.src = dataObject.eolMediaURL;
            p.innerHTML = dataObject.description;

            div.appendChild(img);
            div.appendChild(p);
            
            document.querySelector("#media").appendChild(div);
        }
    } catch (err) {
        console.error(err);
    }
};

const fetchImages = async (element) => {
    if (element == null) return false;
    if (element.value == undefined) return false;
    const page = 1;
    const id = element.value;
    const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=16&images_page=${page}`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.taxonConcept.dataObjects == undefined) return false;
        const container = document.querySelector("#images");
        for (const dataObject of data.taxonConcept.dataObjects) {
            let p = document.createElement("p");
            let div = document.createElement("div");
            let img = document.createElement("img");

            div.className = "col-3";
            img.style.width = "100%";
            img.src = dataObject.eolMediaURL;
            p.innerHTML = dataObject.description;
            
            div.appendChild(img);
            div.appendChild(p);
            
            document.querySelector("#media").appendChild(div);
        }
    } catch (err) {
        console.error(err);
    }
};

const fetchEn = async (row) => {
    try {
        const id = row.value;
        const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&common_names=true`;
        const response = await fetch(url);
        const data = await response.json();
        for (const vn of data.taxonConcept.vernacularNames) {
            if (vn.eol_preferred && vn.language == 'en') {
                update(id, { en: vn.vernacularName });
                row.replaceWith(vn.vernacularName);
                console.log(vn);
                break;
            }
        }
    } catch (err) {
        //console.error(err);
    }
};

const fetchJp = async (row) => {
    try {
        const id = row.value;
        const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&common_names=true`;
        const response = await fetch(url);
        const data = await response.json();
        for (const vn of data.taxonConcept.vernacularNames) {
            if (vn.eol_preferred && vn.language == 'jp') {
                update(id, { jp: vn.vernacularName });
                row.replaceWith(vn.vernacularName);
                console.log(vn);
                break;
            }
        }
    } catch (err) {
        //console.error(err);
    }
};

const fetchThumbnail = async (row) => {
    try {
        const id = row.value;
        const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=1`;
        const response = await fetch(url);
        const data = await response.json();
        if (data.taxonConcept.dataObjects === undefined) return false;
        update(id, { img: data.taxonConcept.dataObjects[0].eolThumbnailURL });
        let img = document.createElement("img");
        img.src = data.taxonConcept.dataObjects[0].eolThumbnailURL;
        row.replaceWith(img);
        console.log(data.taxonConcept.dataObjects[0]);
    } catch (err) {
        //console.error(err);
    }
};

const fetchStatus = async (row) => {
    const id = row.id;
    const value = row.value;
    const token = "25ef48b3629d17b58768363e36c5d7ce34130df6ca7bf81a52667ab63320471b";
    const url = `https://apiv3.iucnredlist.org/api/v3/species/${value}?token=${token}`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.result == undefined) return;
        if (data.result[0] == undefined) return;
        if (data.result[0].category == undefined) return;
        store(id, data.result[0].category);
        row.replaceWith(data.result[0].category);
        console.log(data.result[0]);
    } catch (err) {
        //console.error(err);
    }
};

const fetchWikipedia = async (row) => {
    if (row == undefined) return false;
    
    const lang = row.id;
    const value = row.value;
    const url =
        `https://${lang}.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=${value}&formatversion=2&redirects&origin=*`;
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
        document.querySelector("#wikipedia").innerHTML = text;
        //console.log(text);
    } catch (err) {
        console.error(err);
    }
};

fetchImages( document.querySelector('#EOLid') );
fetchWikipedia( document.querySelector('#ja') );
fetchWikipedia( document.querySelector('#en') );