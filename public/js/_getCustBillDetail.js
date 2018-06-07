    function billDetail(bid) {
        var url = '/js/getbilldetail/' + bid;
        $.getJSON( url, function(data) {
                    $('#serName').html(data[0].name);
                    $('#sBill').html(data[0].sBill);
                    $('#qty').html(data[0].qty);
                    $('#price').html(data[0].priceWoTax);
                }
        );
    }
    $('#idCust').change(function() {
        var url = '/js/getcustbill/' + 'mchemic' + '/' + $(this).val();
        $.getJSON( url, function(jsonData) {
            var options = '';
            $.each(jsonData, function(i,data)
            {
               options +='<option value="'+data.id+'">'+data.sBill+'</option>';
             });
            $('#idBill').html(options);
            
            billDetail(jsonData[0].id);
        });
    });

    $('#idBill').change( function() {
        billDetail($(this).val());
    });