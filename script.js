// You can enhance with confirmation before removal
document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
        if (!confirm('Are you sure you want to remove this item?')) {
            e.preventDefault();
        }
    });
});
