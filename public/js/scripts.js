/**
 * scripts.js
 * Main JavaScript file for Sistem Pengelolaan dan Rekomendasi Kegiatan Ekstrakurikuler
 * MA Modern Miftahussa'adah Cimahi
 */

// Wait for DOM to be loaded before executing scripts
document.addEventListener('DOMContentLoaded', function() {
     initSidebarToggle();
     initDataTables();
     initTooltips();
     initDropdowns();
     initSurveyForm();
     initAttendanceForm();
     initCharts();
     initDeleteConfirmation();
     initDatepickers();
 });
 
 /**
  * Initialize sidebar toggle functionality
  */
 function initSidebarToggle() {
     const sidebarToggle = document.body.querySelector('#sidebarToggle');
     if (sidebarToggle) {
         // Toggle the side navigation
         sidebarToggle.addEventListener('click', event => {
             event.preventDefault();
             document.body.classList.toggle('sb-sidenav-toggled');
             
             // Save the toggle state in localStorage
             localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
         });
         
         // Check if the toggle state is saved in localStorage
         const toggleState = localStorage.getItem('sb|sidebar-toggle');
         if (toggleState === 'true') {
             document.body.classList.add('sb-sidenav-toggled');
         }
     }
 }
 
 /**
  * Initialize DataTables on tables with 'datatable' class
  */
 function initDataTables() {
     const dataTables = document.querySelectorAll('.datatable');
     if (dataTables.length > 0) {
         dataTables.forEach(table => {
             new simpleDatatables.DataTable(table, {
                 searchable: true,
                 fixedHeight: false,
                 perPage: 10,
                 perPageSelect: [5, 10, 15, 20, 25],
                 labels: {
                     placeholder: "Cari...",
                     perPage: "{select} data per halaman",
                     noRows: "Tidak ada data yang ditemukan",
                     info: "Menampilkan {start} sampai {end} dari {rows} data",
                 }
             });
         });
     }
 }
 
 /**
  * Initialize Bootstrap tooltips
  */
 function initTooltips() {
     const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
     tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl);
     });
 }
 
 /**
  * Initialize Bootstrap dropdowns
  */
 function initDropdowns() {
     const dropdownTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
     dropdownTriggerList.map(function (dropdownTriggerEl) {
         return new bootstrap.Dropdown(dropdownTriggerEl);
     });
 }
 
 /**
  * Initialize Survey Form interactions
  */
 function initSurveyForm() {
     const sliders = document.querySelectorAll('.interest-slider');
     
     sliders.forEach(slider => {
         const displayId = slider.dataset.display;
         const display = document.getElementById(displayId);
         
         if (display) {
             // Set initial value
             display.textContent = slider.value;
             
             // Update when slider changes
             slider.addEventListener('input', function() {
                 display.textContent = this.value;
             });
         }
     });
 }
 
 /**
  * Initialize Attendance Form interactions
  */
 function initAttendanceForm() {
     const attendanceOptions = document.querySelectorAll('.attendance-option');
     const statusInputs = document.querySelectorAll('input[name="status[]"]');
     
     // Update attendance option selection
     attendanceOptions.forEach(option => {
         option.addEventListener('click', function() {
             const value = this.dataset.value;
             const index = this.dataset.index;
             const statusInput = document.getElementById('status-' + index);
             
             if (statusInput) {
                 // Remove selected class from siblings
                 const optionsContainer = this.parentElement.parentElement;
                 const siblingOptions = optionsContainer.querySelectorAll('.attendance-option');
                 siblingOptions.forEach(sib => sib.classList.remove('selected'));
                 
                 // Add selected class to this option
                 this.classList.add('selected');
                 
                 // Update hidden input value
                 statusInput.value = value;
                 
                 // Update statistics if available
                 updateAttendanceStatistics();
             }
         });
     });
     
     // Mass action buttons
     const massActionButtons = document.querySelectorAll('.mass-action');
     massActionButtons.forEach(button => {
         button.addEventListener('click', function() {
             const status = this.dataset.status;
             
             // Update all attendance options
             attendanceOptions.forEach(option => {
                 // Remove selected class from all options
                 option.classList.remove('selected');
                 
                 // Add selected class to options matching the target status
                 if (option.dataset.value === status) {
                     option.classList.add('selected');
                     
                     // Update hidden input
                     const index = option.dataset.index;
                     const statusInput = document.getElementById('status-' + index);
                     if (statusInput) {
                         statusInput.value = status;
                     }
                 }
             });
             
             // Update statistics if available
             updateAttendanceStatistics();
         });
     });
     
     // Initialize statistics
     updateAttendanceStatistics();
 }
 
 /**
  * Update attendance statistics
  */
 function updateAttendanceStatistics() {
     const statusInputs = document.querySelectorAll('input[name="status[]"]');
     if (statusInputs.length === 0) return;
     
     const statHadir = document.getElementById('stat-hadir');
     const statIzin = document.getElementById('stat-izin');
     const statSakit = document.getElementById('stat-sakit');
     const statAlpha = document.getElementById('stat-alpha');
     
     if (!statHadir || !statIzin || !statSakit || !statAlpha) return;
     
     let hadirCount = 0;
     let izinCount = 0;
     let sakitCount = 0;
     let alphaCount = 0;
     
     statusInputs.forEach(input => {
         const value = input.value;
         if (value === 'hadir') hadirCount++;
         else if (value === 'izin') izinCount++;
         else if (value === 'sakit') sakitCount++;
         else if (value === 'alpha') alphaCount++;
     });
     
     statHadir.textContent = hadirCount;
     statIzin.textContent = izinCount;
     statSakit.textContent = sakitCount;
     statAlpha.textContent = alphaCount;
 }
 
 /**
  * Initialize Charts if Chart.js is available and necessary elements exist
  */
 function initCharts() {
     if (typeof Chart === 'undefined') return;
     
     // Attendance Chart
     const attendanceChartElement = document.getElementById('overallAttendanceChart');
     if (attendanceChartElement) {
         const ctx = attendanceChartElement.getContext('2d');
         
         // Get data from data attributes or use default values
         const hadir = parseInt(attendanceChartElement.dataset.hadir || 0);
         const izin = parseInt(attendanceChartElement.dataset.izin || 0);
         const sakit = parseInt(attendanceChartElement.dataset.sakit || 0);
         const alpha = parseInt(attendanceChartElement.dataset.alpha || 0);
         
         const attendanceChart = new Chart(ctx, {
             type: 'pie',
             data: {
                 labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                 datasets: [{
                     data: [hadir, izin, sakit, alpha],
                     backgroundColor: [
                         '#198754', // Success/Green - Hadir
                         '#0d6efd', // Primary/Blue - Izin
                         '#ffc107', // Warning/Yellow - Sakit
                         '#dc3545'  // Danger/Red - Alpha
                     ],
                     borderWidth: 1
                 }]
             },
             options: {
                 responsive: true,
                 maintainAspectRatio: false,
                 plugins: {
                     legend: {
                         position: 'right',
                     }
                 }
             }
         });
     }
     
     // Enrollment Chart
     const enrollmentChartElement = document.getElementById('enrollmentChart');
     if (enrollmentChartElement) {
         const ctx = enrollmentChartElement.getContext('2d');
         
         // Get data from data attributes
         const labels = JSON.parse(enrollmentChartElement.dataset.labels || '[]');
         const data = JSON.parse(enrollmentChartElement.dataset.values || '[]');
         const bgColor = enrollmentChartElement.dataset.bgColor || 'rgba(0, 123, 255, 0.7)';
         const borderColor = enrollmentChartElement.dataset.borderColor || 'rgba(0, 123, 255, 1)';
         
         const enrollmentChart = new Chart(ctx, {
             type: 'bar',
             data: {
                 labels: labels,
                 datasets: [{
                     label: 'Jumlah Siswa',
                     data: data,
                     backgroundColor: bgColor,
                     borderColor: borderColor,
                     borderWidth: 1
                 }]
             },
             options: {
                 responsive: true,
                 maintainAspectRatio: false,
                 plugins: {
                     legend: {
                         display: false
                     },
                     tooltip: {
                         callbacks: {
                             label: function(context) {
                                 return `${context.parsed.y} Siswa`;
                             }
                         }
                     }
                 },
                 scales: {
                     y: {
                         beginAtZero: true,
                         ticks: {
                             precision: 0
                         }
                     }
                 }
             }
         });
     }
 }
 
 /**
  * Initialize Delete Confirmation dialogs
  */
 function initDeleteConfirmation() {
     const deleteButtons = document.querySelectorAll('.btn-delete');
     
     deleteButtons.forEach(button => {
         button.addEventListener('click', function(e) {
             e.preventDefault();
             
             const confirmationMessage = this.dataset.message || 'Apakah Anda yakin ingin menghapus data ini?';
             
             if (confirm(confirmationMessage)) {
                 const form = this.closest('form');
                 if (form) form.submit();
             }
         });
     });
 }
 
 /**
  * Initialize Datepickers if Flatpickr is available
  */
 function initDatepickers() {
     if (typeof flatpickr === 'undefined') return;
     
     // Standard datepicker
     const datepickers = document.querySelectorAll('.datepicker');
     if (datepickers.length > 0) {
         flatpickr(datepickers, {
             dateFormat: "Y-m-d",
             locale: {
                 firstDayOfWeek: 1,
                 weekdays: {
                     shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                     longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
                 },
                 months: {
                     shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                     longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
                 }
             }
         });
     }
     
     // Date-time picker
     const datetimepickers = document.querySelectorAll('.datetimepicker');
     if (datetimepickers.length > 0) {
         flatpickr(datetimepickers, {
             enableTime: true,
             dateFormat: "Y-m-d H:i",
             time_24hr: true,
             locale: {
                 firstDayOfWeek: 1,
                 weekdays: {
                     shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                     longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
                 },
                 months: {
                     shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                     longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
                 }
             }
         });
     }
 }
 
 /**
  * Show attendance detail row
  * @param {string} studentId 
  */
 function toggleAttendanceDetail(studentId) {
     const detailRow = document.getElementById('detail-' + studentId);
     const button = document.querySelector(`[data-student-id="${studentId}"]`);
     
     if (detailRow) {
         detailRow.classList.toggle('show');
         
         if (button) {
             const icon = button.querySelector('i');
             if (icon) {
                 if (detailRow.classList.contains('show')) {
                     icon.classList.remove('fa-eye');
                     icon.classList.add('fa-eye-slash');
                 } else {
                     icon.classList.remove('fa-eye-slash');
                     icon.classList.add('fa-eye');
                 }
             }
         }
     }
 }
 
 /**
  * Toggle role-specific form sections in registration
  */
 function toggleRegistrationForms() {
     const roleSelect = document.getElementById('role');
     const siswaForm = document.getElementById('siswa-form');
     const pembinaForm = document.getElementById('pembina-form');
     
     if (!roleSelect || !siswaForm || !pembinaForm) return;
     
     const selectedRole = roleSelect.value;
     
     if (selectedRole === 'siswa') {
         siswaForm.style.display = 'block';
         pembinaForm.style.display = 'none';
     } else if (selectedRole === 'pembina') {
         siswaForm.style.display = 'none';
         pembinaForm.style.display = 'block';
     } else {
         siswaForm.style.display = 'none';
         pembinaForm.style.display = 'none';
     }
 }
 
 // Initialize role-specific form sections on page load and on change
