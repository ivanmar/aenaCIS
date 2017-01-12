@extends('master')

@section('content')


<p><a class="btn btn-sm btn-primary" href="{!! URL::to('invoiceout/create') !!}">add new</a></p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>Račun</td>
            <td>Datum oddaje</td>
            <td>Rok plačila</td>
            <td>Podjetje</td>
            <td>Sklic</td>
            <td>Iznos NET</td>
            <td width="90">akcije</td>
        </tr>
    </thead>
    <tbody>
        @foreach($obj as $key => $value)
        <tr>
            <td>{!! $value->id !!}</td>
            <td>{!! $value->nrInvoice !!}</td>
            <td>{!! $value->dateIssue !!}</td>
            <td>{!! $value->dateDue !!}</td>
          @if (isset($value->company->name))
            <td>{!! $value->company->name  !!}</td>
          @else
            <td> končni kupec </td>
          @endif
            <td>{!! $value->nrRef !!}</td>
            <td>{!! $value->priceNet !!}</td>

            <td class="text-right">
                <a class="btn btn-xs btn-success" href="{!! URL::to('invoiceout/' . $value->id) !!}" target="_blank">S</a>
                <a class="btn btn-xs btn-info" href="{!! URL::to('invoiceout/' . $value->id . '/edit') !!}">E</a>
                <a class="btn btn-xs btn-danger" href="{!! URL::to('invoiceout/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    // CLEAR items from invoice page
    window.onload = function() {
        sessionStorage.clear();
    }
</script>
@stop
