<!DOCTYPE HTML>
<html>
    <head>
        <title>aenaCIS - A1 Informatika d.o.o.</title>
        {!! HTML::style('css/jquery-ui.min.css') !!}
        {!! HTML::style('css/bootstrap.css') !!}
        {!! HTML::style('css/font-awesome.min.css') !!}
        {!! HTML::style('css/bootstrap-cust.css') !!}
        {!! HTML::style('css/chosen.min.css') !!}

        {!! HTML::script('js/jquery.min.js') !!}
        {!! HTML::script('js/jquery-ui.min.js') !!}
        {!! HTML::script('js/bootstrap.min.js') !!}
        {!! HTML::script('js/datepicker-sl.js') !!}
        {!! HTML::script('js/chosen.jquery.min.js') !!}
    </head>
    <body>
        <div class="container-fluid">

            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{!! URL::to('/') !!}" title="aenaCIS info system"> <i class="fa fa-chrome collab-logo"></i> </a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="{!! $actTick or ''!!}"><a href="{!! URL::to('ticket') !!}">Naloge</a></li>
                        <li class="{!! $actSale or ''!!}"><a href="{!! URL::to('saleorder') !!}">Naročila</a></li>
                        <li class="dropdown {!! $actInvo or ''!!}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Računi <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="{!! URL::to('invoiceout') !!}">Računi oddaja</a></li>
                                <li><a href="{!! URL::to('invoicein') !!}">Računi prejem</a></li>
                                <li><a href="{!! URL::to('reclamation') !!}">Reklamacije</a></li>
                                <li><a href="{!! URL::to('storno') !!}">Storno</a></li>
                                <li><a href="{!! URL::to('invcirc') !!}">ponavljajoči rač.</a></li>
                            </ul>
                        </li>
                        <li class="{!! $actProj or ''!!}"><a href="{!! URL::to('project') !!}">Projekti</a></li>
                        
                        <li class="dropdown {!! $actComp or ''!!}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Podjetja <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="{!! URL::to('company/create') !!}">Dodaj podjetje</a></li>
                                <li><a href="{!! URL::to('company') !!}">Seznam podjet</a></li>
                                <li><a href="{!! URL::to('contract') !!}">Pogodbe</a></li>
                            </ul>
                        </li>
                        <li class="{!! $actCont or ''!!}"><a href="{!! URL::to('contact') !!}">Kontakti</a></li>
                        <li class="{!! $actKb or ''!!}">  <a href="{!! URL::to('kb') !!}">KB</a></li>
                        <li class="{!! $actProd or ''!!}">  <a href="{!! URL::to('product') !!}">Produkti</a></li>
                        <li class="dropdown {!! $actSett or ''!!}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Nastavitve <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="{!! URL::to('productgroup') !!}">Produkt grupe</a></li>
                                <li><a href="{!! URL::to('manufacturer') !!}">Proizvajalci</a></li>
                                <li><a href="{!! URL::to('service') !!}">Storitve</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-right navbar-nav nav">
                        <li><a href="#"> </a></li>
                        <li><a href="{!! URL::to('logout') !!}"> <i class="fa fa-power-off" style="color:orange"></i></a></li>
                    </ul>


                </div>
            </nav>

            @if (Session::has('message-err'))
            <div class="alert alert-danger">{!! Session::get('message-err') !!}</div>
            @elseif (Session::has('message'))
            <div class="alert alert-info">{!! Session::get('message') !!}</div>
            @endif
            
            @yield('content')
        </div>
    </body>
</html>