document.addEventListener('DOMContentLoaded', function() {
     const roleSelect = document.getElementById('role');
     if (roleSelect) {
         toggleRegistrationForms();
         roleSelect.addEventListener('change', toggleRegistrationForms);
     }
 });
 
 /**
  * Format number with thousands separator
  * @param {number} number 
  * @param {number} decimals 
  * @return {string}
  */
 function formatNumber(number, decimals = 0) {
     return number.toLocaleString('id-ID', {
         minimumFractionDigits: decimals,
         maximumFractionDigits: decimals
     });
 }
 
 /**
  * Format date to Indonesian format
  * @param {Date|string} date 
  * @return {string}
  */
 function formatDate(date) {
     if (!(date instanceof Date)) {
         date = new Date(date);
     }
     
     const options = { 
         day: 'numeric', 
         month: 'long', 
         year: 'numeric' 
     };
     
     return date.toLocaleDateString('id-ID', options);
 }
 
 /**
  * Format datetime to Indonesian format
  * @param {Date|string} date 
  * @return {string}
  */
 function formatDateTime(date) {
     if (!(date instanceof Date)) {
         date = new Date(date);
     }
     
     const options = { 
         day: 'numeric', 
         month: 'long', 
         year: 'numeric',
         hour: '2-digit',
         minute: '2-digit'
     };
     
     return date.toLocaleDateString('id-ID', options);
 }
 
 /**
  * Get URL parameters
  * @param {string} param 
  * @return {string|null}
  */
 function getUrlParameter(param) {
     const urlParams = new URLSearchParams(window.location.search);
     return urlParams.get(param);
 }
 
 /**
  * Show alert message
  * @param {string} message 
  * @param {string} type 
  * @param {number} duration 
  */
 function showAlert(message, type = 'success', duration = 3000) {
     // Create alert element
     const alertEl = document.createElement('div');
     alertEl.className = `alert alert-${type} alert-dismissible fade show fixed-top mx-auto mt-3`;
     alertEl.style.maxWidth = '500px';
     alertEl.style.zIndex = '9999';
     
     // Add content
     alertEl.innerHTML = `
         ${message}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     `;
     
     // Append to body
     document.body.appendChild(alertEl);
     
     // Auto dismiss
     setTimeout(() => {
         const bsAlert = new bootstrap.Alert(alertEl);
         bsAlert.close();
     }, duration);
 }
 
 /**
  * Copy text to clipboard
  * @param {string} text 
  * @param {Function} callback 
  */
 function copyToClipboard(text, callback) {
     if (navigator.clipboard) {
         navigator.clipboard.writeText(text)
             .then(() => {
                 if (callback) callback(true);
             })
             .catch(() => {
                 if (callback) callback(false);
             });
     } else {
         // Fallback for older browsers
         const textarea = document.createElement('textarea');
         textarea.value = text;
         textarea.style.position = 'fixed';
         textarea.style.opacity = 0;
         
         document.body.appendChild(textarea);
         textarea.select();
         
         try {
             const success = document.execCommand('copy');
             if (callback) callback(success);
         } catch (err) {
             if (callback) callback(false);
         }
         
         document.body.removeChild(textarea);
     }
 }
 
 /**
  * Print element
  * @param {string} elementId 
  * @param {string} title 
  */
 function printElement(elementId, title = '') {
     const element = document.getElementById(elementId);
     if (!element) return;
     
     const printWindow = window.open('', '_blank');
     
     printWindow.document.write(`
         <!DOCTYPE html>
         <html>
         <head>
             <title>${title}</title>
             <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
             <style>
                 body {
                     font-family: 'Arial', sans-serif;
                     padding: 20px;
                 }
                 @media print {
                     body {
                         padding: 0;
                     }
                     .no-print {
                         display: none !important;
                     }
                 }
             </style>
         </head>
         <body>
             <div class="container">
                 <div class="no-print mb-3">
                     <button class="btn btn-primary" onclick="window.print()">
                         <i class="fas fa-print"></i> Cetak
                     </button>
                     <button class="btn btn-secondary" onclick="window.close()">
                         <i class="fas fa-times"></i> Tutup
                     </button>
                 </div>
                 ${element.innerHTML}
             </div>
             <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
             <script>
                 window.onload = function() {
                     setTimeout(function() {
                         window.print();
                     }, 500);
                 };
             </script>
         </body>
         </html>
     `);
     
     printWindow.document.close();
 }
 
 /**
  * Generate random password
  * @param {number} length 
  * @return {string}
  */
 function generatePassword(length = 8) {
     const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
     let password = "";
     
     for (let i = 0; i < length; i++) {
         const randomIndex = Math.floor(Math.random() * charset.length);
         password += charset.charAt(randomIndex);
     }
     
     return password;
 }
 
 /**
  * Limit text to specified length
  * @param {string} text 
  * @param {number} maxLength 
  * @return {string}
  */
 function limitText(text, maxLength = 100) {
     if (!text) return '';
     if (text.length <= maxLength) return text;
     
     return text.substring(0, maxLength) + '...';
 }
 
 /**
  * Calculate age from birthdate
  * @param {Date|string} birthdate 
  * @return {number}
  */
 function calculateAge(birthdate) {
     if (!(birthdate instanceof Date)) {
         birthdate = new Date(birthdate);
     }
     
     const today = new Date();
     let age = today.getFullYear() - birthdate.getFullYear();
     const monthDiff = today.getMonth() - birthdate.getMonth();
     
     if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
         age--;
     }
     
     return age;
 }
 
 /**
  * Generate unique ID
  * @return {string}
  */
 function generateUniqueId() {
     return 'id-' + Math.random().toString(36).substring(2, 11);
 }
 
 /**
  * Get current academic year
  * @return {string}
  */
 function getCurrentAcademicYear() {
     const today = new Date();
     const year = today.getFullYear();
     const month = today.getMonth() + 1; // January is 0
     
     // Academic year starts in July
     if (month >= 7) {
         return `${year}/${year + 1}`;
     } else {
         return `${year - 1}/${year}`;
     }
 }
 
 /**
  * Toggle password visibility
  * @param {string} inputId 
  * @param {string} toggleId 
  */
 function togglePasswordVisibility(inputId, toggleId) {
     const input = document.getElementById(inputId);
     const toggle = document.getElementById(toggleId);
     
     if (!input || !toggle) return;
     
     toggle.addEventListener('click', function() {
         if (input.type === 'password') {
             input.type = 'text';
             this.innerHTML = '<i class="fas fa-eye-slash"></i>';
         } else {
             input.type = 'password';
             this.innerHTML = '<i class="fas fa-eye"></i>';
         }
     });
 }
 
 /**
  * Check if a string is valid JSON
  * @param {string} str 
  * @return {boolean}
  */
 function isValidJson(str) {
     try {
         JSON.parse(str);
         return true;
     } catch (e) {
         return false;
     }
 }
 
 /**
  * Truncate a string in the middle
  * @param {string} str 
  * @param {number} maxLength 
  * @return {string}
  */
 function truncateMiddle(str, maxLength = 30) {
     if (!str) return '';
     if (str.length <= maxLength) return str;
     
     const ellipsis = '...';
     const charsToShow = maxLength - ellipsis.length;
     const frontChars = Math.ceil(charsToShow / 2);
     const backChars = Math.floor(charsToShow / 2);
     
     return str.substr(0, frontChars) + ellipsis + str.substr(str.length - backChars);
 }
 
 /**
  * Convert string to title case
  * @param {string} str 
  * @return {string}
  */
 function toTitleCase(str) {
     if (!str) return '';
     
     return str.replace(
         /\w\S*/g,
         function(txt) {
             return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
         }
     );
 }
 
 /**
  * Convert Naive Bayes score to percentage
  * @param {number} score 
  * @return {string}
  */
 function nbScoreToPercentage(score) {
     // Naive Bayes scores are typically very small, so we scale them
     // to get a percentage value between 0 and 100
     let percentage = score * 100;
     
     // If the percentage is very small, we can adjust it for better UX
     if (percentage < 0.1) {
         percentage = Math.round(percentage * 1000) / 10;
     } else {
         percentage = Math.round(percentage);
     }
     
     return percentage.toFixed(1) + '%';
 }
 
 /**
  * Update progress bar width
  * @param {string} id 
  * @param {number} value 
  * @param {number} max 
  */
 function updateProgressBar(id, value, max) {
     const progressBar = document.getElementById(id);
     if (!progressBar) return;
     
     const percentage = (value / max) * 100;
     progressBar.style.width = percentage + '%';
     progressBar.setAttribute('aria-valuenow', value);
     progressBar.setAttribute('aria-valuemax', max);
     
     // Update color based on percentage
     if (percentage >= 90) {
         progressBar.className = 'progress-bar bg-danger';
     } else if (percentage >= 70) {
         progressBar.className = 'progress-bar bg-warning';
     } else {
         progressBar.className = 'progress-bar bg-success';
     }
 }
 
 /**
  * Show loading spinner
  * @param {string} containerId 
  * @param {string} message 
  */
 function showLoading(containerId, message = 'Memuat...') {
     const container = document.getElementById(containerId);
     if (!container) return;
     
     container.innerHTML = `
         <div class="text-center my-5">
             <div class="spinner-border text-primary mb-3" role="status">
                 <span class="visually-hidden">Loading...</span>
             </div>
             <p>${message}</p>
         </div>
     `;
 }
 
 /**
  * Hide loading spinner and show content
  * @param {string} containerId 
  * @param {string} content 
  */
 function hideLoading(containerId, content = '') {
     const container = document.getElementById(containerId);
     if (!container) return;
     
     container.innerHTML = content;
 }
 
 /**
  * Scroll to element
  * @param {string} elementId 
  * @param {number} offset 
  * @param {number} duration 
  */
 function scrollToElement(elementId, offset = 0, duration = 500) {
     const element = document.getElementById(elementId);
     if (!element) return;
     
     const elementPosition = element.getBoundingClientRect().top;
     const offsetPosition = elementPosition + window.pageYOffset - offset;
     
     window.scrollTo({
         top: offsetPosition,
         behavior: 'smooth'
     });
 }
 
 /**
  * Disable form during submission
  * @param {string} formId 
  * @param {boolean} disabled 
  * @param {string} submitBtnText 
  * @param {string} loadingBtnText 
  */
 function disableForm(formId, disabled = true, submitBtnText = 'Simpan', loadingBtnText = 'Menyimpan...') {
     const form = document.getElementById(formId);
     if (!form) return;
     
     const inputs = form.querySelectorAll('input, select, textarea, button');
     const submitBtn = form.querySelector('button[type="submit"]');
     
     inputs.forEach(input => {
         input.disabled = disabled;
     });
     
     if (submitBtn) {
         if (disabled) {
             submitBtn.innerHTML = `
                 <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                 ${loadingBtnText}
             `;
         } else {
             submitBtn.innerHTML = submitBtnText;
         }
     }
 }
 
 /**
  * Calculate average score
  * @param {array} scores 
  * @return {number}
  */
 function calculateAverage(scores) {
     if (!scores || scores.length === 0) return 0;
     
     const sum = scores.reduce((acc, curr) => acc + curr, 0);
     return sum / scores.length;
 }
 
 /**
  * Handle notification click to mark as read
  */
 function initializeNotifications() {
     const notificationItems = document.querySelectorAll('.notification-item');
     
     notificationItems.forEach(item => {
         item.addEventListener('click', function(e) {
             const notificationId = this.dataset.id;
             const url = this.dataset.url;
             
             // Mark as read via AJAX
             if (notificationId) {
                 fetch(`/notifications/${notificationId}/read`, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                         'Accept': 'application/json',
                         'Content-Type': 'application/json'
                     }
                 })
                 .then(response => response.json())
                 .then(data => {
                     // Update UI
                     this.classList.remove('unread');
                     
                     // Update notification count
                     const notificationCount = document.getElementById('notification-count');
                     if (notificationCount) {
                         const count = parseInt(notificationCount.textContent) - 1;
                         notificationCount.textContent = count > 0 ? count : '';
                         
                         if (count <= 0) {
                             notificationCount.classList.add('d-none');
                         }
                     }
                     
                     // Redirect if URL is provided
                     if (url) {
                         window.location.href = url;
                     }
                 })
                 .catch(error => {
                     console.error('Error marking notification as read:', error);
                     
                     // Redirect anyway if URL is provided
                     if (url) {
                         window.location.href = url;
                     }
                 });
                 
                 e.preventDefault();
             }
         });
     });
 }
 
 // Initialize notifications on page load
 document.addEventListener('DOMContentLoaded', function() {
     initializeNotifications();
 });