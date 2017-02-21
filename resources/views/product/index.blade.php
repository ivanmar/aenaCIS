@extends('master')
@section('content')
{!! Form::open(array('route'=>'product.index','method' => 'get','class'=>'form-horizontal')) !!}
<div class="row">
    <div class="col-md-2  col-md-offset-7 ">{!! Form::select('idManufact', $manufacturer, $idManufact, array('class' => 'form-control')) !!} </div>
    <div class="col-md-2">{!! Form::select('idProductGroup', $productgroup, $idProG, array('class' => 'form-control')) !!} </div>
    <div class="col-md-1">{!! Form::submit('Filter', array('class' => 'btn btn-success')) !!}</div>
</div>
{!! Form::close() !!}
<div class="row">
    <div class="col-sm-2"><p><a class="btn btn-sm btn-primary" href="{!! URL::to('product/create') !!}">add new</a></p></div>
    <div class="col-sm-10 text-right"><?php echo $obj->render(); ?></div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <td>id</td>
            <td>naziv</td>
            <td>koda naša</td>
            <td>koda proiz</td>
            <td>cena naša</td>
            <td>grupa</td>
            <td>proizvajalec</td>
            <td width="70"></td>
        </tr>
    </thead>
    <tbody>
        @foreach($obj as $key => $value)
        <tr class="active">
            <td>{!! $value->id !!}</td>
            <td>{!! $value->name !!}</td>
            <td>{!! $value->codeSelf !!}</td>
            <td>{!! $value->codeManufact !!}</td>
            <td>{!! $value->priceSelf !!}</td>
            <td>{!! $value->gname !!} </td>
            <td>{!! $value->mname !!}</td>
            <td>
                <a class="btn btn-xs btn-info" href="{!! URL::to('product/' . $value->id . '/edit') !!}">E</a>
                <a class="btn btn-xs btn-danger" href="{!! URL::to('product/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
