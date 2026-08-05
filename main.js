document.addEventListener("DOMContentLoaded", function () {
    const treatType = document.getElementById("treat-type");
    const cakeGroup = document.getElementById("cake-options-group");
    const cookieGroup = document.getElementById("cookie-options-group");
    const cakeSize = document.getElementById("cake-size");
    const cookieAmount = document.getElementById("cookie-amount");
    const quoteDisplay = document.getElementById("quote-price");
    const priceInput = document.getElementById("calculated-price-input");
    const optionInput = document.getElementById("final-option-input");

    if (treatType && quoteDisplay) {
        function updateCustomForm() {
            let price = 0;
            let optionText = "";

            if (treatType.value === "cake") {
                // show cake options, hide cookie options
                cakeGroup.style.display = "block";
                cookieGroup.style.display = "none";

                if (cakeSize.value === "6-inch") {
                    price = 30.00;
                    optionText = "Custom Cake (6 inch)";
                } else {
                    price = 45.00;
                    optionText = "Custom Cake (8 inch)";
                }
            } else {
                // show cookie options, hide cake options
                cakeGroup.style.display = "none";
                cookieGroup.style.display = "block";

                if (cookieAmount.value === "1-dozen") {
                    price = 20.00;
                    optionText = "Custom Cookies (1 Dozen)";
                } else {
                    price = 35.00;
                    optionText = "Custom Cookies (2 Dozen)";
                }
            }

            // update UI & hidden form values
            quoteDisplay.textContent = price.toFixed(2);
            if (priceInput) priceInput.value = price.toFixed(2);
            if (optionInput) optionInput.value = optionText;
        }

        // attach listeners for dynamic updates
        treatType.addEventListener("change", updateCustomForm);
        cakeSize.addEventListener("change", updateCustomForm);
        cookieAmount.addEventListener("change", updateCustomForm);
    }
});