$("#loading").show();
document.addEventListener("DOMContentLoaded", function() {
	$("#loading").hide();
});
window.addEventListener('beforeunload', function () {
	$("#loading").show();
});

function toast(res) {
	console.log(res);
	res = JSON.parse(res);
	if(res.status === 'success') {
		table.ajax.reload( null, false );
		toastr.success(res.message);
		if (res.id){
		document.getElementById('newForm').reset();
		}
	} else {
		toastr.error(res.message);
	}
}

// var resultado = confirm("¿Quieres seleccionar la Opción A?");

// if (resultado) {
//     // El usuario hizo clic en "Aceptar"
//     alert("Seleccionaste la Opción A");
// } else {
//     // El usuario hizo clic en "Cancelar"
//     alert("No seleccionaste la Opción A");
// }

        // // Función para guardar el estado en localStorage
        // function saveStateToLocalStorage(content) {
        //     localStorage.setItem('appContent', content);
        // }

        // // Función para cargar el estado desde localStorage
        // function loadStateFromLocalStorage() {
        //     return localStorage.getItem('appContent') || '';
        // }

        // // Escuchar eventos htmx y guardar el estado antes de cada actualización
        // document.addEventListener('htmx:beforeRequest', function (event) {
        //     const currentContent = document.getElementById('content').innerHTML;
        //     saveStateToLocalStorage(currentContent);
        // });

        // // Escuchar eventos de carga de página y restaurar el estado
        // window.addEventListener('load', function () {
        //     const savedContent = loadStateFromLocalStorage();
        //     if (savedContent) {
        //         document.getElementById('content').innerHTML = savedContent;  
                
        //         setTimeout(reRunScriptsInContent, 100);
        //     }
        // });

        // function reRunScriptsInContent() {
        //     datatables
        //     // Find all script elements within the loaded content
        //     const contentElement = document.getElementById('content');
        //     const scriptElements = contentElement.querySelectorAll('script');
        
        //     // Iterate over the script elements and execute their content
        //     scriptElements.forEach((script) => {
        //         const scriptClone = document.createElement('script');
        //         scriptClone.text = script.text;
        //         contentElement.appendChild(scriptClone);
        //     });
        // }




// document.addEventListener('htmx:beforeRequest', function (e) {
//     console.log(e)
// });




