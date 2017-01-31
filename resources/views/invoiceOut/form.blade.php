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
                        {!! Form::select('idCompany', $customer, $obj->idCompany, array('class' => 'form-control','id'=>'idCompany')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Št. računa</span>
                        {!! Form::text('nrInvoice', (isset($lastNrInvoice) ? $lastNrInvoice : $obj->nrInvoice), array('class' => 'form-control input-sm','id'=>'nrInvoice')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName"> Datum oddaje</span>
                        {!! Form::text('dateIssue', (isset($obj->dateIssue) ? $obj->dateIssue : date('Y-m-d')), array('class' => 'form-control input-sm dateSel','id'=>'dateIssue')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">Opis Računa</span>
                        {!! Form::textarea('desc', $obj->desc, array('class' => 'form-control','rows'=>'5','id'=>'desc')) !!}
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">Opis Interno</span>
                        {!! Form::textarea('descInternal', $obj->descInternal, array('class' => 'form-control','rows'=>'5','id'=>'descInternal')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
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
                    <div class="col-md-1 col-md-offset-5">
                        <span class="cFieldName">Cena dostave</span>
                        {!! Form::text('shipCost', $obj->shipCost, array('class' => 'form-control input-sm','id'=>'shipCost')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">Stavek</span>
                        {!! Form::text('nameItem', null, array('class' => 'form-control input-sm','id'=>'nameItem')) !!}
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <span class="cFieldName">kol</span>
                        {!! Form::text('qtyItem', 1, array('class' => 'form-control input-sm','id'=>'qtyItem')) !!}
                    </div>
                    <div class="col-md-1">
                        <span class="cFieldName">cena</span>
                        {!! Form::text('priceUnit', 0, array('class' => 'form-control input-sm','id'=>'priceUnit')) !!}
                    </div>
                    <div class="col-md-1">
                        <br>
                        <button class="btn btn-sm btn-primary" id="addItem"> Dodaj</button>
                    </div>
                </div>
                
                <hr>
<?php $priceTot = 0; ?>
                
@if (Session::has('sessInvoOut'))
<?php $sessData = Session::get('sessInvoOut'); ?>
@foreach( $sessData as $key => $val )
            
<?php 
        if($val['idProduct'] > 0) {
            $priceUnit = DB::table('product')->where('id',$val['idProduct'])->value('priceSelf');
            $nameItem = DB::table('product')->where('id',$val['idProduct'])->value('name');

        } else {
            $nameItem = $val['nameItem'];
            $priceUnit = $val['priceUnit'];
        }
        $qty = $val['qty'];
        $priceProdTot = $priceUnit * $qty;
?>
                <div class="form-group well">
                    <div class="col-md-1">
                        <b>{!! $key + 1 !!} </b>
                    </div>
                    <div class="col-md-4">
                        <b>{!! $nameItem !!} </b>
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
                        {!! $priceProdTot !!}
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-xs btn-danger delProduct" id='p{!! $key !!}'>D</a>
                    </div>
                </div>
                <?php $priceTot += $priceProdTot; ?>
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
    window.onload = function() {
        // If sessionStorage is storing default values (ex. name), exit the function and do not restore data
        if (sessionStorage.getItem('idCompany') === "idCompany") {
            return;
        }
        // If values are not blank, restore them to the fields
        var idCompany = sessionStorage.getItem('idCompany');
        if (idCompany !== null) $('#idCompany').val(idCompany);

        var nrInvoice = sessionStorage.getItem('nrInvoice');
        if (nrInvoice !== null) $('#nrInvoice').val(nrInvoice);

        var dateIssue= sessionStorage.getItem('dateIssue');
        if (dateIssue !== null) $('#dateIssue').val(dateIssue);

        var descInternal= sessionStorage.getItem('descInternal');
        if (descInternal !== null) $('#descInternal').val(descInternal);
        
        var desc= sessionStorage.getItem('desc');
        if (desc !== null) $('#desc').val(desc);
        
        var shipCost= sessionStorage.getItem('shipCost');
        if (shipCost !== null) $('#shipCost').val(shipCost);
    }

    // Before refreshing the page, save the form data to sessionStorage
    window.onbeforeunload = function() {
        sessionStorage.setItem("idCompany", $('#idCompany').val());
        sessionStorage.setItem("nrInvoice", $('#nrInvoice').val());
        sessionStorage.setItem("dateIssue", $('#dateIssue').val());
        sessionStorage.setItem("descInternal", $('#descInternal').val());
        sessionStorage.setItem("shipCost", $('#shipCost').val());
        sessionStorage.setItem("desc", $('#desc').val());
    }
    
$(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});

$('#addProduct').click(function (e) {
    e.preventDefault();
    var idProduct = $('#idProduct').val();
    if(idProduct > 0) {
    $.ajax({type: "POST",
        url: "/js/addsessinvoout",
        data: {
            idProduct: idProduct,
            qty: $('#qtyProduct').val(),
            _token: $('input[name=_token]').val()
        },
        success: function (data)
        {
           window.location.reload();
        }
    });
} else { alert('izberite iz seznama'); }
});
$('#addItem').click(function (e) {
    e.preventDefault();
    var nameItem = $('#nameItem').val();
    if(nameItem) {
    $.ajax({type: "POST",
        url: "/js/addsessinvoout",
        data: {
            nameItem: nameItem,
            qty: $('#qtyItem').val(),
            priceUnit: $('#priceUnit').val(),
            _token: $('input[name=_token]').val()
        },
        success: function (data)
        {
           window.location.reload();
        }
    });
} else { alert('izberite iz seznama'); }
});


$('.delProduct').click(function() {
    $.ajax({type: "POST",
        url: "/js/delsessinvoout",
        data: {
            index: this.id.substring(1),
            _token: $('input[name=_token]').val()
        },
        success: function (data) { location.reload(); }
    });
});

</script>
    
@stop
