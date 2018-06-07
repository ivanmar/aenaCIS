@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

<div class="row">
    <div class="col-md-6 col-md-offset-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{!!$formTitle!!}</h3>
            </div>
            <div class="panel-body">
                {!! Form::open(array('route'=>array($formAction,$object->id),'method'=>$formMethod,'autocomplete'=>'off')) !!}
                <fieldset>
        @foreach($fields as $fkey => $fval)
            <div class="form-group">
                
            @if(is_array($fval))
            <span class="cFieldName">{!! $fkey !!}</span>
                 {!! Form::select($fkey, $fval, $object->{$fkey}, array('id'=>$fkey,'class'=>'form-control input-sm')) !!}
            @else
            <span class="cFieldName">{!! $fval !!}</span>
                @if($fkey == 'password')
                    {!! Form::text($fkey, null, array('id'=>$fkey,'class'=>'form-control input-sm')) !!}
                @else
                    {!! Form::text($fkey, $object->{$fkey}, array('id'=>$fkey,'class'=>'form-control input-sm')) !!}
                @endif
            @endif
            </div>
        @endforeach
                    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
                    <a style="display: {!!$displayCancel or 'none'!!}" class="btn btn-sm btn-danger pull-left" href="{!!URL::to($objectTitle)!!}">Cancel</a>
                </fieldset>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>ID</td>
                @foreach($fields as $fkey => $fval)
                  @if (is_array($fval))
                    <td>{!!$fkey!!}</td>
                  @elseif ($fkey != 'id' && $fkey != 'password')
                    <td>{!!$fval!!}</td>
                  @endif
                @endforeach
                <td width='70'>akcije</td>
            </tr>
        </thead>
        <tbody>
            @foreach($objectlist as $key => $value)
            <tr>
                <td>{!! $value->id !!}</td>
                @foreach($fields as $tkey => $tval)
                  @if ($tkey != 'id'  && $tkey != 'password')
                    <td>{!! $value->{$tkey} !!}</td>
                  @endif
                @endforeach

                <td  class="text-right">
                    <a class="btn btn-xs btn-info" href="{!! URL::to($objectTitle.'/' . $value->id . '/edit') !!}">E</a>
                    <a class="btn btn-xs btn-danger" href="{!! URL::to($objectTitle.'/' . $value->id . '/destroy') !!}"
                       onclick="if (!confirm('Delete this item?')) { return false; };">D</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
<script>
    $("#dateTest").datepicker({dateFormat: 'yy-mm-dd'});

</script> 
    @stop
