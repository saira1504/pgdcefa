<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SENA - Crear Cuenta</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #2d5016;
            --secondary-green: #4a7c59;
            --accent-green: #6b9080;
            --light-green: #a4c3b2;
            --bg-green: #f0f7f4;
            --error-red: #dc3545;
            --success-green: #28a745;
            --warning-yellow: #ffc107;
            --white: #ffffff;
            --dark-text: #1a1a1a;
            --light-text: #666;
            --shadow: 0 10px 30px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px rgba(0,0,0,0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            position: relative;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 50%, var(--accent-green) 100%);
}

        /* Animated Background - Same as login */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
            animation-duration: 8s;
        }

        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            left: 80%;
            animation-delay: 2s;
            animation-duration: 6s;
        }

        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            top: 80%;
            left: 20%;
            animation-delay: 4s;
            animation-duration: 10s;
        }

        .shape:nth-child(4) {
            width: 40px;
            height: 40px;
            top: 30%;
            left: 70%;
            animation-delay: 1s;
            animation-duration: 7s;
        }

        .shape:nth-child(5) {
            width: 120px;
            height: 120px;
            top: 10%;
            left: 60%;
            animation-delay: 3s;
            animation-duration: 9s;
        }

        .shape:nth-child(6) {
            width: 70px;
            height: 70px;
            top: 70%;
            left: 5%;
            animation-delay: 5s;
            animation-duration: 8s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: particle-float 15s linear infinite;
        }

        .particle:nth-child(odd) {
            animation-duration: 20s;
            background: rgba(164, 195, 178, 0.8);
        }

        @keyframes particle-float {
            0% {
                transform: translateY(100vh) translateX(0px);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(100px);
                opacity: 0;
            }
        }

        .geometric-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: geometric-move 20s linear infinite;
        }

        @keyframes geometric-move {
            0% {
                background-position: 0 0, 25px 25px;
            }
            100% {
                background-position: 50px 50px, 75px 75px;
            }
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
            padding: 20px 0;
        }

        .auth-content {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: var(--shadow-hover);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.3);
            animation: slideIn 0.8s ease-out;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--bg-green), rgba(164, 195, 178, 0.3));
            padding: 40px 40px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(45, 80, 22, 0.1);
            position: relative;
            overflow: hidden;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: header-pattern 10s linear infinite;
        }

        @keyframes header-pattern {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: var(--shadow);
            animation: logo-pulse 3s ease-in-out infinite;
        }

        @keyframes logo-pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: var(--shadow);
            }
            50% {
                transform: scale(1.05);
                box-shadow: var(--shadow-hover);
            }
        }

        .logo-text h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-green);
            margin: 0;
            line-height: 1;
            position: relative;
            z-index: 2;
        }

        .logo-text p {
            font-size: 0.9rem;
            color: var(--secondary-green);
            font-weight: 500;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .auth-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-green);
            margin: 0 0 10px 0;
            position: relative;
            z-index: 2;
        }

        .auth-header p {
            color: var(--light-text);
            font-size: 1rem;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .auth-body {
            padding: 40px;
            position: relative;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-green);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            font-size: 16px;
            color: var(--secondary-green);
        }

        .form-input {
            padding: 16px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(250, 251, 252, 0.8);
            color: var(--dark-text);
            backdrop-filter: blur(10px);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--secondary-green);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 3px rgba(74, 124, 89, 0.1);
            transform: translateY(-2px);
        }

        .form-input.is-invalid {
            border-color: var(--error-red);
            background: #fef5f5;
        }

        .password-input-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--light-text);
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--secondary-green);
            transform: translateY(-50%) scale(1.1);
        }

        .error-message {
            color: var(--error-red);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 5px;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            width: 100%;
            height: 4px;
            background: #e1e5e9;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-text {
            font-size: 12px;
            color: var(--light-text);
        }

        .password-match {
            margin-top: 8px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .match-success {
            color: var(--success-green);
        }

        .match-error {
            color: var(--error-red);
        }

        /* Improved Terms and Conditions */
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            color: var(--dark-text);
            position: relative;
            line-height: 1.5;
        }

        .checkbox-container input[type="checkbox"] {
            display: none;
        }

        .checkmark {
            width: 22px;
            height: 22px;
            border: 2px solid #e1e5e9;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            background: var(--white);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .checkbox-container input[type="checkbox"]:checked + .checkmark {
            background: var(--secondary-green);
            border-color: var(--secondary-green);
            transform: scale(1.1);
        }

        .checkbox-container input[type="checkbox"]:checked + .checkmark::after {
            content: '✓';
            color: white;
            font-size: 14px;
            font-weight: bold;
            animation: checkmark-appear 0.3s ease-in-out;
        }

        @keyframes checkmark-appear {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: white;
            border: none;
            padding: 18px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
            background: linear-gradient(135deg, var(--secondary-green), var(--accent-green));
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(-1px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(45, 80, 22, 0.1);
        }

        .auth-footer p {
            color: var(--light-text);
            font-size: 14px;
            margin: 0;
        }

        .auth-link {
            color: var(--secondary-green);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .auth-link:hover {
            color: var(--primary-green);
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .auth-header,
            .auth-body {
                padding: 30px 25px;
            }
            
            .auth-header h2 {
                font-size: 1.6rem;
            }

            .shape {
                display: none;
            }

            .terms-container {
                padding: 15px;
            }

            .terms-header {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .terms-links {
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="geometric-bg"></div>
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <div class="particles" id="particles"></div>
    </div>

    <div class="auth-container">
    <div class="auth-content">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <div class="logo-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="logo-text">
                        <h1>SENA</h1>
                        <p>Gestión Documental</p>
                    </div>
                </div>
                <h2>Crear Cuenta</h2>
                <p>Únete a la plataforma de gestión documental</p>
            </div>

            <div class="auth-body">
                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            Nombre Completo
                        </label>
                        <input id="name" 
                               type="text" 
                               class="form-input @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autocomplete="name" 
                               autofocus
                               placeholder="Tu nombre completo">
                        
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Correo Electrónico
                        </label>
                        <input id="email" 
                               type="email" 
                               class="form-input @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email"
                               placeholder="tu@email.com">
                        
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Contraseña
                        </label>
                        <div class="password-input-container">
                            <input id="password" 
                                   type="password" 
                                   class="form-input @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="••••••••">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <span class="strength-text" id="strength-text">Ingresa una contraseña</span>
                        </div>
                        
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="form-label">
                            <i class="fas fa-check-double"></i>
                            Confirmar Contraseña
                        </label>
                        <div class="password-input-container">
                            <input id="password-confirm" 
                                   type="password" 
                                   class="form-input" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="••••••••">
                            <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                                <i class="fas fa-eye" id="password-confirm-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="password-match"></div>
                    </div>

                    <div class="form-group">
    <label class="checkbox-container">
        <input type="checkbox" name="terms" id="terms" required>
        <span class="checkmark"></span>
        Acepto los términos y condiciones y la política de privacidad
    </label>
</div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-user-plus"></i>
                        Crear Cuenta
                    </button>
                </form>

                <div class="auth-footer">
                    <p>¿Ya tienes una cuenta? 
                        <a href="{{ route('login') }}" class="auth-link">Inicia sesión aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 15;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password-confirm');
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');
            const passwordMatch = document.getElementById('password-match');

            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    const strength = calculatePasswordStrength(password);
                    updatePasswordStrength(strength, strengthFill, strengthText);
                });
            }

            if (confirmInput) {
                confirmInput.addEventListener('input', function() {
                    checkPasswordMatch(passwordInput.value, this.value, passwordMatch);
                });
                
                passwordInput.addEventListener('input', function() {
                    if (confirmInput.value) {
                        checkPasswordMatch(this.value, confirmInput.value, passwordMatch);
                    }
                });
            }
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/)) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 25;
            return Math.min(strength, 100);
        }

        function updatePasswordStrength(strength, fillElement, textElement) {
            fillElement.style.width = strength + '%';
            
            if (strength < 25) {
                fillElement.style.background = '#dc3545';
                textElement.textContent = 'Muy débil';
                textElement.style.color = '#dc3545';
            } else if (strength < 50) {
                fillElement.style.background = '#ffc107';
                textElement.textContent = 'Débil';
                textElement.style.color = '#ffc107';
            } else if (strength < 75) {
                fillElement.style.background = '#fd7e14';
                textElement.textContent = 'Buena';
                textElement.style.color = '#fd7e14';
            } else {
                fillElement.style.background = '#28a745';
                textElement.textContent = 'Fuerte';
                textElement.style.color = '#28a745';
            }
        }

        function checkPasswordMatch(password, confirmPassword, matchElement) {
            if (confirmPassword === '') {
                matchElement.innerHTML = '';
                return;
            }
            
            if (password === confirmPassword) {
                matchElement.innerHTML = '<i class="fas fa-check"></i> Las contraseñas coinciden';
                matchElement.className = 'password-match match-success';
            } else {
                matchElement.innerHTML = '<i class="fas fa-times"></i> Las contraseñas no coinciden';
                matchElement.className = 'password-match match-error';
            }
        }
    </script>
</body>
</html>
