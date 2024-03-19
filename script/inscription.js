function validateForm() {             
    var captchaResponse = grecaptcha.getResponse();             
    while (captchaResponse == "") {                 
        document.getElementById("captchaError").innerHTML = "<h4>Veuillez compléter le reCAPTCHA.</h4>";
        return false;             
    }             
    return true; 
} 