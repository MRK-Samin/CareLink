document.addEventListener('DOMContentLoaded', function() {
    console.log('CareLink HMS Initialized');
    
    // Initialize all components
    initializeModals();
    initializeSearch();
    initializeFormValidation();
    initializeTooltips();
    initializeAnimations();
    initializeDataTables();
    initializeAlerts();
    initializeConfirmDialogs();
});

// ========== MODAL FUNCTIONS ==========
function initializeModals() {
    // Get all modal triggers
    const modalTriggers = document.querySelectorAll('[data-modal]');
    const modals = document.querySelectorAll('.modal');
    const modalCloses = document.querySelectorAll('.modal-close, [data-modal-close]');
    
    // Open modal
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                openModal(modalId);
            }
        });
    });
    
    // Close modal
    modalCloses.forEach(close => {
        close.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Close modal on outside click
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal.active');
            if (activeModal) {
                closeModal(activeModal.id);
            }
        }
    });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// ========== SEARCH FUNCTIONALITY ==========
function initializeSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');
    
    searchInputs.forEach(input => {
        const tableId = input.getAttribute('data-search');
        const table = document.getElementById(tableId);
        
        if (table) {
            input.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
}

// Live search for tables
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    
    if (input && table) {
        const filter = input.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }
}

// ========== FORM VALIDATION ==========
function initializeFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showAlert('Please fill all required fields correctly', 'danger');
            }
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = '#f5576c';
            
            // Add error message if doesn't exist
            if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                const error = document.createElement('span');
                error.className = 'error-message';
                error.textContent = 'This field is required';
                error.style.color = '#f5576c';
                error.style.fontSize = '12px';
                error.style.marginTop = '5px';
                field.parentNode.insertBefore(error, field.nextSibling);
            }
        } else {
            field.style.borderColor = '#e0e0e0';
            // Remove error message if exists
            if (field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')) {
                field.nextElementSibling.remove();
            }
        }
        
        // Reset border color on input
        field.addEventListener('input', function() {
            this.style.borderColor = '#e0e0e0';
            if (this.nextElementSibling && this.nextElementSibling.classList.contains('error-message')) {
                this.nextElementSibling.remove();
            }
        });
    });
    
    // Email validation
    const emailFields = form.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        if (field.value && !isValidEmail(field.value)) {
            isValid = false;
            field.style.borderColor = '#f5576c';
            showAlert('Please enter a valid email address', 'danger');
        }
    });
    
    // Phone validation
    const phoneFields = form.querySelectorAll('input[type="tel"]');
    phoneFields.forEach(field => {
        if (field.value && !isValidPhone(field.value)) {
            isValid = false;
            field.style.borderColor = '#f5576c';
            showAlert('Please enter a valid phone number', 'danger');
        }
    });
    
    return isValid;
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function isValidPhone(phone) {
    const re = /^[0-9+\-\s()]{10,}$/;
    return re.test(phone);
}

// ========== TOOLTIPS ==========
function initializeTooltips() {
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    
    tooltipTriggers.forEach(trigger => {
        const tooltipText = trigger.getAttribute('data-tooltip');
        
        const tooltip = document.createElement('span');
        tooltip.className = 'tooltip-text';
        tooltip.textContent = tooltipText;
        
        trigger.classList.add('tooltip');
        trigger.appendChild(tooltip);
    });
}

// ========== ANIMATIONS ==========
function initializeAnimations() {
    // Animate elements on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('[data-animate]');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 100) {
                const animation = element.getAttribute('data-animate');
                element.style.animation = `${animation} 0.6s ease forwards`;
            }
        });
    };
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Initial check
}

// ========== DATA TABLES ==========
function initializeDataTables() {
    // Add sorting functionality
    const tables = document.querySelectorAll('.data-table');
    
    tables.forEach(table => {
        const headers = table.querySelectorAll('thead th');
        
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                sortTable(table, index);
            });
        });
    });
}

function sortTable(table, columnIndex) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    const sorted = rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        
        // Try numeric comparison first
        if (!isNaN(aValue) && !isNaN(bValue)) {
            return parseFloat(aValue) - parseFloat(bValue);
        }
        
        // Fallback to string comparison
        return aValue.localeCompare(bValue);
    });
    
    // Check if already sorted ascending
    const isAscending = rows.every((row, i) => row === sorted[i]);
    
    if (isAscending) {
        sorted.reverse();
    }
    
    // Clear and re-append rows
    tbody.innerHTML = '';
    sorted.forEach(row => tbody.appendChild(row));
}

// ========== ALERTS ==========
function initializeAlerts() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            fadeOut(alert);
        }, 5000);
        
        // Add close button if doesn't exist
        if (!alert.querySelector('.alert-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'alert-close';
            closeBtn.innerHTML = '&times;';
            closeBtn.style.cssText = 'background: none; border: none; font-size: 24px; cursor: pointer; margin-left: auto; padding: 0 5px;';
            closeBtn.addEventListener('click', () => fadeOut(alert));
            alert.appendChild(closeBtn);
        }
    });
}

