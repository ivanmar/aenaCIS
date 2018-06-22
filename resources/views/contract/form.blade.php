@extends('master')

@section('content')

{!! HTML::ul($errors->all()) !!}

{!! Form::open(array('route'=>array($formAction,$obj->id),'method'=>$formMethod,'files' => true,'class'=>'form-horizontal','autocomplete'=>'off')) !!}

    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"> POGODBE</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-2">
                        <span class="cFieldName">Podjetje</span>
                        {!! Form::select('idCompany', $company, $obj->idCompany, array('class' => 'form-control selChose','id'=>'idCompany')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">Naziv</span>
                         {!! Form::text('name', $obj->name, array('class' => 'form-control input-sm')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <span class="cFieldName">Datum začetka</span>
                         {!! Form::text('dateStart', (isset($obj->dateStart) ? $obj->dateStart : date('Y-m-d')), array('class' => 'form-control input-sm dateSel')) !!}
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        <span class="cFieldName">Datum konca</span>
                         {!! Form::text('dateEnd', $obj->dateEnd, array('class' => 'form-control input-sm dateSel')) !!}
                    </div>
                </div>
                <div class="form-group">
                    
                    <div class="col-md-2">
                    <span class="btn btn-info btn-file btn-sm"> izberite datoteke <input name="file[]" type="file" title="" multiple> </span>
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        @if(isset($files))
                            @foreach ($files as $ind => $file)
                                <a href="/upload/contract/{!!$file->nameEnc !!}" target="_blank"> <i class="fa fa-download"></i> </a> &nbsp;&nbsp;
                                <a href="{!! URL::to('contract/delfile/'.$file->id) !!}"> <i class="fa fa-remove"></i> </a>&nbsp;&nbsp;
                                <span class="small"> {!!$file->nameOrig.'.'.$file->fileExt !!}</span>
                                <br>
                            @endforeach
                        @endif
                    </div>

                    <div class="col-md-5 col-md-offset-2">
                        <span class="cFieldName">Opis</span>
                        {!! Form::textarea('desc', $obj->desc, array('class' => 'form-control','rows'=>'5','id'=>'desc')) !!}
                    </div>
                </div>
                
                <hr>

    <input class="btn btn-md btn-success pull-right" type="submit" value="Submit">
    <a class="btn btn-sm btn-danger pull-left" href="{!!URL::to('contract')!!}">Cancel</a>
</div>
</div>
</div>
{!! Form::close() !!}

<script>
    $(".dateSel").datepicker({dateFormat: 'yy-mm-dd'});
    $('select.selChose').chosen({allow_single_deselect: true});

</script>
    
@stop
