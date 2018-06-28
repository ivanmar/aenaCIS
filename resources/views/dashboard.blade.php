@extends('master')

@section('content')

<div class="row">
    <div class="col-md-6">
    <h5 class="well"> RAČUNI ZA IZDATI </h5>
        <table class="table table-bordered small">
            <thead>
                <tr>
                    <td>stranka</td>
                    <td>circ name</td>
                    <td>zadnji datum</td>
                    <td>sintaksa</td>
                    <td>ustvari račun</td>
                </tr>
            </thead>
            <tbody>
    @if(isset($dataInv))
        @foreach($dataInv as $key => $val)
                <tr>
                <td>{!! $val->cname !!}</td>
                <td>{!! $val->circname !!}</td>
                <td>{!! $val->dateIssue !!}</td>
                <td>{!! $val->circSyntax !!}</td>
                <td> 
                    <a href="{!! URL::to('invoiceout/copy/' . $val->id ) !!}" title="Kopiraj"><i class="fa fa-clone"></i></a>
                </td>
                </tr>
        @endforeach
    @endif
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        
    </div>
</div>

@stop




