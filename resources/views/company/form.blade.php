@extends('master')

@section('content')
{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$company->id),'method'=>$formMethod, 'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> {!! $formTitle !!}</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">naziv *</span>
                        {!! Form::text('name', $company->name, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">telefon</span>
                        {!! Form::text('phone', $company->phone, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName"> email</span>
                        {!! Form::text('email', $company->email, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">naslov</span>
                        {!! Form::text('address', $company->address, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">zip</span>
                        {!! Form::text('zipCode', $company->zipCode, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">mesto</span>
                        {!! Form::text('city', $company->city, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">DDV št.</span>
                        {!! Form::text('ddvCode', $company->ddvCode, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">DDV zavezanec</span> {!! Form::checkbox('indTax', 1, $company->indTax) !!} <br>
                        <span class="cFieldName">Ind Vendor</span> {!! Form::checkbox('indVendor', 1, $company->indVendor) !!}
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">bančni rač.</span>
                        {!! Form::text('bankAccount', $company->bankAccount, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">b2b info</span>
                        {!! Form::textarea('b2bAccess', $company->b2bAccess, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">opomba</span>
                        {!! Form::textarea('note', $company->note, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
                <a style="display:{!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to('company')!!}">Cancel</a>
            </div>
        </div>
    </div>
{!! Form::close() !!}
@stop
