
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Quieres crear este sensor?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, crear',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var formData = new FormData(e.target);
            fetch('ws/createElement.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Éxito!',
                        data.message,
                        'success'
                    );
                } else {
                    Swal.fire(
                        'Error!',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'Hubo un problema al procesar tu solicitud.',
                    'error'
                );
            });
        }
    });
});