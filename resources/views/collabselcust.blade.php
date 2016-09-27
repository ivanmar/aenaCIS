
    {!! Form::text('nameCustomer', isset($obj->customer->name)?$obj->customer->name:null,array('id'=>'selCust','class' => 'form-control input-sm')) !!}
    {!! Form::hidden('idCustomer', isset($obj->customer->id)?$obj->customer->id:null,array('id'=>'idCustomer')) !!}
    <script>
        $('#selCust').autocomplete({
            minLength: 2,
            source: function (request, respond) {
                $.get('/js/getcustlist/' + request.term, function (data) { respond(data);} );
            },
            select: function (event, ui) {
                $( this ).val( ui.item.label );
                $("input[id*=idCustomer]").val(ui.item.value).trigger('change');
                return false;
            },
            change: function (event, ui) {
                if(!ui.item) {
                    $( this ).val('');
                }
            }
        });
    </script>
