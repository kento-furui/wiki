<div class="row" id="tree">
    <div class="col-12">
        @foreach (array_reverse($tree) as $t)
        <a style="color: antiquewhite" href="/{{ $app }}/{{ $t->taxonID }}">{{ $t->canonicalName }}</a> >
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-1" id="thumbnail">
        <img src="{{ $taxon->image ? $taxon->image->eolThumbnailURL : '/img/noimage.png' }}" width="98px">
    </div>
    <div class="col-7" id="names">
        {{ $taxon->taxonRank }}<br>
        <h4 style="color: whitesmoke; margin-bottom: 0">
            {{ $taxon->scientificName }}
            {{ $taxon->eol ? $taxon->eol->jp : null }}
            @if ($taxon->iucn)
            <span class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</span>
            @endif
        </h4>
    </div>
    <div class="col-4" id="nav">
        <a class="btn {{ $app == 'page'  ? 'btn-primary' : 'btn-info' }}" href="/page/{{ $taxon->taxonID }}">Page</a>
        <a class="btn {{ $app == 'tree'  ? 'btn-primary' : 'btn-info' }}" href="/tree/{{ $taxon->taxonID }}">Tree</a>
        <a class="btn {{ $app == 'media' ? 'btn-primary' : 'btn-info' }}" href="/media/{{ $taxon->taxonID }}">Media</a>
        <a class="btn {{ $app == 'map'   ? 'btn-primary' : 'btn-info' }}" href="/map/{{ $taxon->taxonID }}">Map</a>
        <a class="btn {{ $app == 'ja'    ? 'btn-primary' : 'btn-info' }}" href="/ja/{{ $taxon->taxonID }}">JPN</a>
        <a class="btn {{ $app == 'en'    ? 'btn-primary' : 'btn-info' }}" href="/en/{{ $taxon->taxonID }}">ENG</a>
    </div>
</div>
<hr>