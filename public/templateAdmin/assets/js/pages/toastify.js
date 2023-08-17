// toastfy.js
document.addEventListener("DOMContentLoaded", function () {
    if (toastMessages.errors) {
        toastMessages.errors.forEach(function (error) {
            Toastify({
                text: error,
                duration: 5000,
                close: false,
                gravity: "top",
                position: "center",
                style: {
                    background: "#FF0000",
                },
            }).showToast();
        });
    }

    if (toastMessages.error) {
        Toastify({
            text: toastMessages.error,
            duration: 5000,
            close: false,
            gravity: "top",
            position: "center",
            style: {
                background: "#FF0000",
            },
        }).showToast();
    }

    if (toastMessages.success) {
        Toastify({
            text: toastMessages.success,
            duration: 5000,
            close: false,
            gravity: "top",
            position: "center",
            style: {
                background: "#008000",
            },
        }).showToast();
    }

    if (toastMessages.warning) {
        Toastify({
            text: toastMessages.warning,
            duration: 5000,
            close: false,
            gravity: "top",
            position: "center",
            style: {
                background: "#FFA500",
            },
        }).showToast();
    }

    if (toastMessages.info) {
        Toastify({
            text: toastMessages.info,
            duration: 5000,
            close: false,
            gravity: "top",
            position: "center",
            style: {
                background: "#0000FF",
            },
        }).showToast();
    }
});

// document.getElementById('basic').addEventListener('click', () => {
//     Toastify({
//         text: "This is a toast",
//         duration: 3000
//     }).showToast();
// })
// document.getElementById('background').addEventListener('click', () => {
//     Toastify({
//         text: "This is a toast",
//         duration: 3000,
//         backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
//     }).showToast();
// })
// document.getElementById('close').addEventListener('click', () => {
//     Toastify({
//         text: "Click close button",
//         duration: 3000,
//         close:true,
//         backgroundColor: "#4fbe87",
//     }).showToast();
// })
// document.getElementById('top-left').addEventListener('click', () => {
//     Toastify({
//         text: "This is toast in top left",
//         duration: 3000,
//         close:true,
//         gravity:"top",
//         position: "left",
//         backgroundColor: "#4fbe87",
//     }).showToast();
// })
// document.getElementById('top-center').addEventListener('click', () => {
//     Toastify({
//         text: "This is toast in top center",
//         duration: 3000,
//         close:true,
//         gravity:"top",
//         position: "center",
//         backgroundColor: "#4fbe87",
//     }).showToast();
// })
// document.getElementById('top-right').addEventListener('click', () => {
//     Toastify({
//         text: "This is toast in top right",
//         duration: 3000,
//         close:true,
//         gravity:"top",
//         position: "right",
//         backgroundColor: "#4fbe87",
//     }).showToast();
// })
// document.getElementById('bottom-right').addEventListener('click', () => {
//     Toastify({
//         text: "This is toast in bottom right",
//         duration: 3000,
//         close:true,
//         gravity:"bottom",
//         position: "right",
//         backgroundColor: "#4fbe87",
//     }).showToast();
// })
// document.getElementById('bottom-center').addEventListener('click', () => {
//     Toastify({
//         text: "This is toast in bottom center",
//         duration: 3000,
//         close:true,
//         gravity:"bottom",
//         position: "center",
//         backgroundColor: "#4fbe87",
//     }).showToast();
// })
// document.getElementById('bottom-left').addEventListener('click', () => {
//     Toastify({
//         text: "This is toast in bottom left",
//         duration: 3000,
//         close:true,
//         gravity:"bottom",
//         position: "left",
//         backgroundColor: "#4fbe87",
//     }).showToast();
// })
