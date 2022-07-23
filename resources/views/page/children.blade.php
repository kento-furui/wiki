@if (count($taxon->children) > 0)
<h2>Children <small style="font-size: 80%">{{ count($taxon->children) }}</small></h2>
@endif
<div class="row" id="children">
    @foreach ($taxon->children as $child)
    <div class="col-3 child">
        @if ($child->iucn2)
        <span class="{{ $child->iucn2->category }}">{{ $child->iucn2->category }}</span>
        @endif
        <a href="/{{ $app }}/{{ $child->taxonID }}">
            @if ($child->number && $child->number->json)
            @foreach (json_decode($child->number->json) as $key => $val)
            @if ($key == 'species')
            <div class="json">{{ number_format($val) }} {{ $key }}</div>
            @endif
            @endforeach
            @endif
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