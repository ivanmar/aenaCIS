<html>
    <head>
        <title>Račun</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {!! HTML::style('public/css/pdfgen.css') !!}
    </head>
    <body>
        <div class="page last">
            <table class="front">
                <tr>
                    <td colspan="3" class="txt-xsmall"> A1 INFORMATIKA D.O.O. <br> DŠ: 88334244 <br> Naslov: Preglov trg 2, Ljubljana <br> TRR: SI56 1010 0005 3116 904 pri banki Koper</td>
                    <td> <img  class='float-right' src="{!!base_path('public/img/a1_logo.png')!!}" width="70"></td>
                </tr>
                <tr><td colspan="4"> <h1 class="txt-center"> RAČUN {!!$nrInvoice!!} </h1></td> </tr>
                <tr><td class="txt-small"><b> Kupec </b> </td>  <td class="txt-small"> <b> {!!$company or 'končni kupec'!!} </b></td>
                    <td class="txt-small" colspan="2"> <p class="txt-right">Datum : {!!$dateIssue!!}</p> </td> 
                </tr>
                <tr><td class="txt-small"> Naslov </td> <td class="txt-small"> {!!$address or ''!!} </td>
                    <td class="txt-small" colspan="2"><p class="txt-right"> Kraj : Ljubljana </p> </td> 
                </tr>
                <tr><td class="txt-small"> Sedež </td> <td class="txt-small"> {!!$zipCode or ''!!} &nbsp; {!!$city or ''!!} </td>
                    <td class="txt-small" colspan="2"> <p class="txt-right"> Rok plačila : {!!$dateDue or ''!!} </p> </td> 
                </tr>
                <tr><td class="txt-small"> DDV št </td> <td class="txt-small"> {!!$ddvCode or ''!!} </td> 
                    <td class="txt-small" colspan="2">  </td> 
                </tr>
            </table>
            
            <p class="h60"></p>
            
            <table class="article0">
                <tr>
                    <th><strong>št</strong></th>
                    <th><strong>artikel</strong></th>
                    <th><strong>kol</strong></th>
                    <th><strong>cena enote</strong></th>
                    <th><strong>vrednost</strong></th>
                </tr>
                <?php
                $totPriceQty = 0;
                $totPriceQtyDis = 0;
                ?>
                @foreach($items as $key => $value)
                <tr>
                    <td>{!!$key +1!!}</td>
                    @if($value->idProduct > 0)
                    <?php $nameArt = DB::table('product')->where('id',$value->idProduct)->value('name'); ?>
                    <td>{!!$nameArt!!}</td>
                    @elseif($value->idService > 0)
                    <?php $nameArt = DB::table('service')->where('id',$value->idService)->value('name'); ?>
                    <td>{!!$nameArt!!}</td>
                    @endif
                    <?php
                    $totPriceQty += $value->priceUnit * $value->qty;
                    ?>
                    <td class="txt-center">{!!$value->qty!!}</td>
                    <td class="txt-right">{!!number_format($value->priceUnit, 2,',','')!!}</td>
                    <td class="txt-right">{!!number_format($value->priceUnit * $value->qty, 2,',','')!!}</td>
                </tr>
                @endforeach

                <tr>
                    <td class="txt-right" colspan="4"><b>Skupaj : (EUR)</b> </td>
                    <td class="txt-right"><b>{!! number_format($totPriceQty,2,',','')!!}</b> </td>
                </tr>

            </table>
            
            <p class="txt-italic txt-xsmall txt-right">
                * DDV ni obračunan na podlagi 1. odst. 94. člena ZDDV-1 (nismo DDV zavezanci)
            </p>
            <p class="h60"></p>
            <p class="h60"></p>
            <p>Ivan Primorac dipl. inž. ______________________</p>

        </div>
    </body>
</html>