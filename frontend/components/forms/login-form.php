<form method="POST" action="/login" class="auth-form">
    <div class="form-group">
        <label for="email"><i class="fas fa-envelope"></i> Email</label>
        <input type="email" id="email" name="email" class="form-control" 
               placeholder="exemple@email.com" required data-validate="required|email">
        <div class="form-error" id="email_error"></div>
    </div>
    
    <div class="form-group">
        <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
        <div style="position: relative;">
            <input type="password" id="password" name="password" class="form-control" 
                   placeholder="••••••••" required data-validate="required|min:6">
            <button type="button" class="toggle-password" data-target="password" 
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #999;">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div class="form-error" id="password_error"></div>
    </div>
    
    <button type="submit" class="btn btn-primary btn-block">
        <i class="fas fa-sign-in-alt"></i> Se connecter
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordBtn = document.querySelector('.toggle-password');
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            if (target) {
                if (target.type === 'password') {
                    target.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    target.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            }
        });
    }
});
</script>