//get_en();
//get_jp();
get_status();
//get_images();
//get_thumbnail();
//get_wikipedia();
//get_wikipedia_en();

$("[name=edit_jp]").change(function() {
    const value = $(this).val();
    const id = $(this).attr('id');
    //console.table(id, value);
    $.post('/api/eol/update/'+id, {jp:value}, function() {
        $("[name=edit_jp]").fadeOut().fadeIn();
    });
});

$("[name=edit_en]").change(function() {
    const value = $(this).val();
    const id = $(this).attr('id');
    //console.table(id, value);
    $.post('/api/eol/update/'+id, {en:value}, function() {
        $("[name=edit_en]").fadeOut().fadeIn();
    });
});

$("[name=edit_status]").change(function() {
    const value = $(this).val();
    const id = $(this).attr('id');
    //console.table(id, value);
    $.post('/api/iucn/store', {id:id, value:value}, function() {
        $("[name=edit_status]").fadeOut().fadeIn();
    });
});

function get_images_guest() {
    const id = $("#guest").val();
    if (id != undefined) {
        const url = "https://eol.org/api/pages/1.0/" + id + ".json";
        $.get(url, {details:true, images_per_page:30}, function(json) {
            //console.log(json);
            $.each(json.taxonConcept.dataObjects, function(index, data) {
                $("#images").append("<a href="+data.eolMediaURL+"><img height=180px src="+data.eolMediaURL+"></a>");
            });
        });
    }
}

function get_status() {
    const token = "25ef48b3629d17b58768363e36c5d7ce34130df6ca7bf81a52667ab63320471b";
    $("[name=nostatus]").each(function(index, element) {
        const id = element.id;
        const value = element.value;
        //console.table(id, value);
        const url = "https://apiv3.iucnredlist.org/api/v3/species/" + value + "?token=" + token;
        $.get(url, function (json) {
            //console.log(json);
            if (json.result == undefined) return;
            if (json.result[0] == undefined) return;
            if (json.result[0].category == undefined) return;
            $.post("/api/iucn/store", { id: id, value: json.result[0].category }, function () {
                console.log(json.result[0]);
                element.replaceWith(json.result[0].category);
            });
        });
    });
}

function get_en() {
    $('[name=noen]').each(function (index, element) {
        const id = element.value;
        if (id == "") return false;
        const url = "https://eol.org/api/pages/1.0/" + id + ".json?details=true&common_names=true";
        $.get(url, function(json) {
            if (json.taxonConcept.vernacularNames == undefined) return;
            $.each(json.taxonConcept.vernacularNames, function(i,v) {
                if (v.eol_preferred && v.language == 'en') {
                    $.post("/api/eol/update/" + id, {en : v.vernacularName}, function() {
                        console.log(v);
                        element.replaceWith(v.vernacularName);
                    });
                }
            });
        });
    });
}

function get_jp() {
    $('[name=nojp]').each(function (index, element) {
        const id = element.value;
        if (id == "") return false;
        const url = "https://eol.org/api/pages/1.0/" + id + ".json?details=true&common_names=true";
        $.get(url, function(json) {
            if (json.taxonConcept.vernacularNames == undefined) return;
            $.each(json.taxonConcept.vernacularNames, function(i,v) {
                if (v.eol_preferred && v.language == 'jp') {
                    $.post("/api/eol/update/" + id, {jp : v.vernacularName}, function() {
                        console.log(v);
                        element.replaceWith(v.vernacularName);
                    });
                }
            });
        });
    });
}

function get_wikipedia() {
    const obj = $("[name=title]");
    if (obj.val() == null) return false;
    const url = 
        "https://ja.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=" +
        obj.val() + "&formatversion=2&redirects&origin=*";
    $.get(url, function(json) {
        //console.log(json);
        if (json.parse == undefined) return false;
        let text = json.parse.text;
        text = text.replaceAll('href="/wiki/', 'target="_blank" href="//ja.wikipedia.org/wiki/');
        text = text.replaceAll('href="/w/', 'target="_blank" style="color:red" href="//ja.wikipedia.org/w/');
        $("#japanese").html(text);
    });
}

function get_wikipedia_en() {
    const obj = $("[name=canonical]");
    if (obj.val() == null) return false;
    const url =
        "https://en.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=" +
        obj.val() + "&formatversion=2&redirects&origin=*";
    $.get(url, function(json) {
        if (json.parse == undefined) return false;
        let text = json.parse.text;
        text = text.replaceAll('href="/wiki/', 'target="_blank" href="//en.wikipedia.org/wiki/');
        text = text.replaceAll('href="/w/', 'target="_blank" style="color:red" href="//en.wikipedia.org/w/');
        $("#english").html(text);
    });
}

async function get_images() {
    const obj = $("#EOLid");
    if (obj.val() == undefined) return false;
    const url = "https://eol.org/api/pages/1.0/" + obj.val() + ".json?details=true&images_per_page=30";
    $.get(url, function(json) {
        //console.log(json);
        if (json.taxonConcept.dataObjects == undefined) return;
        $.each(json.taxonConcept.dataObjects, function(key, val) {
            //console.log(val);
            let img = $('<img>')
                .css('height', '68px')
                .attr('title', val.title)
                .attr('src', val.eolThumbnailURL)
                .on('click', function() {
                    //console.log(val.eolThumbnailURL);
                    $.post("/api/eol/update/" + obj.val() , {img : val.eolThumbnailURL}, function() {
                        location.reload();
                    });
                });
            $("#images").append(img);
        });
    });
}

function get_thumbnail() {
    $("[name=noimg]").each(function(index, element) {
        const id = element.value;
        if (id == "") return false;
        const url = "https://eol.org/api/pages/1.0/" + id + ".json?details=true&images_per_page=1";
        $.get(url, function(json) {
            if (json.taxonConcept.dataObjects === undefined) return false;
            //console.log(json.taxonConcept.dataObjects[0].eolThumbnailURL);
            $.post("/api/eol/update/" + id, {img : json.taxonConcept.dataObjects[0].eolThumbnailURL}, function() {
                console.log(json.taxonConcept.dataObjects[0]);
                $(element).replaceWith('<img src="'+ json.taxonConcept.dataObjects[0].eolThumbnailURL +'">');
            });
        });
    });
}