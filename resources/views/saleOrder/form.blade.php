@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> NAROČILO </h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">Podjetje</span>
                        {!! Form::select('idCompany', $compList, $obj->idCompany, array('class' => 'form-control','id'=>'idCompany')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">Stranka</span>
                        @include('selcontact')
                    </div>
                    <div class="col-md-1">
                        <br>
                        <span class="btn btn-sm btn-primary" id="popAddCont"> dodaj stranko >> </span>
    @include('popcontact')              
                    </div>
                    
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">Datum nar.</span>
                         {!! Form::text('dateOrder', (isset($obj->dateOrder) ? $obj->dateOrder : date('Y-m-d')), array('class' => 'form-control input-sm dateSel','id'=>'dateOrder')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName"> Datum za</span>
                        {!! Form::text('dateFor', $obj->dateFor, array('class' => 'form-control input-sm dateSel','id'=>'dateFor')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">Status Naročila</span>
                        {!! Form::select('status', $status, $obj->status, array('class' => 'form-control','id'=>'status')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">Nar. prek</span>
                        {!! Form::select('orderOrigin', $orderOrigin, $obj->orderOrigin, array('class' => 'form-control input-sm','id'=>'orderOrigin')) !!} <br>
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">Opis Naročila</span>
                        {!! Form::textarea('desc', $obj->desc, array('class' => 'form-control','rows'=>'5','id'=>'desc')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
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
                
                <hr>
<?php $priceTot = 0; ?>
                
@if (Session::has('sessInvoOut'))
<?php $sessData = Session::get('sessInvoOut'); ?>
@foreach( $sessData as $key => $val )
            
<?php 

        if($val['idProduct'] > 0) {
            $priceUnit = DB::table('product')->where('id',$val['idProduct'])->value('priceSelf');
            $nameItem = DB::table('product')->where('id',$val['idProduct'])->value('name');
            $qty = $val['qty'];
            $priceProdTot = $priceUnit * $qty;
        }
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
    <a style="display: {!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to('saleorder')!!}">Cancel</a>
</div>
</div>
</div>
{!! Form::close() !!}

<script>
    $(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});
    
    window.onload = function() {
        // If sessionStorage is storing default values (ex. name), exit the function and do not restore data
        if (sessionStorage.getItem('idCompany') === "idCompany") {
            return;
        }
        // If values are not blank, restore them to the fields
        var idCompany = sessionStorage.getItem('idCompany'); if (idCompany !== null) $('#idCompany').val(idCompany);
        var idContact = sessionStorage.getItem('idContact'); if (idContact !== null) $('#idContact').val(idContact);
        var selCont = sessionStorage.getItem('selCont'); if (selCont !== null) $('#selCont').val(selCont);
        var dateOrder= sessionStorage.getItem('dateOrder'); if (dateOrder !== null) $('#dateOrder').val(dateOrder);
        var dateFor= sessionStorage.getItem('dateFor'); if (dateFor !== null) $('#dateFor').val(dateFor);
        var status= sessionStorage.getItem('status');if (status !== null) $('#status').val(status);
        var orderOrigin= sessionStorage.getItem('orderOrigin');if (orderOrigin !== null) $('#orderOrigin').val(orderOrigin);
        var desc= sessionStorage.getItem('desc');if (desc !== null) $('#desc').val(desc);
    }

    // Before refreshing the page, save the form data to sessionStorage
    window.onbeforeunload = function() {
        sessionStorage.setItem("idCompany", $('#idCompany').val());
        sessionStorage.setItem("idContact", $('#idContact').val());
        sessionStorage.setItem("selCont", $('#selCont').val());
        sessionStorage.setItem("dateOrder", $('#dateOrder').val());
        sessionStorage.setItem("dateFor", $('#dateFor').val());
        sessionStorage.setItem("status", $('#status').val());
        sessionStorage.setItem("orderOrigin", $('#orderOrigin').val());
        sessionStorage.setItem("desc", $('#desc').val());
    }
    

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

$("#popAddCont").popover({
    html: true,
    content: function () {
        return $("#popAddCustCont").html();
    }});
</script>
    
@stop
