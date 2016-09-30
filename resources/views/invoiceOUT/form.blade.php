@extends('master')

@section('content')

<!-- if there are creation errors, they will show here -->
{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$bill->id),'method'=>$formMethod,'id'=>'billForm', 'class'=>'form-horizontal')) !!}


<div class="form-group">
    <label class="col-md-1"> Stranka</label>
    <div class='col-md-4'>
        {!! Form::select('idCustomer', $customer, $bill->idCustomer, array('class' => 'form-control')) !!}</div>
    <label class="col-md-1"> Opis </label>
    <div class='col-md-6'>
        {!! Form::text('desc', $bill->desc, array('class' => 'form-control')) !!}</div>
</div>
<div class="form-group">
    <label class="col-md-7"> Storitev</label>
    <label class="col-md-1"> Količina</label>
    <label class="col-md-1"> Rabat(%) </label>
    <label class="col-md-1"> DDV </label>
    <label class="col-md-2"> Cena en </label>
</div>
@for($i=0; $i<5; $i++)
<div class="form-group">
    <div class='col-md-6 col-md-offset-1'>
        {!! Form::select('idService[]', $service, $bill->idService, array('class' => 'form-control','id'=>'idSer'.$i)) !!}</div>
    <div class='col-md-1'>
        {!! Form::text('qty[]', isset($bill->qty) ? $bill->qty : '1', array('class' => 'form-control','id'=>'qty'.$i)) !!}</div>
    <div class='col-md-1'>
        {!! Form::text('rDisSingle[]', isset($bill->qty) ? $bill->rDiscount : '0', array('class' => 'form-control','id'=>'rDisSingle'.$i)) !!}</div>
    <div class='col-md-1'> <span id="tax{!!$i!!}"></span> </div>
    <div class='col-md-2 bg-info'> <span id="price{!!$i!!}" class="form-control"></span> </div>
</div>
@endfor
<div class="form-group">
    <label class="col-md-2 col-md-offset-2"> Rabat(%)</label>
    <div class='col-md-3'>
        {!! Form::text('rDiscount', null, array('class' => 'form-control','id'=>'rDiscount')) !!}</div>
    <label class="col-md-2"> Cena tot</label>
    <div class='col-md-3 bg-info'> <span class="form-control" id="priceTot"></span></div>
</div>
<div class="form-group">
    <label class="col-md-2 col-md-offset-7"> Cena + rab</label>
    <div class='col-md-3 bg-info'> <span id="priceTotDis" class="form-control" ></span>  </div>
</div>
<div class="form-group">
    <div class='col-md-7'></div>
    <label class="col-md-2"> Cena + rab + ddv</label>
    <div class='col-md-3 bg-info'> <span id="priceTotDisTax" class="form-control" ></span>  </div>
</div>

<div class="row">

    <div class='col-md-2 col-md-offset-6'>{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}</div>
    <div class='col-md-2'>{!! Form::button('Calc', array('id' => 'calcBtn','class'=>'btn btn-default')) !!}</div>
    <div class='col-md-2'>{!! Form::button('Reset', array('id' => 'resetBtn','class'=>'btn btn-default')) !!}</div>

</div>

{!! Form::close() !!}

<script>
    

    $("#resetBtn").click(function() {$('#billForm')[0].reset(); calcPrice();});
    $("#calcBtn").click(function() {calcPrice();});

    function isNumber(n) { return !isNaN(parseFloat(n)) && isFinite(n);}
    function getPrice(cid, fldid) {
        $.get('/js/getserprice/' + cid, function(dprice) {
            $("#price"+fldid).text(dprice[0]);
            $("#tax"+fldid).text(dprice[1]);
            calcPrice();
        });
    }
    function calcPrice() {
        var pricetot=0;
        var pricetotdis=0;
        var pricetotdistax=0;
        var discount =1;
        if(isNumber($('#rDiscount').val())) {
            discount = 1 - ($('#rDiscount').val() / 100 );
        }
        for(var i=0; i<5; i++ ){
            var price = $('#price'+i).text();
            var sdis = $('#rDisSingle'+i).val();
            if(isNumber(price)) {
                var tmpprice = price * $('#qty'+i).val();
                if(isNumber(sdis)) {
                    var tmppricedis = tmpprice * (1 - sdis / 100);
                } else {
                    var tmppricedis = tmpprice;
                }
                pricetot += tmpprice;
                pricetotdis += tmppricedis;
                pricetotdistax += tmppricedis * (1 + ($('#tax'+i).text() / 100));
            }
        }
        $("#priceTot").text((pricetot).toFixed(2));
        $("#priceTotDis").text((pricetotdis).toFixed(2));
        $("#priceTotDisTax").text((pricetotdistax * discount).toFixed(2));

    }
    
    $('#idSer0').change(function() { getPrice($(this).val(),'0'); });
    $('#idSer1').change(function() { getPrice($(this).val(),'1'); });
    $('#idSer2').change(function() { getPrice($(this).val(),'2'); });
    $('#idSer3').change(function() { getPrice($(this).val(),'3'); });
    $('#idSer4').change(function() { getPrice($(this).val(),'4'); });
    

// $('#qty0').keyup(function() {if (isNumber($(this).val())) {calcPrice();} });
// $('#qty0').focusout(function() {if (! isNumber($(this).val())) {alert('vnesi ševilko');setTimeout(function() {$('#qty0').focus();}, 1);} });

    
</script> 
@stop

