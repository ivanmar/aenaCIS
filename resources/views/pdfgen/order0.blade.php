<html>
    <head>
        <title>Delovni Nalog</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {!! HTML::style('css/pdfgen.css') !!}
    </head>
    <body>
        <div>
            <div class="page">
                
                <table class="front">
                <tr><td colspan="7"><img src="{!!base_path('public/img/real-logo.gif')!!}" width="300"></td>

                    <td colspan="3" class="top-date">
                        <p><span class="name-row"> Datum poročila: </span> <span class="itxt"> {!! date('d-m-Y') !!} </span></p>
                    </td>
                </tr>
                <tr><td colspan="10" id="brd-no">
                        <h1 class="txt-center"> DELOVNI NALOG </h1>
                    </td>
                </tr>
                <tr><td colspan="5" class="name-row"> Naročnik </td> 
                    <td colspan="5"> {!!$item->cname!!}</td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Lokacija izvedbe </td> 
                    <td colspan="5"> {!!$item->locaddress.'<br>'.$item->loccity!!}</td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Datum začetka izvedbe</td>
                    <td colspan="5">{!! date('d-m-Y',strtotime($item->dateStart)) !!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Datum konca izvedbe</td>
                    <td colspan="5">
                        @if (!empty($item->dateFinish))
                        {!! date('d-m-Y',strtotime($item->dateFinish)) !!}
                        @else
                        {!! date('d-m-Y',strtotime($item->dateStart)) !!}
                        @endif
                    </td> 
                </tr>
                @if(!empty($service))
                <tr><td colspan="10" class="name-row"> Opravljene storitve </td>  </tr>
                
                     
                    @foreach ($service as $skey => $sval)
                    <tr><td colspan="10">{!! $sval->sername !!} ( {!! $sval->qty !!} ) </td></tr>
                    @endforeach
                @endif
                <tr><td colspan="10" class="desc"> <p class="name-row"> Opis in vrsta meritev</p>
                        {!!$item->desc!!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Podpis naročnika</td> 
                    <td colspan="5" class="name-row"> Delo opravil</td> 
                </tr>
                <tr><td colspan="5"> </td> 
                    <td colspan="5"> Aleksander Remškar, dipl. var. inž. <br><br></td> 
                </tr>
            </table>
            </div>
        </div>
    </body>
</html>