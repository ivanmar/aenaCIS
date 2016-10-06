<div id="popAddCommCont" class="hide">
    {!! Form::hidden('idTicket', isset($obj->id)?$obj->id:null) !!}
    {!! Form::hidden('idUser', Auth::user()) !!}
    {!! Form::textarea('comment', null, array('rows'=>'3','class'=>'popEnable','id'=>'popText')) !!}

    <span class="btn btn-xs btn-primary" id="popSubmit"> Dodaj komentar</span>
        <script>
            $('#popSubmit').click(function (e) {
            e.preventDefault();
            var comment = $('#popText').val();
            $.ajax({type: "POST",
                url: "/aenaCIS/js/addcomment",
                data: { 
                    name: comment,
                    _token: $('input[name=_token]').val()
                },
                success:function(data)
                {
                }
            });

            $("#popAddComm").popover('hide');

            });
        </script>
</div>
