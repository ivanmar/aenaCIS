@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'class'=>'form-horizontal','autocomplete'=>'off')) !!}

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"> KB </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> naziv *</span>
                    {!! Form::text('name', $obj->name, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-2'>
                    <span class="cFieldName"> podjetje</span>
                    {!! Form::select('idCompany', $compList, $obj->idCompany, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-1'>
                    <span class="cFieldName"> projekt</span>
                    {!! Form::select('idProject', $projList, $obj->idProject, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                <div class='col-md-12'>
                    <span class="cFieldName"> Tekst</span>
                    {!! Form::textarea('text', $obj->text, array('class' => 'form-control','rows' => '30')) !!}
                </div>
            </div>
            <div class="row">
                <div class='col-md-1 col-md-offset-8'>
                    <a class="btn btn-sm btn-danger" href="{!!URL::to('kb')!!}">Cancel</a> 
                </div>
                <div class='col-md-1 col-md-offset-2'>
                    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

@stop