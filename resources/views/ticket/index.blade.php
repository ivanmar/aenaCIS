@extends('master')

@section('content')
{!! Form::open(array('route'=>'ticket.index','method' => 'get','class'=>'form-horizontal')) !!}
<div class="row">
    <div class="col-md-2  col-md-offset-7 ">{!! Form::select('idContact', $contact, $idContact, array('class' => 'form-control')) !!} </div>
    <div class="col-md-2">{!! Form::select('year', $years, $year, array('class' => 'form-control')) !!} </div>
    <div class="col-md-1">{!! Form::submit('Filter', array('class' => 'btn btn-success')) !!}</div>
</div>
{!! Form::close() !!}
<p><a class="btn btn-sm btn-primary" href="{!! URL::to('ticket/create') !!}">add new</a></p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>oprema</td>
            <td>stranka</td>
            <td>tel</td>
            <td>email</td>
            <td>status</td>
            <td>datum</td>
            <td width="90">akcije</td>
        </tr>
    </thead>
    <tbody>
        @foreach($obj as $key => $value)
        <tr>
            <td>{!! $value->id !!}</td>
            <td>{!! $value->name !!}</td>
            <td>{!! $value->cname !!}</td>
            <td>{!! $value->tel !!}</td>
            <td>{!! $value->email !!}</td>
            <td>{!! $value->status !!}</td>
            <td>{!! $value->dateOpen !!}</td>
            <td class="text-right">
            <a class="btn btn-xs btn-success" href="{!! URL::to('ticket/' . $value->id)!!}">P</a>
            <a class="btn btn-xs btn-info" href="{!! URL::to('ticket/' . $value->id . '/edit') !!}">E</a>
            <a class="btn btn-xs btn-danger" href="{!! URL::to('ticket/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
