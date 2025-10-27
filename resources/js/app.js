    import './bootstrap';


    const instructorId = window.instructorId;

    window.Echo.private(`instructor.${instructorId}`)
        .listen('.CourseEnrolled', (e) => {
            const message = `${e.student_name} enrolled in ${e.course_title} at ${e.enrolled_at}`;
            showToast(message);
        });

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-bg-success border-0 show position-fixed bottom-0 end-0 m-4';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 7000);
    }