function showAlert(message, type = 'info') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type}`;
    alertContainer.innerHTML = `
        <i class="fas fa-${getAlertIcon(type)}"></i>
        <span>${message}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    alertContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
    
    document.body.appendChild(alertContainer);
    
    setTimeout(() => {
        fadeOut(alertContainer);
    }, 5000);
}

function getAlertIcon(type) {
    const icons = {
        success: 'check-circle',
        danger: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}

function fadeOut(element) {
    element.style.transition = 'opacity 0.5s ease';
    element.style.opacity = '0';
    setTimeout(() => {
        element.remove();
    }, 500);
}

// ========== CONFIRM DIALOGS ==========
function initializeConfirmDialogs() {
    const confirmLinks = document.querySelectorAll('[data-confirm]');
    
    confirmLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

// ========== LOADING SPINNER ==========
function showLoading() {
    const loader = document.createElement('div');
    loader.id = 'global-loader';
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    `;
    loader.innerHTML = '<div class="loading-spinner"></div>';
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.getElementById('global-loader');
    if (loader) {
        loader.remove();
    }
}

// ========== AJAX FUNCTIONS ==========
function ajaxRequest(url, method = 'GET', data = null, callback) {
    showLoading();
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: data ? JSON.stringify(data) : null
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (callback) callback(data);
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('An error occurred. Please try again.', 'danger');
    });
}

// ========== FORM SUBMISSION WITH AJAX ==========
function submitFormAjax(formId, successCallback) {
    const form = document.getElementById(formId);
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm(this)) {
                return;
            }
            
            const formData = new FormData(this);
            const url = this.action || window.location.href;
            
            showLoading();
            
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showAlert(data.message || 'Operation successful!', 'success');
                    if (successCallback) successCallback(data);
                } else {
                    showAlert(data.message || 'Operation failed!', 'danger');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showAlert('An error occurred. Please try again.', 'danger');
            });
        });
    }
}

// ========== AUTO-FILL PATIENT NUMBER ==========
function generatePatientNumber() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < 5; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}

// ========== DATE FORMATTING ==========
function formatDate(date) {
    const d = new Date(date);
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const year = d.getFullYear();
    return `${month}/${day}/${year}`;
}

function formatDateTime(date) {
    const d = new Date(date);
    const dateStr = formatDate(d);
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    return `${dateStr} ${hours}:${minutes}`;
}

// ========== NUMBER FORMATTING ==========
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// ========== PRINT FUNCTIONALITY ==========
function printDiv(divId) {
    const printContent = document.getElementById(divId);
    if (!printContent) return;
    
    const windowPrint = window.open('', '', 'width=800,height=600');
    windowPrint.document.write('<html><head><title>Print</title>');
    windowPrint.document.write('<link rel="stylesheet" href="../../assets/css/style.css">');
    windowPrint.document.write('</head><body>');
    windowPrint.document.write(printContent.innerHTML);
    windowPrint.document.write('</body></html>');
    windowPrint.document.close();
    
    setTimeout(() => {
        windowPrint.print();
        windowPrint.close();
    }, 250);
}

// ========== EXPORT TO CSV ==========
function exportTableToCSV(tableId, filename = 'export.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const csvRow = [];
        cols.forEach(col => {
            csvRow.push('"' + col.textContent.trim() + '"');
        });
        csv.push(csvRow.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// ========== PASSWORD STRENGTH CHECKER ==========
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    const strengthTexts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    const strengthColors = ['#f5576c', '#fa709a', '#fee140', '#43e97b', '#38f9d7'];
    
    return {
        score: strength,
        text: strengthTexts[strength - 1] || 'Very Weak',
        color: strengthColors[strength - 1] || '#f5576c'
    };
}

// ========== SMOOTH SCROLL ==========
function smoothScroll(targetId) {
    const target = document.getElementById(targetId);
    if (target) {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// ========== COPY TO CLIPBOARD ==========
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
        showAlert('Failed to copy to clipboard', 'danger');
    });
}

// ========== DEBOUNCE FUNCTION ==========
function debounce(func, delay = 300) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

// ========== THEME TOGGLE (Optional) ==========
function toggleTheme() {
    const currentTheme = localStorage.getItem('theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    document.body.classList.toggle('dark-theme');
    localStorage.setItem('theme', newTheme);
}

// Load saved theme
if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-theme');
}

// ========== UTILITY FUNCTIONS ==========

// Check if element is in viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Get URL parameters
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// ========== CONSOLE MESSAGE ==========
console.log('%c CareLink HMS ', 'background: linear-gradient(135deg, #667eea, #764ba2); color: white; font-size: 20px; padding: 10px; border-radius: 5px;');
console.log('%c Developed by Samin ', 'color: #667eea; font-size: 14px; padding: 5px;');

// ========== EXPORT FUNCTIONS FOR GLOBAL USE ==========
window.CareLink = {
    openModal,
    closeModal,
    showAlert,
    showLoading,
    hideLoading,
    ajaxRequest,
    submitFormAjax,
    generatePatientNumber,
    formatDate,
    formatDateTime,
    formatCurrency,
    formatNumber,
    printDiv,
    exportTableToCSV,
    checkPasswordStrength,
    smoothScroll,
    copyToClipboard,
    searchTable
};