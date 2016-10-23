<html>
    <head>
        <title>DELOVNI NALOG</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {!! HTML::style('css/pdfgen.css') !!}
    </head>
    <body>
        <div class="page"> 
            <table class="front">
                <tr><td colspan="7"><img src="{!!base_path('public/img/a1_logo.png')!!}" width="70"></td>

                    <td colspan="3" class="top-date">
                        <p><span class="name-row"> Datum poročila: </span> <span class="itxt"> {!! date('d-m-Y') !!} </span></p>
                        <p><span class="name-row"> Št. poročila: </span> <span class="itxt"> {!! 'tck-'. $id !!} </span></p>
                    </td>
                </tr>
                <tr><td colspan="10" id="brd-no"><h1 class="txt-center"> DELOVNI NALOG </h1></td> </tr>
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
                <tr><td colspan="5" class="name-row"> Opravljeno delo</td> 
                    <td colspan="5"> {!! $ticketRes !!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Dodatna oprema</td> 
                    <td colspan="5"> </td> 
                </tr>
                <tr><td colspan="10" class="desc"> <p class="name-row"> Opombe</p>
                        {!!$note!!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Opravil / Datum</td> 
                    <td colspan="5"> {!! $uname !!} / {!! date('d-m-Y',strtotime($dateClose)) !!} </td> 
                </tr>
            </table>
        </div>
    </body>
</html>