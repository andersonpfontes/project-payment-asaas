$(document).ready(function(){

    //chama a função e gera um cpf pra teste
    $("#cpfCnpj").val(gerarCpf());

    $("#cpfCnpj").mask("999.999.999-99");
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
        let cpfCnpj = $("#cpfCnpj").val();
        let email = $("#email").val();
        let phone = $("#phone").val();
        let birthday = $("#birthday").val();
        let value = $("#value").val();
        let addressNumber = $("#addressNumber").val();
        let postalCode = $("#postalCode").val();
        let holderName = $("#name").val();
        let number = $("#cardnumber").val();
        let expirationdate = $("#expirationdate").val();
        let ccv = $("#securitycode").val();
        let _token   = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: "send-payment",
            data:{
                payment_methods:payment_methods,
                fullname:fullname,
                cpfCnpj:cpfCnpj,
                email:email,
                phone:phone,
                birthday:birthday,
                value:value,
                addressNumber:addressNumber,
                postalCode:postalCode,
                creditCard:{
                    holderName: holderName,
                    number: number,
                    expirationdate: expirationdate,
                    ccv: ccv
                },
                _token: _token
            },
            beforeSend: function () {
                $("#msgTransaction").html("Estamos registrando as informações");
                $(".alert-loader").addClass('show');
            },
            success: function(response){
                console.log(response);
                    $('#titleModal').text(response.data.title);
                    // $("#ajaxform")[0].reset();
                    if(payment_methods === "bankslip"){
                        $("#result").append('<a href="'+response.data.bankSlipUrl+'" target="_blank" class="btn btn-outline-primary"><i class="fa fa-file" aria-hidden="true"></i> Visualizar Boleto</a>');
                    }else if(payment_methods === "pix"){
                        $("#result").append(response.data.encodedImage + '<br><a href="'+response.data.invoiceUrl+'" target="_blank" class="btn btn-outline-primary"><i class="fa fa-file" aria-hidden="true"></i> Problema com QRCODE? Clique AQUI</a>');
                    }else{
                        $("#result").append('<p><h3>'+response.data.status+'</h3></p>' +
                                            '<a href="'+response.data.transactionReceiptUrl+'" target="_blank" class="btn btn-outline-primary"><i class="fa fa-file" aria-hidden="true"></i> Comprovante de pagamento</a> ' +
                                            '<a href="'+response.data.invoiceUrl+'" target="_blank" class="btn btn-outline-success"><i class="fa fa-file-o" aria-hidden="true"></i> Detalhes do pagamento</a>'
                        );
                    }
                    $("#modalResult").modal("show");
            },
            error: function(response) {
                console.log(response);
                $('#fullnameError').text(response.responseJSON.errors.fullname);
                $('#emailError').text(response.responseJSON.errors.email);
                $('#phoneError').text(response.responseJSON.errors.phone);
                $('#cpfCnpjError').text(response.responseJSON.errors.cpfCnpj);
                $('#birthdayError').text(response.responseJSON.errors.birthday);
            }
        });
    });

    $("#cancel-payment").click(function(){
        window.location.reload();
    });

});
