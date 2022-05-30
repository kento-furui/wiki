const fetchCommonName = async (row) => {
    try {
        const id = row.value;
        const response = await fetch(
            `https://eol.org/api/pages/1.0/${id}.json?details=true&common_names=true`
        );
        const data = await response.json();
        for (const vn of data.taxonConcept.vernacularNames) {
            if (vn.eol_preferred && vn.language == "en") {
                await fetch(`/api/eol/update/${id}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ en: vn.vernacularName }),
                });
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
        const response = await fetch(
            `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=1`
        );
        const data = await response.json();
        if (data.taxonConcept.dataObjects === undefined) return false;
        await fetch(`/api/eol/update/${id}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                img: data.taxonConcept.dataObjects[0].eolThumbnailURL,
            })
        });
        let img = document.createElement('img');
        img.src = data.taxonConcept.dataObjects[0].eolThumbnailURL;
        row.replaceWith(img);
        console.log(data.taxonConcept.dataObjects[0]);
    } catch (err) {
        //console.error(err);
    }
};

const noen = document.querySelectorAll("[name=noen]");
for (const row of noen) {
    fetchCommonName(row);
}

const noimg = document.querySelectorAll("[name=noimg]");
for (const row of noimg) {
    fetchThumbnail(row);
}
