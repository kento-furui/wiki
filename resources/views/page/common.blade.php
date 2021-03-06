<div class="row" id="tree">
    <div class="col-12" style="color: whitesmoke">
        @foreach (array_reverse($tree) as $t)
            @if ($app == 'ja')
                <a style="color: antiquewhite" href="/{{ $app }}/{{ $t->taxonID }}">{{ $t->eol && !empty($t->eol->jp) ? $t->eol->jp : $t->canonicalName }}</a>
                >
            @else
                <a style="color: antiquewhite" href="/{{ $app }}/{{ $t->taxonID }}">{{ $t->canonicalName }}</a>
                >
            @endif
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-1" id="thumbnail">
        <img src="{{ $taxon->image ? $taxon->image->eolThumbnailURL : '/img/noimage.png' }}" width="98px">
    </div>
    <div class="col-7" id="names" style="color: whitesmoke">
        {{ $taxon->taxonRank }}<br>
        <h4 style="margin-bottom: 0">
            {{ $taxon->scientificName }}
            {{ $taxon->eol ? $taxon->eol->jp : null }}
            <small>{{ $taxon->eol ? $taxon->eol->en : null }}</small>
            @if ($taxon->iucn)
                <span class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</span>
            @endif
        </h4>
    </div>
    <div class="col-4" id="nav">
        <a class="btn {{ $app == 'page' ? 'btn-primary' : 'btn-info' }}" href="/page/{{ $taxon->taxonID }}">Page</a>
        <a class="btn {{ $app == 'tree' ? 'btn-primary' : 'btn-info' }}" href="/tree/{{ $taxon->taxonID }}">Tree</a>
        <a class="btn {{ $app == 'media' ? 'btn-primary' : 'btn-info' }}"
            href="/media/{{ $taxon->taxonID }}">Media</a>
        <a class="btn {{ $app == 'taxon' ? 'btn-primary' : 'btn-info' }}"
            href="/taxon/{{ $taxon->taxonID }}">Taxon</a>
        <a class="btn {{ $app == 'ja' ? 'btn-primary' : 'btn-info' }}" href="/ja/{{ $taxon->taxonID }}">JPN</a>
        <a class="btn {{ $app == 'en' ? 'btn-primary' : 'btn-info' }}" href="/en/{{ $taxon->taxonID }}">ENG</a>
    </div>
</div>
<hr>
