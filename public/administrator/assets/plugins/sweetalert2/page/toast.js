document.addEventListener("DOMContentLoaded", function () {
    var option = {
        classname: "toast",
        transition: "fade",
        insertBefore: true,
        duration: 4000,
        enableSounds: true,
        autoClose: true,
        progressBar: true,
        sounds: {
            info: toastMessages.path + "/sounds/info/1.mp3",
            // path to sound for successfull message:
            success:
                toastMessages.path + "/sounds/success/1.mp3",
            // path to sound for warn message:
            warning:
                toastMessages.path + "/sounds/warning/1.mp3",
            // path to sound for error message:
            error: toastMessages.path + "/sounds/error/1.mp3",
        },

        onShow: function (type) {
            console.log("a toast " + type + " message is shown!");
        },
        onHide: function (type) {
            console.log("the toast " + type + " message is hidden!");
        },

        // the placement where prepend the toast container:
        prependTo: document.body.childNodes[0],
    };
    if (toastMessages.errors) {
        toastMessages.errors.forEach(function (error) {
            // Example usage
            var toasty = new Toasty(option);
            toasty.configure(option);
            toasty.error(error);
        });
    }

    if (toastMessages.error) {
        // Example usage
        var toasty = new Toasty(option);
        toasty.configure(option);
        toasty.error(toastMessages.error);
    }

    if (toastMessages.success) {
        var toasty = new Toasty(option);
        toasty.configure(option);
        toasty.success(toastMessages.success);
    }

    if (toastMessages.warning) {
        var toasty = new Toasty(option);
        toasty.configure(option);
        toasty.warning(toastMessages.warning);
    }

    if (toastMessages.info) {
        var toasty = new Toasty(option);
        toasty.configure(option);
        toasty.info(toastMessages.info);
    }
});
