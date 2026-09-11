// ─── COLOR DROPDOWN ON CHANGE ─────────────────
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', () => {
        const val = select.value.toLowerCase();
        select.className = 'status-select ' + val;
    });
});

// ─── SAVE STATUS ──────────────────────────────
document.querySelectorAll('.save-status-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const orderId  = btn.dataset.id;
        const select   = document.querySelector(`.status-select[data-id="${orderId}"]`);
        const newStatus = select.value;

        // Loading state
        btn.textContent = '...';
        btn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('status', newStatus);
            console.log(formData);
            const response = await fetch('../controllers/update_order_status.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.status === 'success') {
                showToast(result.message, 'success');
            } else {
                showToast(result.message || 'Could not update status', 'error');
            }

        } catch (error) {
            showToast('Network error. Try again.', 'error');
        } finally {
            btn.textContent = 'Save';
            btn.disabled = false;
        }
    });
});

// ─── TOAST ────────────────────────────────────
function showToast(message, type = '') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    setTimeout(() => toast.classList.remove('show'), 3000);
}