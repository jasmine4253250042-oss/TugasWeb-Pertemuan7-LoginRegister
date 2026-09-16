document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Hilangkan pesan alert otomatis setelah 4 detik
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }, 4000); // 4000ms = 4 detik
    });

    // 2. Validasi real-time: Cek apakah password dan konfirmasi password cocok
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    if (passwordInput && confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (this.value === passwordInput.value) {
                    this.style.borderColor = '#28a745'; // Hijau jika cocok
                } else {
                    this.style.borderColor = '#dc3545'; // Merah jika tidak cocok
                }
            } else {
                this.style.borderColor = '#e0e0e0'; // Reset warna
            }
        });
    }

});