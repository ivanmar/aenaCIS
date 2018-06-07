<!DOCTYPE html>
<html lang="sl-SI">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {!! HTML::style('css/bootstrap4.css') !!}
        {!! HTML::script('js/jquery.min.js') !!}
        {!! HTML::script('js/bootstrap4.bundle.min.js') !!}
        <title>{{ config('app.name', '') }}</title>
    </head>
    <body>

        <div class="container">


            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header text-right">aena CIS</div>
                    <div class="card-body">
                        <h5 class="card-title text-center">PRIJAVA</h5>
                        <div class="form-group text-right">
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-Mail Naslov</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Geslo</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary"> PRIJAVA </button>
                        </div>
                    </div>
                    <div class="card-footer text-muted"> A1 Informatika d.o.o. / 064 170003 / info@aena.si</div>
                </div>
            </form>
        </div>
    </body>
</html>