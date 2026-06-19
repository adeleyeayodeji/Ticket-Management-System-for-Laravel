$(document).ready(function () {
    /**
     * On submit
     * #ticketForm
     */
    $("#ticketForm").submit(function (e) {
        e.preventDefault();

        //form
        let form = $(this);

        // Get form data
        let serializedData = form.serialize();

        /**
         * Ajax
         */
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: serializedData,
            dataType: "json",
            beforeSend: function () {
                // Disable the submit button to prevent multiple submissions
                form.find('button[type="submit"]').prop("disabled", true);
            },
            success: function (response) {
                //enable the submit button
                form.find('button[type="submit"]').prop("disabled", false);
                //check if response is success
                if (response.success) {
                    // Show success message
                    alert(response.message);

                    // Reset the form
                    form[0].reset();
                } else {
                    // Show error message
                    alert(response.message);
                }
            },
            error: () => {
                //enable the submit button
                form.find('button[type="submit"]').prop("disabled", false);
                // Show generic error message
                alert(
                    "An error occurred while submitting the ticket. Please try again.",
                );
            },
        });
    });

    /**
     * registerForm
     *
     */
    $("#registerForm").submit(function (e) {
        e.preventDefault();

        //get the form
        let form = $(this);

        // Get form data
        let serializedData = form.serialize();

        /**
         * Ajax
         */
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: serializedData,
            dataType: "json",
            beforeSend: function () {
                // Disable the submit button to prevent multiple submissions
                form.find('button[type="submit"]').prop("disabled", true);
            },
            success: function (response) {
                //enable the submit button
                form.find('button[type="submit"]').prop("disabled", false);
                //check if response is success
                if (response.success) {
                    // Show success message
                    alert(response.message);
                    //redirect the user to the dashboard
                    window.location.href = response.redirect;
                    // Reset the form
                    form[0].reset();
                } else {
                    // Show error message
                    alert(response.message);
                }
            },
            error: () => {
                //enable the submit button
                form.find('button[type="submit"]').prop("disabled", false);
                // Show generic error message
                alert("An error occurred while registering. Please try again.");
            },
        });
    });

    /**
     * loginForm
     *
     */
    $("#loginForm").submit(function (e) {
        e.preventDefault();

        //get the form
        let form = $(this);

        // Get form data
        let serializedData = form.serialize();

        /**
         * Ajax
         */
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: serializedData,
            dataType: "json",
            beforeSend: function () {
                // Disable the submit button to prevent multiple submissions
                form.find('button[type="submit"]').prop("disabled", true);
            },
            success: function (response) {
                //enable the submit button
                form.find('button[type="submit"]').prop("disabled", false);
                //check if response is success
                if (response.success) {
                    // Show success message
                    alert(response.message);
                    //redirect the user to the dashboard
                    window.location.href = response.redirect;
                    // Reset the form
                    form[0].reset();
                } else {
                    // Show error message
                    alert(response.message);
                }
            },
            error: () => {
                //enable the submit button
                form.find('button[type="submit"]').prop("disabled", false);
                // Show generic error message
                alert("An error occurred while registering. Please try again.");
            },
        });
    });
});
