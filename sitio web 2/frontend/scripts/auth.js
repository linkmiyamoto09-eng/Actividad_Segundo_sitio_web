document.getElementById('login-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const correo = document.getElementById('correo').value;
    const password = document.getElementById('password').value;

    const res = await fetch('../../backend/apis/auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ correo, password })
    });

    const data = await res.json();
    if (data.status === 'success') {
        window.location.href = data.redirect;
    } else {
        alert(data.message || 'Error al iniciar sesión');
    }
});

function logout() {
    window.location.href = 'login.html';
}
