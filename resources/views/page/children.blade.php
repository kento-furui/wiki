<h2>Children <small style="font-size: 80%">{{ count($taxon->children) }}</small></h2>
<div class="row" id="children">
    @foreach ($taxon->children as $child)
        <div class="col-3 child">
            @if ($child->iucn)
                <span class="{{ $child->iucn->status }}">{{ $child->iucn->status }}</span>
            @endif
            <a href="/{{ $app }}/{{ $child->taxonID }}">
                <img src="{{ $child->image ? $child->image->eolMediaURL : '/img/noimage.png' }}" width="100%"
                    height="200px">
                <div class="overlay">
                    <h5>{{ $child->canonicalName }}</h5>
                    <h5>{{ $child->eol ? $child->eol->jp : null }}</h5>
                </div>
            </a>
        </div>
    @endforeach
</div>