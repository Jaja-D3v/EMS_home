<div
    class="modal fade"
    id="sessionTimeoutModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-body text-center p-4 p-md-5">

                <div
                    class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-warning-subtle text-warning"
                    style="width: 70px; height: 70px;"
                >
                    <i class="bi bi-clock-history fs-2"></i>
                </div>

                <h4 class="fw-bold mb-2">
                    Are you still here?
                </h4>

                <p class="text-body-secondary mb-4">
                    Your session has been inactive for 4 minutes.
                    Would you like to continue your session?
                </p>

                <div class="mb-4">
                    <span
                        id="sessionCountdown"
                        class="fs-1 fw-bold text-danger"
                    >
                        60
                    </span>

                    <div class="small text-body-secondary">
                        seconds remaining
                    </div>
                </div>

                <button
                    type="button"
                    class="btn btn-primary px-4"
                    onclick="extendSession()"
                >
                    <i class="bi bi-arrow-clockwise me-2"></i>
                    Extend Session
                </button>

            </div>

        </div>
    </div>
</div>

<script>
const INACTIVITY_LIMIT = 4 * 60 * 1000; // 4 minutes
const WARNING_TIME = 60; // 60 seconds

let inactivityTimer;
let countdownTimer;
let countdown = WARNING_TIME;

function resetInactivityTimer() {
    clearTimeout(inactivityTimer);

    inactivityTimer = setTimeout(() => {
        showSessionWarning();
    }, INACTIVITY_LIMIT);
}

function showSessionWarning() {

    countdown = WARNING_TIME;

    const modal = new bootstrap.Modal(
        document.getElementById('sessionTimeoutModal'),
        {
            backdrop: 'static',
            keyboard: false
        }
    );

    document.getElementById('sessionCountdown').textContent = countdown;

    modal.show();

    clearInterval(countdownTimer);

    countdownTimer = setInterval(() => {

        countdown--;

        document.getElementById('sessionCountdown').textContent = countdown;

        if (countdown <= 0) {

            clearInterval(countdownTimer);

            logoutUser();
        }

    }, 1000);
}

function extendSession() {

    clearInterval(countdownTimer);

    fetch('backend/authentication/Auth.php', {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => {

        if (!response.ok) {
            throw new Error('Session expired');
        }

        return response.json();

    })
    .then(data => {

        if (data.success) {

            const modalElement =
                document.getElementById('sessionTimeoutModal');

            const modal =
                bootstrap.Modal.getInstance(modalElement);

            if (modal) {
                modal.hide();
            }

            resetInactivityTimer();

        } else {
            logoutUser();
        }

    })
    .catch(() => {
        logoutUser();
    });
}

function logoutUser() {

    clearInterval(countdownTimer);

    window.location.href =
        'authentication/login.html?error=session_expired';
}

/* Detect user activity */
[
    'mousemove',
    'mousedown',
    'keydown',
    'scroll',
    'touchstart',
    'click'
].forEach(event => {

    document.addEventListener(event, () => {

        const modal =
            document.getElementById('sessionTimeoutModal');

        if (!modal.classList.contains('show')) {
            resetInactivityTimer();
        }

    }, { passive: true });

});

/* Start timer */
resetInactivityTimer();
</script>