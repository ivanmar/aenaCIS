
    {!! Form::text('nameContact', isset($obj->contact->name)?$obj->contact->name:null,array('id'=>'selCont','class' => 'form-control input-sm')) !!}
    {!! Form::hidden('idContact', isset($obj->contact->id)?$obj->contact->id:null,array('id'=>'idContact')) !!}
    <script>
        $('#selCont').autocomplete({
            minLength: 2,
            source: function (request, respond) {
                $.get('/aenaCIS/js/getcontactlist/' + request.term, function (data) { respond(data);} );
            },
            select: function (event, ui) {
                $( this ).val( ui.item.label );
                $("input[id*=idContact]").val(ui.item.value).trigger('change');
                return false;
            },
            change: function (event, ui) {
                if(!ui.item) {
                    $( this ).val('');
                }
            }
        });
    </script>
