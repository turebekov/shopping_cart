/*var stripe = Stripe('pk_test_TMMdtoELapSoZcHNL5kcJgph');

var $form = $('#checkout-form');

$form.submit(function (event) {
    $('#charge-error').addClass('hidden',true);
    $form.find('button').prop('disabled',true);
    Stripe.card.createToken({
        number: $('#card-number').val(),
        cvc: $('#card-cvc').val(),
        exp_month: $('#card-expiry-month').val(),
        exp_year: $('#card-expiry-year').val(),
        name: $('#card-name').val()
    },stripeResponseHandler);
    return false;
});

function  stripeREsponseHandler(status, response) {
    if (response.error){
        $('#charge-error').removeClass('hidden');
        $('#charge-error').text(response.error.message);
        $form.find('button').prop('disabled',false);
    }
    else{
        var token = response.id;
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));

        $form.get(0).submit();
    }
}*/
Stripe.setPublishableKey('pk_YOUR_PUBLISHABLE_KEY');
$(document).ready(function() {
    function addInputNames() {
        // Not ideal, but jQuery's validate plugin requires fields to have names
        // so we add them at the last possible minute, in case any javascript
        // exceptions have caused other parts of the script to fail.
        $(".card-number").attr("name", "card-number")
        $(".card-cvc").attr("name", "card-cvc")
        $(".card-expiry-year").attr("name", "card-expiry-year")
    }
    function removeInputNames() {
        $(".card-number").removeAttr("name")
        $(".card-cvc").removeAttr("name")
        $(".card-expiry-year").removeAttr("name")
    }
    function submit(form) {
        // remove the input field names for security
        // we do this *before* anything else which might throw an exception
        removeInputNames(); // THIS IS IMPORTANT!
        // given a valid form, submit the payment details to stripe
        $(form['submit-button']).attr("disabled", "disabled")
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, function(status, response) {
            if (response.error) {
                // re-enable the submit button
                $(form['submit-button']).removeAttr("disabled")

                // show the error
                $(".payment-errors").html(response.error.message);
                // we add these names back in so we can revalidate properly
                addInputNames();
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the stripe token
                var input = $("<input name='stripeToken' value='" + token + "' style='display:none;' />");
                form.appendChild(input[0])
                // and submit
                form.submit();
            }
        });

        return false;
    }

    // add custom rules for credit card validating
    jQuery.validator.addMethod("cardNumber", Stripe.validateCardNumber, "Please enter a valid card number");
    jQuery.validator.addMethod("cardCVC", Stripe.validateCVC, "Please enter a valid security code");
    jQuery.validator.addMethod("cardExpiry", function() {
        return Stripe.validateExpiry($(".card-expiry-month").val(),
            $(".card-expiry-year").val())
    }, "Please enter a valid expiration");
    // We use the jQuery validate plugin to validate required params on submit
    $("#checkout-form-form").validate({
        submitHandler: submit,
        rules: {
            "card-cvc" : {
                cardCVC: true,
                required: true
            },
            "card-number" : {
                cardNumber: true,
                required: true
            },
            "card-expiry-year" : "cardExpiry" // we don't validate month separately
        }
    });
    // adding the input field names is the last step, in case an earlier step errors
    addInputNames();
});