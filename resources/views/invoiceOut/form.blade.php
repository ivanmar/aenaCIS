@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'id'=>'invForm', 'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> RAČUN ODDAJA</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">Stranka</span>
                        {!! Form::select('idCompany', $customer, $obj->idCompany, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Št. računa</span>
                        {!! Form::text('nrInvoice', $obj->nrInvoice, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName"> Datum oddaje</span>
                        {!! Form::text('dateIssue', $obj->dateIssue, array('class' => 'form-control input-sm dateSel')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-7">
                        <span class="cFieldName">Opis</span>
                        {!! Form::textarea('desc', $obj->desc, array('class' => 'form-control','rows'=>'5')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Izdelek</span>
                        {!! Form::select('idProduct', $products, null, array('class' => 'form-control input-sm','id'=>'idProduct')) !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">kol</span>
                        {!! Form::text('qtyProduct', 1, array('class' => 'form-control input-sm','id'=>'qtyProduct')) !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <br>
                        <button class="btn btn-sm btn-primary" id="addProduct"> Dodaj</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Storitev</span>
                        {!! Form::select('idService', $services, null, array('class' => 'form-control input-sm','id'=>'idService')) !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">kol</span>
                        {!! Form::text('qtyService', 1, array('class' => 'form-control input-sm','id'=>'qtyService')) !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <br>
                        <button class="btn btn-sm btn-primary" id="addService"> Dodaj</button>
                    </div>
                </div>
                
                <hr>
<?php $priceTot = 0; $index=1; ?>
                
@if (Session::has('sessDataProduct'))
<?php $sessDataProduct = Session::get('sessDataProduct'); ?>
@foreach( $sessDataProduct as $idProduct => $qty )
            
<?php $nameProduct = DB::table('product')->where('id',$idProduct)->value('name');
      $priceSelf = DB::table('product')->where('id',$idProduct)->value('priceSelf');
      $priceProdTot = $priceSelf * $qty;
?>
                <div class="form-group well">
                    <div class="col-md-1">
                        <b>{!! $index !!} </b>
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <b>{!! $nameProduct !!} </b>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">kol : </span>
                        {!! $qty !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">cena enote : </span>
                        {!! $priceSelf !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">cena : </span>
                        {!! $priceProdTot !!}
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-xs btn-danger delProduct" id='p{!! $idProduct !!}'>D</a>
                    </div>
                </div>
                <?php $priceTot += $priceProdTot; $index++; ?>
@endforeach
@endif



@if (Session::has('sessDataService'))
<?php $sessDataService = Session::get('sessDataService'); ?>
@foreach($sessDataService as $idService => $qty)
            
<?php $nameService = DB::table('service')->where('id',$idService)->value('name');
      $priceUnit = DB::table('service')->where('id',$idService)->value('priceUnit');
      $priceServTot = $priceUnit * $qty;
?>
                <div class="form-group well">
                    <div class="col-md-1">
                        <b>{!! $index !!} </b>
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <b>{!! $nameService !!} </b>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">kol : </span>
                        {!! $qty !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">cena enote : </span>
                        {!! $priceUnit !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">cena : </span>
                        {!! $priceServTot !!}
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-xs btn-danger delService" id='s{!! $idService !!}'>D</a>
                    </div>
                </div>
                <?php $priceTot += $priceServTot; $index++; ?>
@endforeach
@endif

<div class="form-group"> <div class="col-md-2 col-md-offset-10"> <b>TOTAL: {!! $priceTot or '0' !!} EUR </b> </div> </div>






    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
    <a style="display: {!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to('invoiceout')!!}">Cancel</a>
</div>
</div>
</div>
{!! Form::close() !!}

<script>
$(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});

$('#addProduct').click(function (e) {
    e.preventDefault();
    var idProduct = $('#idProduct').val();
    if(idProduct > 0) {
    $.ajax({type: "POST",
        url: "/js/addsessproduct",
        data: {
            idProduct: idProduct,
            qty: $('#qtyProduct').val(),
            _token: $('input[name=_token]').val()
        },
        success: function (data)
        {
           location.reload();
        }
    });
} else { alert('izberite iz seznama'); }
});

$('#addService').click(function (e) {
    e.preventDefault();
    var idService = $('#idService').val();
    if(idService > 0) {
    $.ajax({type: "POST",
        url: "/js/addsessservice",
        data: {
            idService: idService,
            qty: $('#qtyService').val(),
            _token: $('input[name=_token]').val()
        },
        success: function (data)
        {
           location.reload();
        }
    });
} else { alert('izberite iz seznama'); }
});

$('.delProduct').click(function() {
    $.ajax({type: "POST",
        url: "/js/delsessproduct",
        data: {
            idProduct: this.id.substring(1),
            _token: $('input[name=_token]').val()
        },
        success: function (data) { location.reload(); }
    });
});
$('.delService').click(function() {
    $.ajax({type: "POST",
        url: "/js/delsessservice",
        data: {
            idService: this.id.substring(1),
            _token: $('input[name=_token]').val()
        },
        success: function (data) { location.reload(); }
    });
});
</script>
    
@stop
