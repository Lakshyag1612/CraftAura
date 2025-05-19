function toggleForm(formType) {
    const container = document.querySelector('.main-container');
    if (formType === 'register') {
        container.classList.add('show-register');
    } else {
        container.classList.remove('show-register');
    }
}
function showForm(formId) {
    document.getElementById('login-form').classList.remove('active');
    document.getElementById('register-form').classList.remove('active');
    document.getElementById(formId).classList.add('active');
}

// Optional: trigger based on PHP session
document.addEventListener('DOMContentLoaded', function() {
    const isRegister = '<?= $activeForm; ?>' === 'register';
    toggleForm(isRegister ? 'register' : 'login');
});
