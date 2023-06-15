$(document).ready(function(){

    //chama a função e gera um cpf pra teste
    $("#cpf").val(gerarCpf());

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
        let payment_methods = $("input[name='payment_methods']:checked").val();
        let fullname = $("#full_name").val();
        let cpf = $("#cpf").val();
        let email = $("#email").val();
        let phone = $("#phone").val();
        let birthday = $("#birthday").val();
        let _token   = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: "/send-payment",
            data:{
                payment_methods:payment_methods,
                fullname:fullname,
                cpf:cpf,
                email:email,
                phone:phone,
                birthday:birthday,
                _token: _token
            },
            beforeSend: function () {
                $("#msgTransaction").html("Estamos registrando as informações");
                $(".alert-loader").addClass('show');
            },
            success: function(response){
                console.log(response);
                if(response) {
                    $('.success').text(response.success);
                    $("#ajaxform")[0].reset();
                }
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
            },
            error: function(response) {
                console.log(response);
                $('#fullnameError').text(response.responseJSON.errors.fullname);
                $('#emailError').text(response.responseJSON.errors.email);
                $('#phoneError').text(response.responseJSON.errors.phone);
                $('#cpfError').text(response.responseJSON.errors.cpf);
                $('#birthdayError').text(response.responseJSON.errors.birthday);
            }
        });
    });

    $("#cancel-payment").click(function(){
        window.location.reload();
    });

});
