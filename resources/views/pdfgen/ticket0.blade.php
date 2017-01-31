<html>
    <head>
        <title>DELOVNI NALOG</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link media="all" type="text/css" rel="stylesheet" href="css/pdfgen.css">
    </head>
    <body>
        <div class="page last"> 
            <table class="front">
                <tr><td colspan="2" id="brd-no0"> <img src="{!!base_path('public/img/a1_logo.png')!!}" width="70"> </td>
                    <td colspan="6" id="brd-no1"> 
                        <b>A1 INFORMATIKA D.O.O. </b><br>
                            <span class="txt-xsmall">Dolenjska cesta 242, 1000 Ljubljana<br>
                                  064 170003 / www.aena.si </span>
                    </td>

                    <td colspan="2" class="top-date" id="brd-no2">
                        <p><span class="name-row"> Datum poročila :</span> <span class="itxt"> {!! date('d-m-Y') !!} </span></p>
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
                    <td colspan="5"> {!! $uname !!} / {!! date('d-m-Y') !!} </td> 
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