<div class="row" id="tree">
    <div class="col-12">
        @foreach (array_reverse($tree) as $t)
        <a style="color: antiquewhite" href="/{{ $app }}/{{ $t->taxonID }}">{{ $t->canonicalName }}</a> >
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-1" id="thumbnail">
        <img src="{{ $taxon->image ? $taxon->image->eolThumbnailURL : '/img/noimage.png' }}" width="100%">
    </div>
    <div class="col-5" id="names">
        <h4 style="color: whitesmoke; margin-bottom: 0">{{ $taxon->scientificName }}</h4>
        <h5 style="color: whitesmoke">{{ $taxon->eol ? $taxon->eol->jp : null }}</h5>
    </div>
    <div class="col-6" id="nav">
        <a class="btn {{ $app == 'page'  ? 'btn-primary' : 'btn-info' }}" href="/page/{{ $taxon->taxonID }}">Page</a>
        <a class="btn {{ $app == 'tree'  ? 'btn-primary' : 'btn-info' }}" href="/tree/{{ $taxon->taxonID }}">Tree</a>
        <a class="btn {{ $app == 'media' ? 'btn-primary' : 'btn-info' }}" href="/media/{{ $taxon->taxonID }}">Media</a>
        <a class="btn {{ $app == 'ja'    ? 'btn-primary' : 'btn-info' }}" href="/ja/{{ $taxon->taxonID }}">JPN</a>
        <a class="btn {{ $app == 'en'    ? 'btn-primary' : 'btn-info' }}" href="/en/{{ $taxon->taxonID }}">ENG</a>
    </div>
</div>