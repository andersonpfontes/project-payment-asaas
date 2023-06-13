$(document).ready(function(){

    //get value radio button for validation
    $("input[type='radio']").click(function(){
        var radioValue = $("input[name='payment_methods']:checked").val();
        if(radioValue === "credit-card"){
            $('.payment-area').show('slow');
        }else{
            $('.payment-area').hide('slow');
        }

        $('#send-payment').prop('disabled', false);
    });

    $("#send-payment").click(function(){

        var data = {};
        data['']        = $("#").val();
        data['']        = $("#").val();
        data['']        = $("#").val();
        data['']        = $("#").val();

        $.ajax({
            type: "POST",
            url: "caminho/controller",
            dataType: "json",
            data: JSON.stringify(data),
            beforeSend: function () {
                $("#msgTransaction").html("Estamos registrando as informações");
                $(".alert-loader").addClass('show');
            },
            success: function(msg, statusText, xhr){
                var status = xhr.status;//200
                var head = xhr.getAllResponseHeaders(); //Detalhe header info
                if(status == "200"){

                    $(".alert-loader").addClass('alert-success');
                    $("#msgTransaction").html(msg['message']);
                    setTimeout(function(){ $(".alert-loader").addClass('hide'); }, 3000);
                    document.getElementById('new_customer').reset();
                }
                else {
                    $(".alert-loader").addClass('alert-danger');
                    $("#msgTransaction").html(msg['message']);
                    setTimeout(function(){ $(".alert-loader").addClass('hide'); }, 3000);
                }
            }
        });
    });

    $("#cancel-payment").click(function(){
        window.location.reload();
    });

});
