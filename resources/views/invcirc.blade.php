@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'class'=>'form-horizontal','autocomplete'=>'off')) !!}
<div class="row">
    <div class="col-md-11">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> PONAVLJAJOČI RAČUNI</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">Naziv</span>
                        {!! Form::text('name', $obj->name, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">Pogodba</span>
                        {!! Form::select('idContract', $contracts, $obj->idContract, array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <span class="cFieldName">Datum začetka</span>
                         {!! Form::text('dateStart', (isset($obj->dateStart) ? $obj->dateStart : date('Y-m-d')), array('class' => 'form-control input-sm dateSel')) !!}
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">Cron sintaksa (1 Month, 1 Year)</span>
                         {!! Form::text('circSyntax', $obj->circSyntax, array('class' => 'form-control')) !!}
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
    <a class="btn btn-sm btn-danger pull-left" href="{!!URL::to('invcirc')!!}">Cancel</a>
</div>
</div>
</div>
</div>
{!! Form::close() !!}


    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>ID</td>
                <td>naziv</td>
                <td>pogodba</td>
                <td>datum začetka</td>
                <td>sintaksa</td>
                <td width='70'>akcije</td>
            </tr>
        </thead>
        <tbody>
            @foreach($objectlist as $key => $value)
            <tr>
                <td>{!! $value->id !!}</td>
                <td>{!! $value->name !!}</td>
                <td>{!! $value->idContract !!}</td>
                <td>{!! $value->dateStart !!}</td>
                <td>{!! $value->circSyntax !!}</td>

                <td  class="text-right">
                    <a class="btn btn-xs btn-info" href="{!! URL::to('invcirc/' . $value->id . '/edit') !!}">E</a>
                    <a class="btn btn-xs btn-danger" href="{!! URL::to('invcirc/' . $value->id . '/destroy') !!}"
                       onclick="if (!confirm('Delete this item?')) { return false; };">D</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


<script>
    $(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});
    $('select.selChose').chosen({allow_single_deselect: true});
</script>
    
@stop
