@extends('master')

@section('content')


<p><a class="btn btn-sm btn-primary" href="{!! URL::to('storno/create') !!}">dodaj storno</a></p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>Datum</td>
            <td>Stranka</td>
            <td>št storno</td>
            <td>št računa</td>
            <td>opomba</td>
            <td width="120">akcije</td>
        </tr>
    </thead>
    <tbody>
        @foreach($obj as $key => $value)
        <tr>
            <td>{!! $value->id !!}</td>
            <td>{!! $value->dateIssue !!}</td>
            
          @if (isset($value->invoiceout->contact->name))
            <td>{!! $value->invoiceout->contact->name  !!}</td>
          @else
            <td>{!! $value->invoiceout->company->name  !!}</td>
          @endif
          
            <td>{!! $value->nrStorno !!}</td>
            <td>{!! $value->invoiceout->nrInvoice !!}</td>
            <td>{!! $value->desc !!}</td>
            <td class="text-right">
                <a class="btn btn-xs btn-success" href="{!! URL::to('storno/' . $value->id) !!}" target="_blank">S</a>
                <a class="btn btn-xs btn-info" href="{!! URL::to('storno/' . $value->id . '/edit') !!}">E</a>
                <a class="btn btn-xs btn-danger" href="{!! URL::to('storno/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop
