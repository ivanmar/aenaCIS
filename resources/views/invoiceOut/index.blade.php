@extends('master')

@section('content')


{!! Form::open(array('route'=>'invoiceout.index','method' => 'get','class'=>'form-horizontal')) !!}
<div class="row">
    <div class="col-md-3  col-md-offset-6 ">{!! Form::select('idCustomer', $customer, $idCust, array('class' => 'form-control selChose')) !!} </div> 
    <div class="col-md-2">{!! Form::select('year', $years, $year, array('class' => 'form-control')) !!} </div>
    <div class="col-md-1">{!! Form::submit('Filter', array('class' => 'btn btn-success')) !!}</div>
</div>
{!! Form::close() !!}

<div class="row">
    <div class="col-sm-1 text-left"><a class="btn btn-sm btn-primary" href="{!! URL::to('invoiceout/create') !!}">dodaj račun</a></div>
    <div class="col-sm-11 text-right"><?php echo $obj->appends(request()->input())->links(); ?></div>
</div>


<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>št.Račun</td>
            <td>Datum oddaje</td>
            <td>Podjetje</td>
            <td>Sklic</td>
            <td>Iznos NET</td>
            <td width="90">akcije</td>
        </tr>
    </thead>
    <tbody>
        @foreach($obj as $key => $value)
          @if($value->indStorno)
           <tr class="warning">
          @else
           <tr>
          @endif
            <td>{!! $value->nrInvoice !!}</td>
            <td>{!! $value->dateIssue !!}</td>
          @if (isset($value->cname))
            <td>{!! $value->cname  !!}</td>
          @else
            <td> končni kupec </td>
          @endif
            <td>{!! $value->nrRef !!}</td>
            <td>{!! DB::table('invoiceOutArt')->select(DB::raw('sum(priceUnit * qty) AS priceNet'))->where('idInvoiceOut',$value->id)->value('priceNet');!!}</td>

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
    
    $('select.selChose').chosen({allow_single_deselect: true});
</script>

@stop
