@extends('master')

@section('content')


<p><a class="btn btn-sm btn-primary" href="{!! URL::to('reclamation/create') !!}">add new</a></p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>Kontakt</td>
            <td>Podjetje</td>
            <td>Datum</td>
            <td>Izdelek</td>
            <td>Status</td>
            <td>ID Račun</td>
            <td width="90">akcije</td>
        </tr>
    </thead>
    <tbody>
        @foreach($obj as $key => $value)
        <tr>
            <td>{!! $value->id !!}</td>
          @if (isset($value->contact->name))
            <td>{!! $value->contact->name  !!}</td>
          @else
            <td>  </td>
          @endif
          @if (isset($value->company->name))
            <td>{!! $value->company->name  !!}</td>
          @else
            <td>  </td>
          @endif
            <td>{!! $value->dateStart !!}</td>
            <td>{!! $value->product->name !!}</td>
            <td>{!! $value->status !!}</td>
            <td>{!! $value->idInvoiceOut !!}</td>
            <td class="text-right">
                <a class="btn btn-xs btn-info" href="{!! URL::to('reclamation/' . $value->id . '/edit') !!}">E</a>
                <a class="btn btn-xs btn-danger" href="{!! URL::to('reclamation/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop
