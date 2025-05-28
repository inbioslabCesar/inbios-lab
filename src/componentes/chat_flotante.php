<style>
    #chat-toggle-btn {
        position: fixed;
        bottom: 40px;
        right: 40px;
        width: 64px;
        height: 64px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 4px 16px rgba(71, 11, 133, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #8d3cff;
        cursor: pointer;
        z-index: 1057;
        transition: background 0.2s;
    }

    #chat-toggle-btn img {
        width: 44px;
        height: 44px;
    }

    #chat-container {
        position: fixed;
        bottom: 120px;
        right: 40px;
        width: 400px;
        max-width: 95vw;
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgba(71, 11, 133, 0.12), 0 1.5px 3px rgba(71, 11, 133, 0.08);
        font-family: 'Inter', Arial, sans-serif;
        z-index: 1056;
        overflow: hidden;
        border: 2px solid #8d3cff;
        display: flex;
        flex-direction: column;
        transition: opacity 0.3s, visibility 0.3s;
        height: 550px;
    }

    #chat-header {
        background: linear-gradient(90deg, #8d3cff 0%, #6e23dd 100%);
        color: #fff;
        padding: 1rem;
        font-weight: bold;
        font-size: 1.1rem;
        letter-spacing: 1px;
        text-align: center;
    }

    #chat-box {
        flex: 1;
        height: 100%;
        overflow-y: auto;
        padding: 1rem;
        background: #f8f6fc;
        font-size: 1rem;
        scroll-behavior: smooth;
    }

    .user-msg {
        text-align: right;
        margin-bottom: 8px;
    }

    .user-msg span {
        background: #8d3cff;
        color: #fff;
        padding: 8px 16px;
        border-radius: 18px 18px 4px 18px;
        display: inline-block;
        max-width: 80%;
        word-break: break-word;
    }

    .bot-msg {
        text-align: left;
        margin-bottom: 8px;
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .bot-msg img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff;
        border: 1.5px solid #8d3cff;
        margin-top: 2px;
    }

    .bot-msg span {
        background: #ece6fa;
        color: #6e23dd;
        padding: 8px 16px;
        border-radius: 18px 18px 18px 4px;
        display: inline-block;
        max-width: 80%;
        word-break: break-word;
    }

    #chat-input-area {
        display: flex;
        border-top: 1px solid #e0d7f7;
        background: #fff;
        padding: 0.5rem;
    }

    #user-input {
        flex: 1;
        border: none;
        padding: 10px;
        font-size: 1rem;
        border-radius: 0.5rem 0 0 0.5rem;
        outline: none;
        background: #fff;
    }

    #send-btn {
        background: linear-gradient(90deg, #8d3cff 0%, #6e23dd 100%);
        color: #fff;
        border: none;
        padding: 0 24px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 0 0.5rem 0.5rem 0;
        cursor: pointer;
        transition: background 0.2s;
    }

    #send-btn:hover {
        background: linear-gradient(90deg, #6e23dd 0%, #8d3cff 100%);
    }

    #typing-dots {
        display: flex !important;
        align-items: center !important;
        margin-left: 8px !important;
        height: 18px !important;
        min-width: 50px !important;
    }

    .typing-dot {
        width: 9px !important;
        height: 9px !important;
        margin: 0 3px !important;
        background: #8d3cff !important;
        border: 2px solid #fff !important;
        border-radius: 50% !important;
        animation: bounce 0.6s infinite alternate !important;
        box-shadow: 0 0 6px #6e23ddaa !important;
        padding: 0 !important;
        line-height: normal !important;
        display: block !important;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s !important;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s !important;
    }

    @keyframes bounce {
        to {
            transform: translateY(-7px);
        }
    }
</style>
<!-- El icono del chat siempre visible -->
<div id="chat-toggle-btn" onclick="toggleChat()"> <img src="/images/inbioslab-logo.png" alt="Abrir chat"> </div>
<div id="chat-container" class="shadow-lg" style="display:none;">
    <div id="chat-header">Chat Laboratorio</div>
    <div id="chat-box"></div>
    <div id="chat-input-area"> <input type="text" id="user-input" class="form-control" placeholder="Escribe tu consulta..." autocomplete="off"> <button id="send-btn" class="btn" onclick="enviarMensaje()">Enviar</button> </div>
</div>
<script>
    let chatContainer = document.getElementById('chat-container');
    let chatBox = document.getElementById('chat-box');
    let toggleBtn = document.getElementById('chat-toggle-btn');
    let inactivityTimer;
    let typingTimeout;
    let isChatVisible = false;
    // Mensaje de bienvenida al cargar 
    window.addEventListener('DOMContentLoaded', function() {
        let bienvenida = "¡Hola, <?php echo htmlspecialchars($nombre); ?>! 👋<br>¿En qué te puedo ayudar? Tenemos varios servicios para ti.";
        chatBox.innerHTML += "<div class='bot-msg'><img src='/images/inbioslab-logo.png' alt='Bot'><span>" + bienvenida + "</span></div>";
        chatBox.scrollTop = chatBox.scrollHeight;
        // El chat inicia oculto, muestra solo el botón 
        chatContainer.style.display = 'none';
        toggleBtn.style.display = 'flex';
    });
    // Enviar mensaje con Enter
    document.getElementById('user-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            enviarMensaje();
        }
        resetInactivity();
    });
    // Resetear temporizador de inactividad en cada interacción 
    function resetInactivity() {
        clearTimeout(inactivityTimer);
        if (isChatVisible) {
            inactivityTimer = setTimeout(minimizeChat, 20000);
            // 20 segundos 
        }
    }

    function minimizeChat() {
        chatContainer.style.display = 'none';
        toggleBtn.style.display = 'flex';
        isChatVisible = false;
    }

    function toggleChat() {
        if (isChatVisible) {
            chatContainer.style.display = 'none';
            toggleBtn.style.display = 'flex';
            isChatVisible = false;
        } else {
            chatContainer.style.display = 'flex';
            toggleBtn.style.display = 'flex';
            // El icono siempre visible 
            isChatVisible = true;
            chatBox.scrollTop = chatBox.scrollHeight;
            resetInactivity();
        }
    }

    function enviarMensaje() {
        let mensaje = document.getElementById('user-input').value;
        if (mensaje.trim() === "") return;
        chatBox.innerHTML += "<div class='user-msg'><span>" + mensaje + "</span></div>";
        chatBox.scrollTop = chatBox.scrollHeight;
        document.getElementById('user-input').value = "";
        // Mostrar animación de puntos mientras espera la respuesta 
        let typingHTML = "<div class='bot-msg' id='typing-msg'><img src='/images/inbioslab-logo.png' alt='Bot'><span id='typing-dots'><span class='typing-dot'></span><span class='typing-dot'></span><span class='typing-dot'></span></span></div>";
        chatBox.innerHTML += typingHTML;
        chatBox.scrollTop = chatBox.scrollHeight;
        // Esperar 2 segundos antes de mostrar la respuesta 
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(function() {
            fetch('/src/componentes/chatbot_backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'mensaje=' + encodeURIComponent(mensaje)
            }).then(response => response.text()).then(respuesta => {
                let typingMsg = document.getElementById('typing-msg');
                if (typingMsg) typingMsg.remove();
                // Quita los puntitos 
                chatBox.innerHTML += "<div class='bot-msg'><img src='/images/inbioslab-logo.png' alt='Bot'><span>" + respuesta + "</span></div>";
                chatBox.scrollTop = chatBox.scrollHeight;
                resetInactivity();
            });
        }, 2500);
        // 2 segundos de espera simulando que el bot "piensa" 
        resetInactivity();
    }
</script>