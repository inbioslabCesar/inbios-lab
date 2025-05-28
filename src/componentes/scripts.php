<!-- src/componentes/scripts.php -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let sintomasSeleccionados = [];
    let chatIniciado = false;
    const nombreUsuario = "<?php echo htmlspecialchars($nombre); ?>";
    // CHAT FLOTANTE 
    document.getElementById('chat-fab').onclick = function() {
        document.getElementById('chat-fab').classList.add('hide');
        document.getElementById('chat-float').style.display = 'block';
        if (!chatIniciado) {
            agregarMensaje("👨‍⚕️ ¡Hola, " + nombreUsuario + "! Soy tu médico virtual. Selecciona o escribe tus síntomas y te ayudo con recomendaciones.", 'msg-bot');
            chatIniciado = true;
        }
    };

    function ocultarChat() {
        document.getElementById('chat-float').style.display = 'none';
        document.getElementById('chat-fab').classList.remove('hide');
    }

    function agregarMensaje(mensaje, clase = 'msg-bot') {
        const chat = document.getElementById('chat');
        const div = document.createElement('div');
        div.className = clase;
        div.innerHTML = mensaje;
        chat.appendChild(div);
        chat.scrollTop = chat.scrollHeight;
    }

    function enviarSintomas() {
        const input = document.getElementById('user-input');
        const sintomas = input.value ? input.value.split(',').map(s => s.trim()).filter(s => s.length > 0) : sintomasSeleccionados;
        if (sintomas.length === 0) {
            agregarMensaje("Por favor, selecciona o escribe al menos un síntoma.", 'msg-bot');
            return;
        }
        agregarMensaje(sintomas.join(', '), 'msg-user');
        input.value = '';
        // Animación de "escribiendo..." 
        const chat = document.getElementById('chat');
        const escribiendoDiv = document.createElement('div');
        escribiendoDiv.className = 'msg-bot typing-indicator bg-light rounded-pill shadow-sm mb-2 mt-1 px-3 py-2';
        escribiendoDiv.id = 'escribiendo-bot';
        escribiendoDiv.innerHTML = ` <span class="typing-dot"></span> <span class="typing-dot"></span> <span class="typing-dot"></span> `;
        chat.appendChild(escribiendoDiv);
        chat.scrollTop = chat.scrollHeight;
        fetch('/src/procesar_sintomas.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                sintomas: sintomas
            })
        }).then(response => response.json()).then(data => {
            setTimeout(() => {
                const escribiendoMsg = document.getElementById('escribiendo-bot');
                if (escribiendoMsg) escribiendoMsg.remove();
                agregarMensaje(data.resultado, 'msg-bot');
            }, 1500);
        });
        sintomasSeleccionados = [];
        document.querySelectorAll('.sintoma-btn.active').forEach(btn => btn.classList.remove('active'));
    }
    // HISTORIAL 
    var historialModal = document.getElementById('historialModal');
    historialModal.addEventListener('show.bs.modal', function() {
        fetch('/src/historial_consultas.php').then(response => response.json()).then(data => {
            const historialList = document.getElementById('historial-list');
            if (data.length === 0) {
                historialList.innerHTML = "<em>No hay consultas registradas.</em>";
            } else {
                historialList.innerHTML = data.map(h => `<div class="border-bottom pb-2 mb-2"> <strong>Fecha:</strong> ${h.fecha}<br> <strong>Síntomas:</strong> ${h.sintomas}<br> <strong>Resultado:</strong> ${h.resultado} </div>`).join('');
            }
        });
    });
    // AVATAR: Previsualizar y subir nueva imagen 
    function previewAvatar(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-avatar').src = e.target.result;
                document.getElementById('main-avatar').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
            // Aquí puedes hacer fetch('/src/actualizar_avatar.php', ...) para guardar el archivo 
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        // Mensaje de bienvenida automático 
        setTimeout(() => {
            addBotMessage('¡Hola, <?php echo htmlspecialchars($nombre); ?>! Soy tu Dr. Virtual.');
            setTimeout(() => {
                addBotMessage('¿Qué servicio deseas obtener? Elige una opción:');
                addServiceOptions();
            }, 2000);
        }, 500);
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const userMsg = chatInput.value.trim();
            if (userMsg) {
                addUserMessage(userMsg);
                chatInput.value = '';
                // Aquí puedes agregar lógica para procesar la respuesta 
            }
        });

        function addBotMessage(msg) {
            const div = document.createElement('div');
            div.className = 'msg-bot mb-2';
            div.textContent = msg;
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function addUserMessage(msg) {
            const div = document.createElement('div');
            div.className = 'msg-user mb-2 text-end';
            div.textContent = msg;
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function addServiceOptions() {
            const optionsDiv = document.createElement('div');
            optionsDiv.className = 'mb-2';
            optionsDiv.innerHTML = ` <button class="chat-option-btn" onclick="selectService('Consulta médica')">Consulta médica</button> <button class="chat-option-btn" onclick="selectService('Análisis clínico')">Análisis clínico</button> `;
            chatMessages.appendChild(optionsDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        // Maneja la selección de servicio 
        window.selectService = function(service) {
            addUserMessage(service);
            addBotMessage('¡Perfecto! Has seleccionado "' + service + '". ¿En qué puedo ayudarte específicamente?');
        };
    });
</script>