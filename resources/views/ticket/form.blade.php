@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'class'=>'form-horizontal','autocomplete'=>'off')) !!}

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"> Ticket </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> naziv *</span>
                    {!! Form::text('name', $obj->name, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-2'>
                    <span class="cFieldName"> status</span>
                    {!! Form::select('status', $status, $obj->status, array('class' => 'form-control')) !!}
                </div>
            </div>
                    
            
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> stranka *</span>
@include('selcontact')
                    </div>
                
                
                <div class="col-md-2">
                    <br>
                    <span class="btn btn-sm btn-primary" id="popAddCont"> dodaj stranko >> </span>
                    
                    
                    <div id="popAddCustCont" class="hide">
                        <input type='text' name="hid_name" placeholder="ime in primek" id="popName">
                        {!! Form::select('hid_idCompany', $compList, null, array('id' => 'popIdCompany')) !!}
                        <input type='text' name="hid_tel" placeholder="Telefon" id="popTel">
                        <input type='text' name="hid_email" placeholder="Email" id="popEmail">
                        <input type='text' name="hid_address" placeholder="Naslov" id="popAddress">
                        <input type='text' name="hid_city" placeholder="Mesto" id="popCity">
                        <input type='text' name="hid_zipCode" placeholder="ZIP" id="popZipCode">
                        <input type='button' value="dodaj" id="popSubmit">
                            <script>
                                $('#popSubmit').click(function (e) {
                                e.preventDefault();
                                var nameCont = $('#popName').val();
                                $.ajax({type: "POST",
                                    url: "/aenaCIS/js/addcontact",
                                    data: { 
                                        name: nameCont,
                                        idCompany: $('#popIdCompany').val(),
                                        tel: $('#popTel').val(),
                                        email: $('#popEmail').val(),
                                        address: $('#popAddress').val(),
                                        city: $('#popCity').val(),
                                        zipCode: $('#popZipCode').val(),
                                        _token: $('input[name=_token]').val()
                                    },
                                    success:function(data)
                                    {
                                        $('#idContact').val(data);
                                        $('#selCont').val(nameCont);
                                    }
                                });

                                $("#popAddCont").popover('hide');

                                });
                            </script>
                    </div>
                </div>
                <div class='col-md-2'>
                    <span class="cFieldName"> datum sprejeto</span>
                    {!! Form::text('dateOpen', isset($obj->dateOpen)?$obj->dateOpen:date('Y-m-d'), array('class' => 'form-control dateSel')) !!}
                </div>
                <div class='col-md-2 col-md-offset-1'>
                    <span class="cFieldName"> datum končano</span>
                    {!! Form::text('dateClose', $obj->dateClose, array('class' => 'form-control dateSel')) !!}
               </div>
            </div>
           
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> zahtevano delo in stanje opreme</span>
                    {!! Form::textarea('ticketDesc', $obj->ticketDesc, array('class' => 'form-control')) !!}
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
                    {!! Form::textarea('partUsed', $obj->partUsed, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-5 col-md-offset-2'>
                    <span class="cFieldName"> opravljeno delo</span>
                    {!! Form::textarea('ticketRes', $obj->ticketRes, array('class' => 'form-control')) !!}
                </div>
            </div>

            <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
            <a style="display: {!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger" href="{!!URL::to('ticket')!!}">Cancel</a>
            <a href="#" id="resetBtn" class="btn btn-sm btn-warning">Reset</a>

        </div>
    </div>
</div>
{!! Form::close() !!}

<script>

    $(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});
    
    $("#popAddCont").popover({
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

