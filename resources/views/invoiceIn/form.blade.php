@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'files' => true,'method'=>$formMethod,'id'=>'invForm', 'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> RAČUN PREJEM</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">Stranka</span>
                        {!! Form::select('idCompany', $customer, $obj->idCompany, array('class' => 'form-control','id'=>'idCompany')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Št. računa</span>
                        {!! Form::text('nrInvoice', $obj->nrInvoice, array('class' => 'form-control input-sm','id'=>'nrInvoice')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName"> Datum računa</span>
                        {!! Form::text('dateIssue', (isset($obj->dateIssue) ? $obj->dateIssue : date('Y-m-d')), array('class' => 'form-control input-sm dateSel','id'=>'dateIssue')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-1">
                        <span class="cFieldName">Marketplace</span>
                        {!! Form::select('marketplace', $marketplaces, $obj->marketplace, array('class' => 'form-control','id'=>'marketplace')) !!}
                    </div>
                    <div class="col-md-3 col-md-offset-1">
                        <span class="cFieldName">MP User</span>
                        {!! Form::text('marketplaceUser', $obj->marketplaceUser, array('class' => 'form-control','id'=>'marketplaceUser')) !!} <br>
                        <span class="cFieldName">Web ref</span>
                        {!! Form::text('webRef', $obj->webRef, array('class' => 'form-control','id'=>'webRef')) !!} 
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">Opis</span>
                        {!! Form::textarea('desc', $obj->desc, array('class' => 'form-control','rows'=>'5','id'=>'desc')) !!}
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
                    <div class="col-md-1">
                        <br>
                        <button class="btn btn-sm btn-primary" id="addProduct"> Dodaj</button>
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="btn btn-primary btn-file">
                                Scan upload… <input name="scan0" type="file">
                        </span>
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <img src="{!!isset($obj->scan0)?'/public/upload/image/'.$obj->scan0:'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D'!!}" 
                         width="80" height="80">
                        
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



<div class="form-group"> <div class="col-md-2 col-md-offset-10"> <b>TOTAL: {!! $priceTot or '0' !!} EUR </b> </div> </div>

    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
    <a style="display: {!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to('invoicein')!!}">Cancel</a>
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
        var marketplace= sessionStorage.getItem('marketplace');
        if (marketplace !== null) $('#marketplace').val(marketplace);
        var marketplaceUser= sessionStorage.getItem('marketplaceUser');
        if (marketplaceUser !== null) $('#marketplaceUser').val(marketplaceUser);
        var webRef= sessionStorage.getItem('webRef');
        if (webRef !== null) $('#webRef').val(webRef);
        var desc= sessionStorage.getItem('desc');
        if (desc !== null) $('#desc').val(desc);
    }

    // Before refreshing the page, save the form data to sessionStorage
    window.onbeforeunload = function() {
        sessionStorage.setItem("idCompany", $('#idCompany').val());
        sessionStorage.setItem("nrInvoice", $('#nrInvoice').val());
        sessionStorage.setItem("dateIssue", $('#dateIssue').val());
        sessionStorage.setItem("marketplace", $('#marketplace').val());
        sessionStorage.setItem("marketplaceUser", $('#marketplaceUser').val());
        sessionStorage.setItem("webRef", $('#webRef').val());
        sessionStorage.setItem("desc", $('#desc').val());
    }
    
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
           window.location.reload();
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

</script>
    
@stop
