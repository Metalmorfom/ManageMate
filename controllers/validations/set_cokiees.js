
    // Función para establecer una cookie
    function setCookie(name, value, daysToLive) {
        let cookieValue = encodeURIComponent(value);
        let expires = "";
        if (daysToLive) {
            const date = new Date();
            date.setTime(date.getTime() + (daysToLive * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + cookieValue + expires + "; path=/";
    }

    // Función para obtener una cookie
    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
        }
        return null;
    }

    // Función para cargar las últimas selecciones desde las cookies
    function cargarUltimasSelecciones() {
        const selecciones = getCookie('ultimasSelecciones');
        return selecciones ? JSON.parse(selecciones) : [];
    }

    // Función para guardar la selección actual en las cookies
    function guardarSeleccion(seleccion) {
        let selecciones = cargarUltimasSelecciones();
        if (!selecciones.includes(seleccion)) {
            selecciones.unshift(seleccion);
            selecciones = selecciones.slice(0, 5);
            setCookie('ultimasSelecciones', JSON.stringify(selecciones), 7);
        }
    }

    // Obtener el elemento de entrada y la lista de sugerencias
    const inputAutocompletar = document.getElementById('inputAutocompletar');
    const sugerencias = document.getElementById('sugerencias');

    // Crear una matriz de ejemplos de sugerencias
    const ejemplosSugerencias = [
        "Manzana", "Banana", "Naranja", "Pera", "Uva", "Kiwi", "Mango"
    ];

    // Función para mostrar las sugerencias y las últimas selecciones
  // Función para mostrar las sugerencias y las últimas selecciones
  function mostrarSugerencias() {
        const texto = inputAutocompletar.value.toLowerCase();
        sugerencias.innerHTML = ''; // Limpia las sugerencias actuales

        // Mostrar últimas selecciones solo si el campo de texto está vacío
        if (texto === '') {
            const ultimasSelecciones = cargarUltimasSelecciones();
            if (ultimasSelecciones.length > 0) {
                const tituloUltimasSelecciones = document.createElement('li');
                tituloUltimasSelecciones.textContent = 'Últimas Selecciones';
                tituloUltimasSelecciones.style.fontWeight = 'bold';
                tituloUltimasSelecciones.className = 'titulo';
                
                sugerencias.appendChild(tituloUltimasSelecciones);

                ultimasSelecciones.forEach(seleccion => {
                    const elemento = document.createElement('li');
                    elemento.textContent = seleccion;
                    elemento.addEventListener('click', () => {
                        inputAutocompletar.value = seleccion;
                        guardarSeleccion(seleccion);
                    });
                    sugerencias.appendChild(elemento);
                });
            }
        }

        // Mostrar sugerencias basadas en el texto
        const sugerenciasFiltradas = ejemplosSugerencias.filter(sugerencia =>
            sugerencia.toLowerCase().includes(texto)
        );

        if (sugerenciasFiltradas.length > 0) {
            const tituloSugerencias = document.createElement('li');
            tituloSugerencias.textContent = 'Sugerencias';
            tituloSugerencias.style.fontWeight = 'bold';
            
            tituloSugerencias.style.background = '#f3f3f3';

            sugerencias.appendChild(tituloSugerencias);
            tituloSugerencias.className = 'titulo'; 
            

            sugerenciasFiltradas.forEach(sugerencia => {
                const sugerenciaElemento = document.createElement('li');
                sugerenciaElemento.textContent = sugerencia;
                sugerenciaElemento.addEventListener('click', () => {
                    inputAutocompletar.value = sugerencia;
                    guardarSeleccion(sugerencia);
                });
                sugerencias.appendChild(sugerenciaElemento);
            });
        }

        sugerencias.style.display = (sugerencias.children.length > 0) ? 'block' : 'none';
    }

    // Eventos
    inputAutocompletar.addEventListener('click', mostrarSugerencias);
    inputAutocompletar.addEventListener('blur', () => {
        setTimeout(() => {
            sugerencias.style.display = 'none';
        }, 100);
    });
    inputAutocompletar.addEventListener('click', mostrarSugerencias);

    const miFormulario = document.getElementById('creaOTForm');
    miFormulario.addEventListener('submit', function(event) {
        const textoInput = inputAutocompletar.value;
        if (textoInput) {
            guardarSeleccion(textoInput);
        }
        // Aquí se puede agregar el código para enviar el formulario a un servidor, si es necesario
        // miFormulario.submit(); // Descomenta esta línea si necesitas enviar el formulario manualmente después de guardar la selección
    });

    inputAutocompletar.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            guardarSeleccion(inputAutocompletar.value);
        }
    });















