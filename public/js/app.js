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

const fetchImages = async (element) => {
    if (element == null) return false;
    if (element.value == undefined) return false;
    const id = element.value;
    const url = `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=30`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.taxonConcept.dataObjects == undefined) return false;
        const container = document.querySelector("#images");
        for (const dataObject of data.taxonConcept.dataObjects) {
            let img = document.createElement("img");
            img.style.marginRight = '2px';
            img.id = dataObject.identifier;
            img.src = dataObject.eolThumbnailURL;
            img.addEventListener("click", async () => {
                preferred(id, dataObject.identifier);
                document.querySelector("#thumb").src = dataObject.eolThumbnailURL;
            });
            await fetch('/api/image/store', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    EOLid: id,
                    title: dataObject.title,
                    width: dataObject.width,
                    height: dataObject.height,
                    mediaURL: dataObject.mediaURL,
                    identifier: dataObject.identifier,
                    eolMediaURL: dataObject.eolMediaURL,
                    description : dataObject.description,
                    eolThumbnailURL: dataObject.eolThumbnailURL,
                }),
            });
            container.appendChild(img);
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
    if (row.value == "") return false;
    const value = row.value;
    const url = `https://ja.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=${value}&formatversion=2&redirects&origin=*`;
    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.parse == undefined) return false;
        let text = data.parse.text;
        text = text.replaceAll(
            'href="/wiki/',
            'target="_blank" href="//ja.wikipedia.org/wiki/'
        );
        text = text.replaceAll(
            'href="/w/',
            'target="_blank" style="color:red" href="//ja.wikipedia.org/w/'
        );
        document.querySelector("#japanese").innerHTML = text;
        //console.log(text);
    } catch (err) {
        console.error(err);
    }
};

const edit_jp = document.querySelector("[name=edit_jp]");
if (edit_jp != undefined) {
    edit_jp.addEventListener("change", async () => {
        update(edit_jp.id, {jp: edit_jp.value});
        edit_jp.style.backgroundColor = "lightblue";
    });
}

const edit_en = document.querySelector("[name=edit_en]");
if (edit_en != undefined) {
    edit_en.addEventListener("change", async () => {
        update(edit_en.id, {en: edit_en.value});
        edit_en.style.backgroundColor = "lightblue";
    });
}

const edit_status = document.querySelector("[name=edit_status]");
if (edit_status != undefined) {
    edit_status.addEventListener("change", async () => {
        store(edit_status.id, edit_status.value);
        edit_status.style.backgroundColor = "lightblue";
    });
}

const title = document.querySelector("[name=title]");
if (title != undefined) {
    fetchWikipedia(title);
}

const element = document.querySelector("#EOLid");
if (element != undefined) {
    fetchImages(element);
}

const nostatus = document.querySelectorAll("[name=nostatus]");
for (const row of nostatus) {
    fetchStatus(row);
}

const noen = document.querySelectorAll("[name=noen]");
for (const row of noen) {
    fetchEn(row);
}

const nojp = document.querySelectorAll("[name=nojp]");
for (const row of nojp) {
    fetchJp(row);
}

const noimg = document.querySelectorAll("[name=noimg]");
for (const row of noimg) {
    fetchThumbnail(row);
}
