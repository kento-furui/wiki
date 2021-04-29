<table class="table table-striped">
    @foreach ($taxa as $taxon)
    <tr>
	<td>
		@if ($taxon->eol && !empty($taxon->eol->img))
		<img src="{{ $taxon->eol->img }}" width="98px">
                @else
                <input type="hidden" name="noimg" value="{{ $taxon->EOLid  }}" />
		@endif
        </td>
        <td><a href="/taxon/{{ $taxon->taxonID }}">{{ $taxon->taxonID}}</td>
        <td>{{ $taxon->EOLid }}</td>
        <td>{{ $taxon->canonicalName }}</td>
        <td>
            @if ($taxon->eol && !empty($taxon->eol->jp))
            {{ $taxon->eol->jp }}
            @else
            <input type="hidden" name="nojp" value="{{ $taxon->EOLid }}" />
            @endif
        </td>
        <td>
            @if ($taxon->eol && !empty($taxon->eol->en))
            {{ $taxon->eol->en }}
            @else
            <input type="hidden" name="noen" value="{{ $taxon->EOLid }}" />
            @endif
        </td>
        <td>{{ $taxon->taxonomicStatus }}</td>
        <td>{{ $taxon->taxonRank }}</td>
    </tr>
    @endforeach
</table>