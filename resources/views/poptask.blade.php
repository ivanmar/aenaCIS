<div id="popAddTaskCont" class="hide">
    {!! Form::hidden('idTicket', isset($idTicket) ? $idTicket:null) !!}
    {!! Form::hidden('idProject', isset($idProject) ? $idProject:null) !!}
    {!! Form::text('name', null, array('class'=>'popEnable form-control','id'=>'popNameTask','placeholder'=>'Naziv')) !!}
    {!! Form::text('dateTimeFrom', null, array('class'=>'popEnable form-control','id'=>'popDateTimeFrom','placeholder'=>'Datum od')) !!}
    {!! Form::text('dateTimeTo', null, array('class'=>'popEnable form-control','id'=>'popDateTimeTo','placeholder'=>'Datum do')) !!}
    {!! Form::textarea('text', null, array('class'=>'popEnable form-control','id'=>'popTextTask','placeholder'=>'Text')) !!}
    <span class="btn btn-xs btn-primary" id="popSubmitTask"> Dodaj nalogo</span>
    <span class="btn btn-xs btn-warning pull-right" onclick="$('#popAddTask').popover('hide');"> Zapri</span>
        <script>
            $('#popSubmitTask').click(function (e) {
                e.preventDefault();
                $.ajax({type: "POST",
                    url: "/js/addtask",
                    data: { 
                        name: $('#popNameTask').val(),
                        text: $('#popTextTask').val(),
                        dateTimeFrom: $('#popDateTimeFrom').val(),
                        dateTimeTo: $('#popDateTimeTo').val(),
                        idTicket: $('input[name=idTicket]').val(),
                        idProject: $('input[name=idProject]').val(),
                        _token: $('input[name=_token]').val()
                    },
                    success:function(data)
                    {
                    }
                });
                $("#popAddTask").popover('hide');
            });
        </script>
</div>
