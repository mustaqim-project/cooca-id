import Swal from 'sweetalert2';

export function initFormConfirmations() {
    // Submit / Update Confirmations
    const submitForms = document.querySelectorAll('.form-confirm-submit');
    submitForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save these changes?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb', // primary-600
                cancelButtonColor: '#64748b', // surface-500
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Delete Confirmations
    const deleteForms = document.querySelectorAll('.form-confirm-delete');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626', // red-600
                cancelButtonColor: '#64748b', // surface-500
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
}
