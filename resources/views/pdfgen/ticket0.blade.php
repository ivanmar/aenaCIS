<html>
    <head>
        <title>DELOVNI NALOG</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link media="all" type="text/css" rel="stylesheet" href="css/pdfgen-min.css">
        <style>
table.front {border: 0; font-size: 11px; letter-spacing: 2px; line-height: 20px;}
table.front td{ text-align: left; border: solid 1px #888; padding: 10px;}
table.front .desc {height: 150px;}
table.front td.top-date p {margin: 10px 0; display: block;}
table.front td.top-date span.itxt {float:right; display: inline-block;}
table .name-row {color: #003366; font-weight: 600;}

#brd-no0 {border: 0;}
#brd-no1 {border: 0;}
#brd-no2 {border: 0;}
#brd-no3 {border: 0;}

        </style>
    </head>
    <body class="pad40">
        <div class="page"> 
            <table class="front">
                <tr><td colspan="1" id="brd-no0"> <img src="{!!base_path('public/img/a1_logo.png')!!}" width="70"> </td>
                    <td colspan="6" id="brd-no1"> 
                        <b>A1 INFORMATIKA D.O.O. </b><br>
                            <span class="txt-xsmall">30. Avgusta 4, 1260 Ljubljana-Polje<br>
                                  064 170003 / www.aena.si </span>
                    </td>

                    <td colspan="3" class="top-date" id="brd-no2">
                        <p> Datum poročila : <b>{!! date('d.m.Y') !!}</b>  </p>
                    </td>
                </tr>
                <tr><td colspan="10" id="brd-no3"><h1 class="txt-center"> DELOVNI NALOG ŠT: {!! date('Y').'-'.$id !!} </h1></td> </tr>
                <tr><td colspan="5" class="name-row"> Naročnik</td> 
                    <td colspan="5"> {!!$cname.'<br>'.$tel.'<br>'.$address!!}</td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Oprema </td> 
                    <td colspan="5"> {!!$name !!}</td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Datum prejema</td>
                    <td colspan="5">{!! date('d-m-Y',strtotime($dateOpen)) !!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Zahtevano delo</td> 
                    <td colspan="5"> {!! $ticketDesc !!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row decs"> Opravljeno delo</td> 
                    <td colspan="5" class="desc"> {!! $ticketRes !!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Dodatna oprema</td> 
                    <td colspan="5"> 
                        
                            @if(isset($indBag) && $indBag)
                            <p class="pad5"><b> &#9635; </b> Torba </p> 
                            @endif
                            @if(isset($indCharger) && $indCharger)
                            <p class="pad5"><b> &#9635; </b> Polnilec </p> 
                            @endif
                    </td> 
                </tr>
                <tr><td colspan="10" class="desc"> <p class="name-row"> Opombe</p>
                        {!!$note!!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Opravil / Datum</td> 
                    <td colspan="5"> {!! ucwords($uname) !!} / {!! date('d-m-Y') !!} </td> 
                </tr>
            </table>
            <p class="txt-italic txt-xsmall txt-right">
                * Veljajo splošni pogoji servisiranja, vključno se ogradimo od kakršnekoli posredne ali neposredne odgovornosti zaradi izgube podatkov.<br>
                * Jamstvo za opravljeno delo in vgrajeno opremo je 30 dni, če ni drugače definirano od proizvajalca opreme.<br>
                * Rok za prevzam opreme je 20 dni po koncu dela. Po poteku roka, ne jamčimo za opremo.
            </p>
            <p class="h60"></p>
            <p>Podpis klijenta: ______________________</p>
        </div>
    </body>
</html>