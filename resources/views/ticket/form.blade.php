@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'class'=>'form-horizontal','autocomplete'=>'off')) !!}

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"> Naloga </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> naziv *</span>
                    {!! Form::text('tname', $obj->name, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2'>
                <span class="cFieldName"> serijska št</span>
                    {!! Form::text('serialDevice', $obj->serialDevice, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2'>
                    <span class="cFieldName"> status</span>
                    {!! Form::select('status', $status, $obj->status, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-2 col-md-offset-1'>
                    <span class="btn btn-sm btn-primary pull-right" id="editForm"> Edit </span>
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
                    
@include('popcontact')              
                    
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
                    {!! Form::textarea('ticketDesc', $obj->ticketDesc, array('class' => 'form-control', 'rows' => '5')) !!}
                </div>
                <div class='col-md-1'>
                    <span class="cFieldName"> Torba</span>
                    {!! Form::checkbox('indBag', 1, $obj->indBag) !!}<br>
                </div>
                <div class='col-md-1'>
                    <span class="cFieldName"> Polnilec</span>
                    {!! Form::checkbox('indCharger', 1, $obj->indCharger) !!}<br>
                </div>
                <div class='col-md-5'>
                    <span class="cFieldName"> opomba</span>
                    {!! Form::textarea('note', $obj->note, array('class' => 'form-control', 'rows' => '5')) !!}
                </div>
            </div>
            <div class="form-group">
                <div class='col-md-5'>
                    <span class="cFieldName"> uporabljeni materijal</span>
                    {!! Form::textarea('partUsed', $obj->partUsed, array('class' => 'form-control', 'rows' => '5')) !!}
                </div>
                <div class='col-md-2'>
                <span class="cFieldName"> cena napovedana</span>
                    {!! Form::text('pricePredict', $obj->pricePredict, array('class' => 'form-control')) !!}
                </div>
                <div class='col-md-5'>
                    <span class="cFieldName"> opravljeno delo</span>
                    {!! Form::textarea('ticketRes', $obj->ticketRes, array('class' => 'form-control', 'rows' => '5')) !!}
                </div>
            </div>
            <div class="row">
                <div class='col-md-5'>
                    <span class="btn btn-sm btn-default" id="popAddComm"> Dodaj komentar</span>
                    <span class="btn btn-sm btn-default" id="popAddTask"> Dodaj nalogo</span>
                </div>
                <div class='col-md-2 col-md-offset-2'>
                    <a class="btn btn-sm btn-danger" href="{!!URL::to('ticket')!!}">Cancel</a> 
                    <a href="#" id="resetBtn" class="btn btn-sm btn-warning">Reset</a>
                </div>
                <div class='col-md-1 col-md-offset-2'>
                    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
                </div>
            </div>
            @include('popcomment')
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
    
    $("#popAddCont").popover({
    html: true,
    content: function () {
        return $("#popAddCustCont").html();
    }});
    $("#popAddComm").popover({
    html: true,
    content: function () {
        $(".popEnable").prop('disabled', false);
        return $("#popAddCommCont").html();
    }});

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

