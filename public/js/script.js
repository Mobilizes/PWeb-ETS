document.addEventListener('DOMContentLoaded', function() {
  const checkboxes = document.querySelectorAll('.task-status');

  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const taskId = this.getAttribute('data-id');
      const newStatus = this.checked ? 1 : 0;
      console.log('Task ID:', taskId);
      console.log('New Status:', this.checked);

      fetch('update_status.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: taskId, status: newStatus })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Failed to update task status');
        }
      })
      .catch(error => console.error('Error:', error));
    });
  });
});
