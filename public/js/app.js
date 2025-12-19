// Global AJAX setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Toast notification system
window.showToast = function(type, message, duration = 5000) {
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    $('#toast-container').append(toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        delay: duration
    });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function () {
        $(this).remove();
    });
};

// Copy to clipboard function
window.copyToClipboard = function(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('success', 'Link copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
        showToast('error', 'Failed to copy link.');
    });
};

// Share functionality
window.shareContent = function(type, id) {
    $.ajax({
        url: `/user/${type}/${id}/share`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Show share modal or options
                showShareOptions(response.links);
            }
        },
        error: function(xhr) {
            showToast('error', 'Failed to load share options.');
        }
    });
};

function showShareOptions(links) {
    const modalHtml = `
        <div class="modal fade" id="shareModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Share</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="share-buttons mb-4">
                            <a href="${links.whatsapp}" target="_blank" class="btn btn-success">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="${links.email}" class="btn btn-primary">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <button onclick="copyToClipboard('${links.link}')" class="btn btn-info">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="${links.link}" readonly>
                            <button class="btn btn-outline-secondary" onclick="copyToClipboard('${links.link}')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('shareModal'));
    modal.show();
    
    $('#shareModal').on('hidden.bs.modal', function () {
        $(this).remove();
    });
}

// Date formatting
window.formatDate = function(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Form validation helper
window.validateForm = function(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input, select, textarea');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.checkValidity()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
};

// Loading state helper
window.setLoadingState = function(buttonId, isLoading) {
    const button = document.getElementById(buttonId);
    const spinner = button.querySelector('.spinner-border');
    const text = button.querySelector('.btn-text');
    
    if (isLoading) {
        button.disabled = true;
        spinner.classList.remove('d-none');
        text.textContent = 'Loading...';
    } else {
        button.disabled = false;
        spinner.classList.add('d-none');
        text.textContent = 'Submit';
    }
};

// Document ready
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-bs-toggle="popover"]').popover();
    
    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
    
    // Confirm delete actions
    $('.confirm-delete').click(function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const name = $(this).data('name');
        
        if (confirm(`Are you sure you want to delete ${name}?`)) {
            window.location.href = url;
        }
    });
});