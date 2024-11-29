function showToast(mensaje = ``, icono = 'success', type = `INFO`, duration = 2500, url = null) {
  const bgColor = {
    'INFO': '#22a6b3',
    'WARNING': '#FFc900',
    'SUCCESS': '#6ab04c',
    'ERROR': '#eb4d4b'
  };
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: duration,
    color: '#fff',
    timerProgressBar: true,
    background: bgColor[type],
    didOpen: (toast) => {
      toast.addEventListener = ('mouseenter', Swal.stopTimer);
      toast.addEventListener = ('mouseleave', Swal.resumeTimer);
    }
  });
  Toast.fire({
    icon: icono,
    title: mensaje
  }).then(() => {
    if (url != null) {
      window.location.href = url;
    }
  });
}

async function showConfirm(pregunta = ``, modulo = ``) {
  const respuesta = await Swal.fire({
    title: pregunta,
    text: modulo,
    showDenyButton: true,
    confirmButtonText: 'Guardar',
    denyButtonText: 'Cancelar',
    footer: '<b>Lino Fino</b>',
  });
  if (respuesta.isConfirmed) {
    return true;
  } else if (respuesta.isDenied) {
    return false;
  }
}

// Función para activar tooltips en todos los elementos que los tengan
function initializeTooltips() {
  const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  tooltipElements.forEach(element => {
    new bootstrap.Tooltip(element);
  });
}
