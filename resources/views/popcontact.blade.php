<div id="popAddCustCont" class="hide">
    <input type='text' name="hid_name" placeholder="ime in primek" id="popName">
    {!! Form::select('hid_idCompany', $compList, null, array('id' => 'popIdCompany')) !!}
    <input type='text' name="hid_tel" placeholder="Telefon" id="popTel">
    <input type='text' name="hid_email" placeholder="Email" id="popEmail">
    <input type='text' name="hid_address" placeholder="Naslov" id="popAddress">
    <input type='text' name="hid_city" placeholder="Mesto" id="popCity">
    <input type='text' name="hid_zipCode" placeholder="ZIP" id="popZipCode">

    <span class="btn btn-xs btn-primary" id="popSubmitCont"> Dodaj</span>
    <span class="btn btn-xs btn-warning pull-right" onclick="$('#popAddCont').popover('hide');"> Zapri</span>
    <script>
        $('#popSubmitCont').click(function (e) {
            e.preventDefault();
            var nameCont = $('#popName').val();
            $.ajax({type: "POST",
                url: "/js/addcontact",
                data: {
                    name: nameCont,
                    idCompany: $('#popIdCompany').val(),
                    tel: $('#popTel').val(),
                    email: $('#popEmail').val(),
                    address: $('#popAddress').val(),
                    city: $('#popCity').val(),
                    zipCode: $('#popZipCode').val(),
                    _token: $('input[name=_token]').val()
                },
                success: function (data)
                {
                    $('#idContact').val(data);
                    $('#selCont').val(nameCont);
                }
            });

            $("#popAddCont").popover('hide');

        });
    </script>
</div>

