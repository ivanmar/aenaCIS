@extends('master')

@section('content')
{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$contact->id),'method'=>$formMethod, 'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> {!! $formTitle !!}</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">naziv *</span>
                        {!! Form::text('name', $contact->name, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">telefon</span>
                        {!! Form::text('tel', $contact->tel, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName"> email</span>
                        {!! Form::text('email', $contact->email, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">podjetje</span>
                        {!! Form::select('idCompany', $compList, $contact->idCompany, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">mesto</span>
                        {!! Form::text('city', $contact->city, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-1 col-md-offset-2">
                        <span class="cFieldName">zip</span>
                        {!! Form::text('zipCode', $contact->zipCode, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-3 col-md-offset-1">
                        <span class="cFieldName">naslov</span>
                        {!! Form::text('address', $contact->address, array('class' => 'form-control input-sm')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <span class="cFieldName">opomba</span>
                        {!! Form::textarea('note', $contact->note, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
                <a style="display:{!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to('contact')!!}">Cancel</a>
            </div>
        </div>
    </div>
{!! Form::close() !!}
@stop
