function showForm(formId) {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    if (formId === 'login-form') {
        registerForm.classList.remove('active');
        loginForm.classList.add('active');
    } else {
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const activeForm = '<?= $activeForm; ?>';  // PHP variable injected here
    if (activeForm === 'login') {
        showForm('login-form');
    } else {
        showForm('register-form');
    }
});
