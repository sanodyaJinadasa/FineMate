<footer>
  <p>© 2025 FineMate System</p>
  <p>
    <a href="#">Privacy Policy</a> |
    <a href="#">Terms of Service</a>
  </p>
</footer>

<script>
  <?php if ($updateSuccess): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Driver updated successfully',
      confirmButtonColor: '#0d6efd',
      timer: 2000
    }).then(() => {
      window.location.href = 'admin_view_drivers.php';
    });
  <?php elseif ($updateError): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: 'Error updating driver: <?= htmlspecialchars($updateError) ?>',
      confirmButtonColor: '#dc3545'
    });
  <?php endif; ?>
</script>

<script>
  const urlParams = new URLSearchParams(window.location.search);
  const msg = urlParams.get('msg');
  if (msg) {
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: msg,
      confirmButtonColor: '#0d6efd',
      timer: 3000
    });
    window.history.replaceState({}, document.title, window.location.pathname);
  }

  function confirmDelete(userId, driverName) {
    Swal.fire({
      title: 'Are you sure?',
      text: `Do you want to delete driver "${driverName}"? This action cannot be undone!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = `delete_driver.php?user_id=${userId}`;
      }
    });
  }
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.swal-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // prevent default link behavior
            const url = this.href;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete URL
                    window.location.href = url;
                }
            });
        });
    });
});
</script>
