var onAuthorize = function(response) {
    if (response.status == 'succeeded') {
        console.log("Pago exitoso:", response);
        alert("¡Pago realizado con éxito!");
    } else {
        console.log("Pago no completado:", response);
        alert("Hubo un problema con el pago.");
    }
};
