/public_html /
src /
componentes 
head.php 
sidebar.php 
navbar.php 
profile.php 
chat_flotante.php 
historial_modal.php 
scripts.php 
db.php 
procesar_sintomas.php 
historial_consultas.php 
actualizar_avatar.php 
/avatars /
images 
index.php 


include 'src/componentes/head.php'; 



//----26-05-25-------

Documentación de Avance INBIOSLAB (hasta hoy)
Estructura de Carpetas y Archivos
/public_html index.php registrar_usuario.php login.php logout.php /src /componentes head.php sidebar.php navbar.php profile.php chat_flotante.php historial_modal.php scripts.php db.php procesar_sintomas.php historial_consultas.php actualizar_avatar.php /avatars # Avatares de usuario (avatar1.png por defecto) /images # Logo institucional (inbioslab-logo.png) 

Funcionalidades implementadas
Panel de usuario modular y responsivo con Bootstrap 5, barra lateral, navbar y chat flotante tipo Hostinger.
Autenticación completa: registro, login y logout, con mensajes de error y validación.
Registro de usuario: avatar por defecto, sin opción de seleccionar imagen al registrarse.
Logo institucional visible en la barra lateral y en formularios de login/registro.
Colores y estilos personalizados en referencia al logo.
Animación de fondo tipo burbujas de laboratorio en login y registro.
Chatbot médico virtual con animación de puntos, historial de consultas en modal.
Componentes PHP separados para facilitar el mantenimiento y crecimiento del sistema.
Conexión segura a la base de datos usando PDO.
Gestión de historial de consultas para cada usuario.
Archivos clave
index.php: Archivo principal que arma el dashboard con los componentes.
registrar_usuario.php: Registro de usuario, sin selección de avatar.
login.php: Inicio de sesión, con logo y fondo animado.
logout.php: Cierra sesión y redirige a login.
/src/db.php: Conexión a la base de datos.
/src/procesar_sintomas.php: Lógica del chatbot.
/src/historial_consultas.php: Para mostrar el historial en el modal.
/src/actualizar_avatar.php: (listo para usar si en el futuro quieres actualizar el avatar).
/src/componentes/: Carpeta con todos los bloques visuales del dashboard.
Próximos pasos sugeridos
Probar todo el flujo (registro, login, panel, chatbot, historial).
Mejorar la gestión de avatar si lo deseas (opcional).
Agregar más módulos o funciones al dashboard (por ejemplo: perfil, resultados, administración).
Mejorar seguridad (CSRF, validaciones extras, etc).
¡Listo para continuar mañana! Guarda esta documentación para recordar cómo está organizado tu sistema.


src/ └── componentes/ ├── configuracion/ │ ├── roles.php # Gestión de roles y usuarios │ ├── chatbot.php # Administración de preguntas/respuestas del chatbot │ ├── no_respondidas.php # Gestión de preguntas no respondidas del chatbot │ ├── masivo.php # Carga masiva de preguntas/respuestas │ └── ... # (Agrega aquí otros módulos de configuración según crezcas) ├── configuracion.php # Archivo principal de la sección de configuración (el "main" de config) └── ... # Otros componentes de tu sistema 