@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> STORNO</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">Št. računa</span>
                        {!! Form::text('nrInvoice', $nrInvoice, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">Leto</span>
                        {!! Form::select('years', $years, $year, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Datum storna</span>
                         {!! Form::text('dateIssue', (isset($obj->dateIssue) ? $obj->dateIssue : date('Y-m-d')), array('class' => 'form-control input-sm dateSel')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        @if(isset($indEdit))
                        <span class="cFieldName">Št. storno</span>
                         {!! Form::text('nrStorno', $obj->nrStorno, array('class' => 'form-control input-sm')) !!}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">Opis</span>
                        {!! Form::textarea('desc', $obj->desc, array('class' => 'form-control','rows'=>'5','id'=>'desc')) !!}
                    </div>
                </div>
                
                <hr>

    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
    <a class="btn btn-sm btn-danger pull-left" href="{!!URL::to('storno')!!}">Cancel</a>
</div>
</div>
</div>
{!! Form::close() !!}

<script>
    $(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});
    $('select.selChose').chosen({allow_single_deselect: true});
</script>
    
@stop
