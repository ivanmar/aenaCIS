<html>
    <head>
        <title>Izvjesce Meritve</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link media="all" type="text/css" rel="stylesheet" href="css/pdfgen-min.css">
    </head>
    <body class="pad30">
        <div class="page"> 
            <table class="front">
                <tr><td colspan="7"><img src="{!!base_path('public/img/real-logo.gif')!!}" width="300"></td>

                    <td colspan="3" class="top-date">
                        <p><span class="name-row"> Datum poročila: </span> <span class="itxt"> {!! date('d-m-Y',strtotime($dateRep)) !!} </span></p>
                        <p><span class="name-row"> Št. poročila: </span> <span class="itxt"> {!! $sReport !!} </span></p>
                    </td>
                </tr>
                <tr><td colspan="10" id="brd-no"><h1 class="txt-center"> {!!$mTitle!!} </h1></td> </tr>
                <tr><td colspan="5" class="name-row"> Naročnik</td> 
                    <td colspan="5"> {!!$customer.'<br>'.$custAddr.'<br>'.$custCity!!}</td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Lokacija meritev </td> 
                    <td colspan="5"> {!!$objAddr.'<br>'.$objCity!!}</td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Datum izvedbe meritev</td>
                    <td colspan="5">{!! date('d-m-Y',strtotime($dateTest)) !!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Datum predhodnih meritev</td> 
                    <td colspan="5"> 
                        @if (!empty($dateLast))
                        {!! date('d-m-Y',strtotime($dateLast)) !!} 
                        @endif
                    </td> 
                </tr>
                @if(isset($mntValid))
                <tr><td colspan="5" class="name-row"> Veljavnost</td> 
                    <td colspan="5"> {!! $mntValid !!} mesecev </td> 
                </tr>
                @endif
                @if(isset($instrument))
                <tr><td colspan="5" class="name-row"> Podatki o merilniku </td> 
                    <td colspan="5"> 
                        @foreach ($instrument as $name => $cert)
                              {!! $name . ' / CRT :'. $cert.'<br>'!!}
                        @endforeach
                    </td> 
                </tr>
                @endif
                <tr><td colspan="10" class="desc"> <p class="name-row"> Opis in vrsta meritev</p>
                        {!!$desc!!} </td> 
                </tr>
                <tr><td colspan="5" class="name-row"> Meritve opravil</td> 
                    <td colspan="5"> Aleksander Remškar, dipl. var. inž. <br>
                        Strokovni izpit: 06-08/96 <br>
                        Dovoljenje ministrstva za delo: 10200-28/2014/5
                    </td> 
                </tr>
            </table>
        </div>

        @yield('content')

    </body>
</html>