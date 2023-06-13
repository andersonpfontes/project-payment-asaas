$(document).ready(function(){

    $("#cpf").mask("999.999.999-99");
    $("#phone").mask("(99) 99999-9999");

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

    $("#send-payment").click(function(event){
        event.preventDefault();
        let fullname = $("input[name=fullname]").val();
        let _token   = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: "/send-payment",
            data:{
                fullname:fullname,
                _token: _token
            },
            beforeSend: function () {
                $("#msgTransaction").html("Estamos registrando as informações");
                $(".alert-loader").addClass('show');
            },
            success: function(response){
                console.log(response);
                // if(status == "200"){
                //
                //     $(".alert-loader").addClass('alert-success');
                //     $("#msgTransaction").html(msg['message']);
                //     setTimeout(function(){ $(".alert-loader").addClass('hide'); }, 3000);
                //     document.getElementById('new_customer').reset();
                // }
                // else {
                //     $(".alert-loader").addClass('alert-danger');
                //     $("#msgTransaction").html(msg['message']);
                //     setTimeout(function(){ $(".alert-loader").addClass('hide'); }, 3000);
                // }
            }
        });
    });

    $("#cancel-payment").click(function(){
        window.location.reload();
    });

});
