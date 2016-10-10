<div id="popAddCommCont" class="hide">
    {!! Form::hidden('idTicket', isset($obj->id)?$obj->id:null) !!}
    {!! Form::textarea('comment', null, array('class'=>'popEnable form-control input-xs','id'=>'popText')) !!}
    <span class="btn btn-xs btn-primary" id="popSubmitComm"> Dodaj komentar</span>
        <script>
            $('#popSubmitComm').click(function (e) {
                e.preventDefault();
                var comment = $('#popText').val();
                $.ajax({type: "POST",
                    url: "/js/addcomment",
                    data: { 
                        name: comment,
                        idTicket: $('input[name=idTicket]').val(),
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
