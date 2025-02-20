document.addEventListener("DOMContentLoaded", function() {
    var formulario = document.getElementById("form");
    var mensajeCancelacion = document.getElementById("mensajeCancelacion");

    // Función para validar el nombre de usuario
    function validarUsuario() {
        var usernameInput = document.getElementById("username");
        var mensajeDivU = document.getElementById("mensajeU");
        var username = usernameInput.value;
        if (username.length !== 8) {
            mensajeDivU.textContent = "El nombre de usuario debe tener 8 caracteres";
            mensajeDivU.style.color = "red";
            return false;
        } else {
            mensajeDivU.textContent = "";
            return true;
        }
    }

    // Función para validar el correo electrónico
    function validarCorreo() {
        var emailInput = document.getElementById("email");
        var mensajeDivE = document.getElementById("mensajeE");
        var email = emailInput.value;
        if (!/@/.test(email)) {
            mensajeDivE.textContent = "Correo no válido";
            mensajeDivE.style.color = "red";
            return false;
        } else {
            mensajeDivE.textContent = "";
            return true;
        }
    }

    // Función para validar el teléfono
    function validarTelefono() {
        var telInput = document.getElementById("tel");
        var mensajeDivT = document.getElementById("mensajeT");
        var tel = telInput.value;
        if (tel.length !== 8) {
            mensajeDivT.textContent = "El teléfono debe tener exactamente 8 números";
            mensajeDivT.style.color = "red";
            return false;
        } else {
            mensajeDivT.textContent = "";
            return true;
        }
    }

    // Función para validar las contraseñas
    function validarContrasenas() {
        var passwordInput = document.getElementById("password");
        var confirmPasswordInput = document.getElementById("Cpassword");
        var mensajeDivP = document.getElementById("mensajeP");
        var mensajeDivC = document.getElementById("mensajeC");
        var errores = false;

        // Verificar contraseña
        var password = passwordInput.value;
        var erroresPassword = [];

        if (password.length < 8) {
            erroresPassword.push("La contraseña debe tener al menos 8 caracteres");
        }

        if (!/[A-Z]/.test(password)) {
            erroresPassword.push("La contraseña debe contener al menos una letra mayúscula");
        }

        if (!/\d/.test(password)) {
            erroresPassword.push("La contraseña debe contener al menos un número");
        }

        if (erroresPassword.length > 0) {
            mensajeDivP.innerHTML = "<ul>";
            erroresPassword.forEach(function(error) {
                mensajeDivP.innerHTML += "<li>" + error + "</li>";
            });
            mensajeDivP.innerHTML += "</ul>";
            mensajeDivP.style.color = "red";
            errores = true;
        } else {
            mensajeDivP.textContent = "";
        }

        // Verificar confirmar contraseña
        var confirmPassword = confirmPasswordInput.value;
        if (password !== confirmPassword) {
            mensajeDivC.textContent = "Las contraseñas no coinciden";
            mensajeDivC.style.color = "red";
            errores = true;
        } else {
            mensajeDivC.textContent = "";
        }

        return !errores;
    }

    // Event listener para validar el formulario al enviarlo
    formulario.addEventListener("submit", function(event) {
        var validacionUsuario = validarUsuario();
        var validacionCorreo = validarCorreo();
        var validacionTelefono = validarTelefono();
        var validacionContrasenas = validarContrasenas();

        if (!(validacionUsuario && validacionCorreo && validacionTelefono && validacionContrasenas)) {
            event.preventDefault();
            mensajeCancelacion.textContent = "El envío del formulario ha sido cancelado debido a errores.";
        } else {
            mensajeCancelacion.textContent = "";
        }
    });

    // Verificaciones en tiempo real
    var usernameInput = document.getElementById("username");
    var emailInput = document.getElementById("email");
    var telInput = document.getElementById("tel");
    var passwordInput = document.getElementById("password");
    var confirmPasswordInput = document.getElementById("Cpassword");

    usernameInput.addEventListener("input", validarUsuario);
    emailInput.addEventListener("input", validarCorreo);
    telInput.addEventListener("input", validarTelefono);
    passwordInput.addEventListener("input", validarContrasenas);
    confirmPasswordInput.addEventListener("input", validarContrasenas);
});
