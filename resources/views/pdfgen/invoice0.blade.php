<html>
    <head>
        <title>Ponudba</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {!! HTML::style('css/pdfgen.css') !!}
    </head>
    <body>
        <script type="text/php">
            if ( isset($pdf) ) {
            $font = Font_Metrics::get_font("helvetica", "bold");
            $pdf->page_text(70, 15, "Strana {PAGE_NUM} od {PAGE_COUNT}", $font, 6, array(0,0,0));
            }
        </script>
        <div class="page last">


            <table class="front">
                <tr><td colspan="2"> <img src="{!!base_path('public/img/real-logo.gif')!!}" width="300"></td></tr>
                <tr><td colspan="2"> <h1 class="txt-center"> PONUDBA {!!$sBill!!} </h1></td> </tr>
                <tr><td class="name-row"> Ponudba za </td>  <td> {!!$customer!!}</td> </tr>
                <tr><td class="name-row"> Naslov </td> <td> {!!$address!!} </td> </tr>
                <tr><td class="name-row"> DDV št </td> <td> {!!$sTax!!} </td> </tr>
            </table>
            
            <p class="mar20"></p>
            
            <table class="front">

                <tr class="txt-center">
                    <td><strong>št</strong></td>
                    <td><strong>storitev</strong></td>
                    <td><strong>kol</strong></td>
                    <td><strong>cena enote</strong></td>
                    <td><strong>rabat</strong></td>
                    <td><strong>vrednost</strong></td>
                </tr>
                <?php
                $totPriceQty = 0;
                $totPriceQtyDis = 0;
                $totPriceQtyDisTax = 0;
                $totTax = 0;
                ?>
                @foreach($items as $key => $value)
                <tr>
                    <td class="txt-center">{!!$key +1!!}</td>
                    <td class="txt-left">{!!$value->name!!}</td>
                    <?php
                    $priceQty = $value->price * $value->qty * (1 - $value->rDiscount / 100);
                    $totPriceQty += $priceQty;
                    $totPriceQtyDis += $priceQty * (1 - $rDiscount / 100);
                    $totTax += ($priceQty * (1 - $rDiscount / 100)) * ($value->rTax / 100);
                    $totPriceQtyDisTax += ($priceQty * (1 - $rDiscount / 100)) * (1 + $value->rTax / 100);
                    ?>
                    <td class="txt-center">{!!$value->qty!!}</td>
                    <td class="txt-right">{!!number_format($value->price, 2,',','')!!}</td>
                    <td class="txt-right">{!!number_format($value->rDiscount, 2,',','')!!}</td>
                    <td class="txt-right">{!!number_format($priceQty, 2,',','')!!}</td>
                </tr>
                @endforeach

                <tr>
                    <td colspan='5'  style="text-align: right;">Skupaj</td>
                    <td colspan='1'   style="text-align: right;"> {!! number_format($totPriceQty,2,',','')!!} </td>
                </tr>
                <tr>
                    <td colspan='5' style="text-align: right;">Rabat {!!$rDiscount . '%'!!}</td>
                    <td colspan='1' style="text-align: right;"> {!! number_format($totPriceQtyDis,2,',','') !!} </td>
                </tr>
                <tr>
                    <td colspan='5'  style="text-align: right;">Osnova za DDV(22%)</td>
                    <td colspan='1'  style="text-align: right;">{!!number_format($totPriceQtyDis,2,',','')!!}</td>
                </tr>
                <tr class="txt-right">
                    <td colspan='5'  style="text-align: right;">Znesek DDV</td>
                    <td colspan='1'  style="text-align: right;">{!!number_format($totTax,2,',','')!!}</td>
                </tr>
                <tr class="brd-right">
                    <td colspan='5'  style="text-align: right;">Skupaj z DDV</td>
                    <td colspan='1'  style="text-align: right;"> <strong> {!! number_format($totPriceQtyDisTax,2,',','') !!} </strong></td>
                </tr>

            </table>
            <p class="txt-small">Prosimo, da zgornji znesek nakažete do datuma valute na naš poslovni račun št: 02045-0090050141 <br>
                    identifikacijska št. za DDV: SI73707295 </p>

            <div id="pbottom">
                
                <p class="right">
                    ___________________________<br><br>
                    Aleksander Remškar dipl. var. inž.
                </p>
            </div>

        </div>
    </body>
</html>