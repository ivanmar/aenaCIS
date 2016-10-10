<div id="popAddEvenCont" class="hide">
    {!! Form::hidden('idTicket', isset($obj->id)?$obj->id:null) !!}
    {!! Form::text('name', null, array('class'=>'popEnable form-control','id'=>'popNameEven','placeholder'=>'Naziv')) !!}
    {!! Form::text('dateTimeFrom', null, array('class'=>'popEnable form-control','id'=>'popDateTimeFrom','placeholder'=>'Datum od')) !!}
    {!! Form::text('dateTimeTo', null, array('class'=>'popEnable form-control','id'=>'popDateTimeTo','placeholder'=>'Datum do')) !!}
    {!! Form::textarea('text', null, array('class'=>'popEnable form-control','id'=>'popTextEven','placeholder'=>'Text')) !!}
    <span class="btn btn-xs btn-primary" id="popSubmitEven"> Dodaj dogodek</span>
        <script>
            $('#popSubmitEven').click(function (e) {
                e.preventDefault();
                $.ajax({type: "POST",
                    url: "/js/addevent",
                    data: { 
                        name: $('#popNameEven').val(),
                        text: $('#popTextEven').val(),
                        dateTimeFrom: $('#popDateTimeFrom').val(),
                        dateTimeTo: $('#popDateTimeTo').val(),
                        idTicket: $('input[name=idTicket]').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success:function(data)
                    {
                    }
                });
                $("#popAddEven").popover('hide');
            });
        </script>
</div>
