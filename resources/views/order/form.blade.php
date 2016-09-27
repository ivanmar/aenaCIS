@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'id'=>'orderForm', 'class'=>'form-horizontal','autocomplete'=>'off')) !!}

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"> Naročilo </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> naziv opreme *</span>
                    {!! Form::text('nameDevice', $obj->nameDevice, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-2'>
                    <span class="cFieldName"> status</span>
                    {!! Form::select('statusOrder', $statusOrder, $obj->statusOrder, 
                            array('class' => 'form-control')) !!}

                </div>
            </div>
                    
            
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> stranka *</span>
@include('collabselcust')
                    </div>
                
                
                <div class="col-md-2">
                    <br>
                    <span class="btn btn-sm btn-primary" id="popAddCust"> dodaj stranko >> </span>
                    
                    
                    <div id="popAddCustCont" class="hide">
                        <input type='text' name="hid_name" placeholder="ime in primek" id="popName">
                        <input type='text' name="hid_tel" placeholder="Telefon" id="popTel">
                        <input type='text' name="hid_email" placeholder="Email" id="popEmail">
                        <input type='text' name="hid_address" placeholder="Naslov" id="popAddress">
                        <input type='text' name="hid_company" placeholder="Podjetje" id="popCompany">
                        <input type='text' name="hid_ddvcompany" placeholder="DDV Podjetja" id="popDdvCompany">
                        <input type='button' value="dodaj" id="popSubmit">
                            <script>
                                $('#popSubmit').click(function (e) {
                                e.preventDefault();
                                var nameCust = $('#popName').val();
                                $.ajax({type: "POST",
                                    url: "/js/addcust",
                                    data: { 
                                        name: nameCust,
                                        tel: $('#popTel').val(),
                                        email: $('#popEmail').val(),
                                        address: $('#popAddress').val(),
                                        company: $('#popCompany').val(),
                                        ddvCompany: $('#popDdvCompany').val(),
                                        _token: $('input[name=_token]').val()
                                    },
                                    success:function(data)
                                    {
                                        $('#idCustomer').val(data);
                                        $('#selCust').val(nameCust);
                                    }
                                });

                                $("#popAddCust").popover('hide');

                                });
                            </script>
                    </div>
                </div>
                <div class='col-md-2'>
                    <span class="cFieldName"> sprejel *</span>
                    {!! Form::select('idUserRec', $users, $obj->idUserRec, 
                            array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-1'>
                    <span class="cFieldName"> datum prejema</span>
                    {!! Form::text('dateRec', isset($obj->dateRec)?$obj->dateRec:date('Y-m-d'), 
                    array('class' => 'form-control dateSel')) !!}
               </div>
            </div>
            <div class="form-group">
            	<div class='col-md-2 col-md-offset-7'>
                    <span class="cFieldName"> oddal</span>
                    {!! Form::select('idUserFin', array('izberite')+$users, $obj->idUserFin, 
                            array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-1'>
                    <span class="cFieldName"> datum oddaje</span>
                    {!! Form::text('dateFin', $obj->dateFin,
                    array('class' => 'form-control dateSel')) !!}
                </div>
            </div>
           
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> zahtevano delo in stanje opreme</span>
                    {!! Form::textarea('stateRec', $obj->stateRec, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2'>
                    <span class="cFieldName"> serijska št</span>
                    {!! Form::text('serialDevice', $obj->serialDevice, array('class' => 'form-control')) !!} <br>
                    <span class="cFieldName"> cena napovedana</span>
                    {!! Form::text('pricePredict', $obj->pricePredict, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-5'>
                    <span class="cFieldName"> opomba</span>
                    {!! Form::textarea('note', $obj->note, array('class' => 'form-control')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> uporabljeni materijal</span>
                    {!! Form::textarea('material', $obj->material, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-5 col-md-offset-2'>
                    <span class="cFieldName"> opravljeno delo</span>
                    {!! Form::textarea('workDone', $obj->workDone, array('class' => 'form-control')) !!}
                </div>
            </div>

            <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
            <a style="display: {!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger" href="{!!URL::to('order')!!}">Cancel</a>
            <a href="#" id="resetBtn" class="btn btn-sm btn-warning">Reset</a>

        </div>
    </div>
</div>
{!! Form::close() !!}

<script>

    $(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});
    
    $("#popAddCust").popover({
    html: true,
    content: function () {
        return $("#popAddCustCont").html();
    }});

    $('html').on('mouseup', function(e) {
        if(!$(e.target).closest('.popover').length) {
            $('.popover').each(function(){
                $(this.previousSibling).popover('hide');
            });
        }
    });
</script> 
@stop

