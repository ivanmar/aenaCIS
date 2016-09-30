@extends('master')
@section('content')
<div class="row well">
     {!! Form::open(array('route'=>'customer.index','method' => 'get','class'=>'form-horizontal')) !!}
     <div class="col-sm-1 col-sm-offset-8 text-right"> stranka</div>
        <div class="col-sm-2">{!! Form::text('nameCust', $nameCust, array('class' => 'form-control input-sm')) !!} </div>
        <div class="col-sm-1">{!! Form::submit('Filter', array('class' => 'btn btn-sm btn-success')) !!}</div>
    {!! Form::close() !!}
</div>
<div class="row">
    <div class="col-sm-2"><a class="btn btn-sm btn-primary" href="{!! URL::to('customer/create') !!}">dodaj stranko</a></div>
    <div class="col-sm-10 text-right"><?php echo $customer->render(); ?></div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <td>id</td>
            <td>naziv</td>
            <td>telefon</td>
            <td>email</td>
            <td>podjetje</td>
            <td>naslov</td>
            <td></td>
            <td width="70"></td>
        </tr>
    </thead>
    <tbody>
        @foreach($customer as $key => $value)
        <tr class="active">
            <td>{!! $value->id !!}</td>
            <td>{!! $value->name !!}</td>
            <td>{!! $value->tel !!}</td>
            <td>{!! $value->email !!}</td>
            <td>{!! $value->company !!} </td>
            <td>{!! $value->address !!}</td>
            <td> </td>
            <td>
                <a class="btn btn-xs btn-info" href="{!! URL::to('customer/' . $value->id . '/edit') !!}">E</a>
                <a class="btn btn-xs btn-danger" href="{!! URL::to('customer/' . $value->id . '/destroy') !!}"
                   onclick="if(!confirm('Delete this item?')){return false;};">D</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
