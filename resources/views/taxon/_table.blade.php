<div class="table-responsive">
    <table class="table table-hover" id="taxa">
        @foreach ($taxa as $taxon)
        <tr onclick="location.href='/taxon/{{ $taxon->taxonID }}'">
            <td>
                @if ($taxon->eol && !empty($taxon->eol->img))
                <img src="{{ $taxon->eol->img }}" id="{{ $taxon->EOLid }}" class="thumb">
                @else
                <input type="hidden" name="noimg" value="{{ $taxon->EOLid }}" />
                @endif
            </td>
            <td><a href="/taxon/{{ $taxon->taxonID }}">{{ $taxon->canonicalName }}</a><br>
                {!! $taxon->number ? $taxon->number->nodes() : null !!}
            </td>
            <td>
                @if ($taxon->eol && !empty($taxon->eol->jp))
                {{ $taxon->eol->jp }}
                @else
                <input type="hidden" name="nojp" value="{{ $taxon->EOLid }}" />
                @endif
                <br>
                {!! $taxon->number ? $taxon->number->names() : null !!}
            </td>
            <td>
                @if ($taxon->eol && !empty($taxon->eol->en))
                {{ $taxon->eol->en }}
                @else
                <input type="hidden" name="noen" value="{{ $taxon->EOLid }}" />
                @endif
            </td>
            <td>{{ $taxon->taxonomicStatus }}</td>
            <td><div class='number {{ $taxon->taxonRank }}'>{{ $taxon->taxonRank }}</div></td>
            <td>
                @if ($taxon->iucn)
                <div class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</div>
                @elseif ($taxon->taxonRank == 'species')
                <input type="hidden" name="nostatus" value="{{ $taxon->canonicalName }}" id="{{ $taxon->taxonID }}" />
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>