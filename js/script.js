// Obtenir el formulari

// Evento para controlar la carga completa de la página HTML
document.addEventListener("DOMContentLoaded", () => {
  // Obtener todos los formularios mediante la clase delete-data
  const formDelete = document.querySelectorAll(".delete-data");

  const modal_delete = document.getElementById("dialog_delete");
  const btn_cancel_delete = document.getElementById("delete_no");
  const btn_confirm_delete = document.getElementById("delete_si");

//   console.log(formDelete);
  let formularioActivo = null;
  
  // Recorremos todos los formularios
  formDelete.forEach( formulario => {
    // console.log(formulario);
    formulario.addEventListener("submit", (e) => {
      e.preventDefault();
      const userName = formulario['userName'].value
      formularioActivo = formulario;
      console.log(formularioActivo);
      document.getElementById('confirm_name_user').textContent = userName
      modal_delete.showModal();
    });

    btn_confirm_delete.addEventListener('click', () => {
        if(formularioActivo) {
            formularioActivo.submit() 
        }
    })

    btn_cancel_delete.addEventListener('click', () => {
        modal_delete.close()
        formularioActivo = null;
    })
    
  });
});
