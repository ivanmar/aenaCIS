@extends('master')

@section('content')
{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod, 'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> Produkti </h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">naziv *</span>
                        {!! Form::text('name', $obj->name, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">koda naša</span>
                        {!! Form::text('codeSelf', $obj->codeSelf, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName"> koda dob</span>
                        {!! Form::text('codeVendor', $obj->codeVendor, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">grupa</span>
                        {!! Form::select('idProductGroup', $grList, $obj->idProductGroup, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">dobavitelj</span>
                        {!! Form::select('idCompany', $compList, $obj->idCompany, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">cena nabave</span>
                        {!! Form::text('priceVendor', $obj->priceVendor, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">cena naša</span>
                        {!! Form::text('priceSelf', $obj->priceSelf, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">opomba</span>
                        {!! Form::textarea('note', $obj->note, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Aktivan</span>
                        {!! Form::checkbox('indActive', 1, $obj->indActive) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">zaloga</span>
                        {!! Form::text('onStock', $obj->onStock, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>

                <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
                <a style="display:{!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to('product')!!}">Cancel</a>
            </div>
        </div>
    </div>
{!! Form::close() !!}
@stop
