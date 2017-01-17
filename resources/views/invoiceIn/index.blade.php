@extends('master')

@section('content')


<p><a class="btn btn-sm btn-primary" href="{!! URL::to('invoicein/create') !!}">add new</a></p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>Račun</td>
            <td>Datum računa</td>
            <td>Dobavitelj</td>
            <td>Marketplace</td>
            <td>MP User</td>
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
            <td>{!! $value->company->name  !!}</td>
            <td>{!! $value->marketplace !!}</td>
            <td>{!! $value->marketplaceUser !!}</td>
            <td>{!! $value->priceNet !!}</td>

            <td class="text-right">
                <a class="btn btn-xs btn-success" href="{!! URL::to('invoicein/' . $value->id) !!}" target="_blank">S</a>
                <a class="btn btn-xs btn-info" href="{!! URL::to('invoicein/' . $value->id . '/edit') !!}">E</a>
                <a class="btn btn-xs btn-danger" href="{!! URL::to('invoicein/' . $value->id . '/destroy') !!}"
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
