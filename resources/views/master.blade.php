<!DOCTYPE HTML>
<html>
    <head>
        <title>COLLAB - A1 SERVIS</title>
        {!! HTML::style('css/jquery-ui.min.css') !!}
        {!! HTML::style('css/bootstrap.min.css') !!}
        {!! HTML::style('css/font-awesome.min.css') !!}
        {!! HTML::style('css/bootstrap-custom.css') !!}

        {!! HTML::script('js/jquery.min.js') !!}
        {!! HTML::script('js/jquery-ui.min.js') !!}
        {!! HTML::script('js/bootstrap.min.js') !!}
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
                    <a class="navbar-brand" href="{!! URL::to('/') !!}" title="OVR varnosne meritve"> <i class="fa fa-chrome collab-logo"></i> </a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class=" {!! $actOrd or ''!!}"><a href="{!! URL::to('order') !!}">Naročil</a></li>

                        <li class="dropdown {!! $actCus or ''!!}">
                            <a href="{!! URL::to('customer') !!}">Stranke</a>
                        </li>
                    </ul>
                    <ul class="navbar-right navbar-nav nav">
                        <li><a href="#"> </a></li>
                        <li><a href="#"> <i class="fa fa-power-off" style="color:orange"></i></a></li>
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
