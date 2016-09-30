@extends('master')

@section('content')

<div class="row">
    <div class="col-md-6">
        <h5 class="well"> KOLEDAR</h5>
        <div class="responsGoogleObj">
            <iframe src="https://calendar.google.com/calendar/embed?title=OVR%20DELOVNI%20KOLEDAR&amp;showPrint=0&amp;showCalendars=0&amp;showTz=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=real1.vzd%40gmail.com&amp;color=%231B887A&amp;ctz=Europe%2FBelgrade" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
        </div>
    </div>
    <div class="col-md-6">
        <h5 class="well"> POTEKLE MERITVE </h5>
        <table class="table table-bordered small">
            <thead>
                <tr>
                    <td>stranka</td>
                    <td width="30">status</td>
                    <td>tip</td>
                    <td>narejeno</td>
                    <td>poteka</td>
                </tr>
            </thead>
            <tbody>

            @foreach($objTable as $key => $value)
                <tr title="{!! $value->desc !!}">
                    <td>{!! $value->cname !!} </td>
                    <td class="cStat-{!!$statusMes[$key][$value->id]!!}"> </td>
                    <td>{!! $value->typeMeasure !!}</td>
                    <td>{!! $value->dateTest !!}</td>
                    <td>{!! date('Y-m-d', strtotime($value->dateTest.' +'.$value->mntValid.' Month')) !!} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop




