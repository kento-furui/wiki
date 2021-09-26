@foreach ($taxa as $t)
    <div style="padding: 0 1%">
        @if ($t->eol && !empty($t->eol->img))
        <img src="{{ $t->eol->img }}" height="20px">
        @else
        <input type="hidden" name="noimg" value="{{ $t->EOLid  }}" />
        @endif

        {{ $t->canonicalName }}

        @if ($t->eol && !empty($t->eol->jp))
        {{ $t->eol->jp }}
        @else
        <input type="hidden" name="nojp" value="{{ $t->EOLid }}" />
        @endif

        @if ($t->eol && !empty($t->eol->en))
        {{ $t->eol->en }}
        @else
        <input type="hidden" name="noen" value="{{ $t->EOLid }}" />
        @endif

        @if ($t->iucn)
        {!! $t->iucn->inline() !!}
        @elseif ($t->taxonRank == "species")
        <input type="hidden" name="nostatus" value="{{ $t->canonicalName }}" id="{{ $t->taxonID }}" />
        @endif

        @include('taxon._recurse', ['taxa' => $t->children])
    </div>
@endforeach