<style>
    table tr td {
        vertical-align: middle;
    }
</style>
<table class="table table-striped">
    @foreach ($taxa as $taxon)
    <tr>
        <td><img src="{{ $taxon->eol ? $taxon->eol->img : '' }}" width="98px"></td>
        <td><a href="/taxon/{{ $taxon->taxonID }}">{{ $taxon->taxonID}}</td>
        <td>{{ $taxon->EOLid }}</td>
        <td>{{ $taxon->canonicalName }}</td>
        <td>{{ $taxon->eol ? $taxon->eol->jp : "" }}</td>
        <td>{{ $taxon->taxonomicStatus }}</td>
        <td>{{ $taxon->taxonRank }}</td>
    </tr>
    @endforeach
</table>