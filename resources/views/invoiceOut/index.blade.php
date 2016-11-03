@extends('master')

@section('title')
@parent
:: Home
@stop

@section('content')


<p><a class="btn btn-sm btn-primary" href="{!! URL::to('bill/create') !!}">add new</a></p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>Ponudba</td>
            <td>Stranka</td>
            <td>Opis</td>
            <td width="90">akcije</td>
        </tr>
    </thead>
    <tbody>
        @foreach($bill as $key => $value)
        <tr>
            <td>{!! $value->id !!}</td>
            <td>{!! $value->sBill !!}</td>
            <td>{!! $value->customer->name !!}</td>
            <td>{!! $value->desc !!}</td>

            <td class="text-right">
                <a class="btn btn-xs btn-success" href="{!! URL::to('bill/' . $value->id) !!}" target="_blank">S</a>
                <a class="btn btn-xs btn-info" href="{!! URL::to('bill/' . $value->id . '/edit') !!}">E</a>
                <a class="btn btn-xs btn-danger" href="{!! URL::to('bill/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
