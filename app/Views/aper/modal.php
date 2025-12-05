<!-- evaluation_modal.php -->

<!-- Bootstrap 5 Modal -->
<div class="modal fade" id="evaluationModal" tabindex="-1" aria-labelledby="evaluationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="evaluationModalLabel">
                    Staff Evaluation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- BODY (Dynamic Content Will Load Here) -->
            <div class="modal-body" id="evaluationModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3">Loading evaluation form…</p>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button id="saveEvaluationBtn" class="btn btn-success d-none">
                    Submit Evaluation
                </button>
            </div>

        </div>
    </div>
</div>

<script>
/** 
 * Load Evaluation Form Into Modal
 * staffId: selected staff ID
 * category: academic / non_academic / senior_admin etc.
 */
function openEvaluationModal(staffId, category) {
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('evaluationModal'));
    modal.show();

    // Reset button state
    document.getElementById('saveEvaluationBtn').classList.add('d-none');

    // Load content via AJAX
    fetch("<?= base_url('evaluation/loadForm') ?>/" + staffId + "/" + category)
        .then(response => response.text())
        .then(html => {
            document.getElementById('evaluationModalBody').innerHTML = html;
            document.getElementById('saveEvaluationBtn').classList.remove('d-none');
        })
        .catch(error => {
            document.getElementById('evaluationModalBody').innerHTML =
                "<div class='alert alert-danger'>Unable to load evaluation form.</div>";
        });
}

/**
 * Submit Evaluation (AJAX)
 */
document.getElementById('saveEvaluationBtn').addEventListener('click', function () {
    let form = document.querySelector('#evaluationForm');

    if (!form) {
        alert('Form not loaded.');
        return;
    }

    let formData = new FormData(form);

    fetch("<?= base_url('evaluation/submit') ?>", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            alert("Evaluation submitted successfully!");
            location.reload();
        } else {
            alert("Error: " + res.message);
        }
    })
    .catch(() => alert("An unexpected error occurred."));
});
</script>
