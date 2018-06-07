@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> REKLAMACIJA</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">Stranka</span>
                        {!! Form::select('idContact', $contact, $obj->idContact, array('class' => 'form-control','id'=>'idContact')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">Podjetje</span>
                        {!! Form::select('idCompany', $company, $obj->idCompany, array('class' => 'form-control','id'=>'idCompany')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Datum rekl.</span>
                         {!! Form::text('dateStart', (isset($obj->dateStart) ? $obj->dateStart : date('Y-m-d')), array('class' => 'form-control input-sm dateSel')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">ID Računa</span>
                         {!! Form::text('idInvoiceOut', $obj->idInvoiceOut, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName"> Izdelek</span>
                        {!! Form::select('idProduct', $products, $obj->idProduct, array('class' => 'form-control','id'=>'idProduct')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">Status Reklamacije</span>
                        {!! Form::select('status', $status, $obj->status, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">Opis</span>
                        {!! Form::textarea('desc', $obj->desc, array('class' => 'form-control','rows'=>'5','id'=>'desc')) !!}
                    </div>
                </div>
                
                <hr>

    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
    <a style="display: {!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to('reclamation')!!}">Cancel</a>
</div>
</div>
</div>
{!! Form::close() !!}

<script>
    $(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});

</script>
    
@stop
