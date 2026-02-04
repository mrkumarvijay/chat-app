<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application - Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
        }
        .auth-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(10px);
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="min-vh-100 d-flex justify-content-center align-items-center">
        <div class="auth-container border-0 shadow-lg rounded-4">
            <div class="text-center mb-4">
                <h2>Chat Application</h2>
                <p class="text-muted">Login or register to start chatting</p>
            </div>

            <form id="auth-form">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                <div class="mb-3" id="name-field" style="display: none;">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="submit-btn">Login</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <button class="btn btn-link" id="toggle-auth">Don't have an account? Register</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        class AuthApp {
            constructor() {
                this.isRegister = false;
                this.form = document.getElementById('auth-form');
                this.emailInput = document.getElementById('email');
                this.passwordInput = document.getElementById('password');
                this.nameInput = document.getElementById('name');
                this.nameField = document.getElementById('name-field');
                this.submitBtn = document.getElementById('submit-btn');
                this.toggleBtn = document.getElementById('toggle-auth');

                this.setupEventListeners();
            }

            setupEventListeners() {
                this.form.addEventListener('submit', (e) => this.handleSubmit(e));
                this.toggleBtn.addEventListener('click', () => this.toggleMode());
            }

            toggleMode() {
                this.isRegister = !this.isRegister;

                if (this.isRegister) {
                    this.submitBtn.textContent = 'Register';
                    this.toggleBtn.textContent = 'Already have an account? Login';
                    this.nameField.style.display = 'block';
                    this.nameInput.required = true;
                } else {
                    this.submitBtn.textContent = 'Login';
                    this.toggleBtn.textContent = "Don't have an account? Register";
                    this.nameField.style.display = 'none';
                    this.nameInput.required = false;
                }
            }

            async handleSubmit(e) {
                e.preventDefault();

                const email = this.emailInput.value;
                const password = this.passwordInput.value;
                const name = this.nameInput.value;

                if (this.isRegister && !name) {
                    alert('Please enter your name');
                    return;
                }

                try {
                    const response = await fetch(this.isRegister ? '/api/register' : '/api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            email,
                            password,
                            ...(this.isRegister && { name, password_confirmation: password })
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Authentication failed');
                    }

                    // Store token and user info
                    localStorage.setItem('auth_token', data.token);
                    localStorage.setItem('user_id', data.user.id);
                    localStorage.setItem('user_name', data.user.name);

                    // Redirect to chat
                    window.location.href = '/chat';
                } catch (error) {
                    alert(error.message);
                }
            }
        }

        // Initialize auth app when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new AuthApp();
        });
    </script>
</body>
</html>
