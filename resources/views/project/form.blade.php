@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'class'=>'form-horizontal','autocomplete'=>'off')) !!}

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"> Projekt </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> naziv *</span>
                    {!! Form::text('pname', $obj->name, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-2'>
                    <span class="cFieldName"> status</span>
                    {!! Form::select('status', $status, $obj->status, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-1'>
                    <span class="btn btn-sm btn-primary pull-right" id="editForm"> Edit </span>
                </div>
            </div>

            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> podjetje *</span>
                    {!! Form::select('idCompany', $compList, $obj->idCompany, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-2'>
                    <span class="cFieldName"> datum odprto</span>
                    {!! Form::text('dateOpen', isset($obj->dateOpen)?$obj->dateOpen:date('Y-m-d'), array('class' => 'form-control dateSel')) !!}
                </div>
                <div class='col-md-2 col-md-offset-1'>
                    <span class="cFieldName"> datum končano</span>
                    {!! Form::text('dateClose', $obj->dateClose, array('class' => 'form-control dateSel')) !!}
               </div>
            </div>

            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> Opis</span>
                    {!! Form::textarea('desc', $obj->desc, array('class' => 'form-control', 'rows' => '5')) !!}
                </div>

                <div class='col-md-5 col-md-offset-2'>
                    <span class="cFieldName"> Opaska</span>
                    {!! Form::textarea('note', $obj->note, array('class' => 'form-control', 'rows' => '5')) !!}
                </div>
            </div>
            <div class="row">
                <div class='col-md-5'>
                    <span class="btn btn-sm btn-default" id="popAddTask"> Dodaj nalogo</span>
                </div>
                <div class='col-md-2 col-md-offset-2'>
                    <a class="btn btn-sm btn-danger" href="{!!URL::to('project')!!}">Cancel</a> 
                    <a href="#" id="resetBtn" class="btn btn-sm btn-warning">Reset</a>
                </div>
                <div class='col-md-1 col-md-offset-2'>
                    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
                </div>
            </div>
            @include('poptask')
        </div>
    </div>
</div>
{!! Form::close() !!}

@if(isset($comments))
    @foreach($comments as $key => $value)
    <blockquote>
      <p> {!! $value->text !!} </p>
      <footer>{!! $value->username !!} <cite title="Source Title">{!! $value->dateTime !!}</cite></footer>
    </blockquote>
    @endforeach
@endif
<script>

    $(function() {
        if ( document.location.href.indexOf('/create') === -1 ) {
            $("input").prop('disabled', true);
            $("select").prop('disabled', true);
            $("textarea").prop('disabled', true);
        }
    });
    
    $("#editForm").click(function() {
        $("input").prop('disabled', false);
        $("select").prop('disabled', false);
        $("textarea").prop('disabled', false);
        $("checkbox").prop('disabled', false);
    });

    $(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});

    $("#popAddTask").popover({
    html: true,
    content: function () {
        $(".popEnable").prop('disabled', false);
        return $("#popAddTaskCont").html();
    }}).on('shown.bs.popover', function () {
        $('#popDateTimeFrom').datepicker({dateFormat: 'yy-mm-dd'});
        $('#popDateTimeTo').datepicker({dateFormat: 'yy-mm-dd'});
    });

</script> 
@stop