
"use strict";

$(function() {
  var paymentForm = document.querySelector(".checkout-cc-payment-form");

  if (paymentForm) {
    var card = new Card({
      form: paymentForm,
      container: ".card-wrapper",
      masks: {
        cardNumber: "•"
      }
    });
  }
});
