@extends('master')

@section('content')
{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$customer->id),'method'=>$formMethod, 'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> {!! $formTitle !!}</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">naziv *</span>
                        {!! Form::text('name', $customer->name, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">telefon</span>
                        {!! Form::text('tel', $customer->tel, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName"> email</span>
                        {!! Form::text('email', $customer->email, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">podjetje</span>
                        {!! Form::text('company', $customer->company, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">ddv</span>
                        {!! Form::text('ddvCompany', $customer->ddvCompany, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">naslov</span>
                        {!! Form::text('address', $customer->address, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <span class="cFieldName">opomba</span>
                        {!! Form::textarea('note', $customer->note, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
                <a style="display:{!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to('customer')!!}">Cancel</a>
            </div>
        </div>
    </div>
{!! Form::close() !!}
@stop
