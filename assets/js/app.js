// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function() {
  setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
      const bsAlert = new bootstrap.Alert(a);
      bsAlert.close();
    });
  }, 4000);
});
