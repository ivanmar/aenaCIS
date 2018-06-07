@extends('master')

@section('content')
{!! Form::open(array('route'=>'project.index','method' => 'get','class'=>'form-horizontal')) !!}
<div class="row">
    <div class="col-md-2  col-md-offset-7 ">{!! Form::select('idCompany', $company, $idComp, array('class' => 'form-control')) !!} </div>
    <div class="col-md-2">{!! Form::select('year', $years, $year, array('class' => 'form-control')) !!} </div>
    <div class="col-md-1">{!! Form::submit('Filter', array('class' => 'btn btn-success')) !!}</div>
</div>
{!! Form::close() !!}
<p><a class="btn btn-sm btn-primary" href="{!! URL::to('project/create') !!}">add new</a></p>

<table class="table table-striped table-bprojected">
    <thead>
        <tr>
            <td>ID</td>
            <td>projekt</td>
            <td>podjetje</td>
            <td>odprto</td>
            <td>status</td>
            <td width="90">akcije</td>
        </tr>
    </thead>
    <tbody>
        @foreach($obj as $key => $value)
        <tr>
            <td>{!! $value->id !!}</td>
            <td>{!! $value->name !!}</td>
            <td>{!! $value->cname !!}</td>
            <td>{!! $value->dateOpen !!}</td>
            <td>{!! $value->status !!}</td>
            <td class="text-right">
            <a class="btn btn-xs btn-info" href="{!! URL::to('project/' . $value->id . '/edit') !!}">E</a>
            <a class="btn btn-xs btn-danger" href="{!! URL::to('project/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
