@extends('master')

@section('content')


<p><a class="btn btn-sm btn-primary" href="{!! URL::to('contract/create') !!}">dodaj pogodbo</a></p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>Podjetje</td>
            <td>naziv</td>
            <td>začetek</td>
            <td>konec</td>
            <td width="90">akcije</td>
        </tr>
    </thead>
    <tbody>
        @foreach($obj as $key => $value)
        <tr>
            <td>{!! $value->id !!}</td>
            <td>{!! $value->cname  !!}</td>
            <td>{!! $value->name !!}</td>
            <td>{!! $value->dateStart !!}</td>
            <td>{!! $value->dateEnd !!}</td>
            <td class="text-right">
                <a class="btn btn-xs btn-info" href="{!! URL::to('contract/' . $value->id . '/edit') !!}">E</a>
                <a class="btn btn-xs btn-danger" href="{!! URL::to('contract/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop
