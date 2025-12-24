/**
 * File: script.js
 * Deskripsi: Custom JavaScript untuk Sistem Pakar Troubleshooting Komputer
 * Fitur: Validasi form, konfirmasi hapus, toggle sidebar, animasi
 */

// ==================== DOCUMENT READY ====================
document.addEventListener("DOMContentLoaded", function () {
  // Initialize tooltips (Bootstrap 5)
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers (Bootstrap 5)
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Auto-hide alerts after 5 seconds
  autoHideAlerts();

  // Add fade-in animation to cards
  addFadeInAnimation();

  // Sidebar toggle for mobile
  initSidebarToggle();

  // Form validation
  initFormValidation();

  // Delete confirmation
  initDeleteConfirmation();
});

// ==================== AUTO HIDE ALERTS ====================
function autoHideAlerts() {
  const alerts = document.querySelectorAll(".alert:not(.alert-permanent)");

  alerts.forEach(function (alert) {
    setTimeout(function () {
      // Fade out animation
      alert.style.transition = "opacity 0.5s ease";
      alert.style.opacity = "0";

      setTimeout(function () {
        alert.remove();
      }, 500);
    }, 5000); // 5 seconds
  });
}

// ==================== FADE IN ANIMATION ====================
function addFadeInAnimation() {
  const cards = document.querySelectorAll(".card");

  cards.forEach(function (card, index) {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";

    setTimeout(function () {
      card.style.transition = "opacity 0.5s ease, transform 0.5s ease";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 100); // Stagger animation
  });
}

// ==================== SIDEBAR TOGGLE ====================
function initSidebarToggle() {
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("active");

      // Add overlay for mobile
      if (window.innerWidth <= 768) {
        toggleOverlay();
      }
    });
  }
}

function toggleOverlay() {
  let overlay = document.getElementById("sidebarOverlay");

  if (!overlay) {
    // Create overlay
    overlay = document.createElement("div");
    overlay.id = "sidebarOverlay";
    overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        `;
    document.body.appendChild(overlay);

    // Close sidebar when clicking overlay
    overlay.addEventListener("click", function () {
      document.getElementById("sidebar").classList.remove("active");
      overlay.style.display = "none";
    });
  }

  // Toggle overlay
  if (overlay.style.display === "none" || overlay.style.display === "") {
    overlay.style.display = "block";
  } else {
    overlay.style.display = "none";
  }
}

// ==================== FORM VALIDATION ====================
function initFormValidation() {
  const forms = document.querySelectorAll("form[id]");

  forms.forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        // Check if form has Bootstrap validation class
        if (!form.classList.contains("needs-validation")) {
          return true;
        }

        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });
}

// Validate specific fields
function validateField(field, errorMessage) {
  const value = field.value.trim();

  if (value === "") {
    showFieldError(field, errorMessage || "Field ini harus diisi!");
    return false;
  }

  clearFieldError(field);
  return true;
}

function showFieldError(field, message) {
  // Remove existing error
  clearFieldError(field);

  // Add error class
  field.classList.add("is-invalid");

  // Create error message element
  const errorDiv = document.createElement("div");
  errorDiv.className = "invalid-feedback";
  errorDiv.textContent = message;

  // Insert after field
  field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
  field.classList.remove("is-invalid");

  const errorDiv = field.parentNode.querySelector(".invalid-feedback");
  if (errorDiv) {
    errorDiv.remove();
  }
}

// ==================== DELETE CONFIRMATION ====================
function initDeleteConfirmation() {
  const deleteButtons = document.querySelectorAll(
    '.btn-delete, [onclick*="confirm"]'
  );

  deleteButtons.forEach(function (button) {
    if (!button.hasAttribute("onclick")) {
      button.addEventListener("click", function (event) {
        if (
          !confirm(
            "Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan!"
          )
        ) {
          event.preventDefault();
          return false;
        }
      });
    }
  });
}

// ==================== LOADING SPINNER ====================
function showLoading(buttonElement) {
  const originalText = buttonElement.innerHTML;
  buttonElement.setAttribute("data-original-text", originalText);
  buttonElement.disabled = true;
  buttonElement.innerHTML =
    '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
}

function hideLoading(buttonElement) {
  const originalText = buttonElement.getAttribute("data-original-text");
  buttonElement.disabled = false;
  buttonElement.innerHTML = originalText;
}

// ==================== SHOW SUCCESS MESSAGE ====================
function showSuccessMessage(message, duration = 3000) {
  const alertDiv = document.createElement("div");
  alertDiv.className = "alert alert-success alert-dismissible fade show";
  alertDiv.innerHTML = `
        <i class="bi bi-check-circle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  // Insert at top of main content
  const mainContent = document.querySelector(".main-content .container-fluid");
  if (mainContent) {
    mainContent.insertBefore(alertDiv, mainContent.firstChild);

    // Auto hide
    setTimeout(function () {
      alertDiv.style.opacity = "0";
      setTimeout(function () {
        alertDiv.remove();
      }, 500);
    }, duration);
  }
}

// ==================== SHOW ERROR MESSAGE ====================
function showErrorMessage(message, duration = 3000) {
  const alertDiv = document.createElement("div");
  alertDiv.className = "alert alert-danger alert-dismissible fade show";
  alertDiv.innerHTML = `
        <i class="bi bi-exclamation-triangle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  // Insert at top of main content
  const mainContent = document.querySelector(".main-content .container-fluid");
  if (mainContent) {
    mainContent.insertBefore(alertDiv, mainContent.firstChild);

    // Auto hide
    setTimeout(function () {
      alertDiv.style.opacity = "0";
      setTimeout(function () {
        alertDiv.remove();
      }, 500);
    }, duration);
  }
}

// ==================== CONFIRM DIALOG ====================
function confirmAction(message, callback) {
  if (confirm(message)) {
    callback();
  }
}

// ==================== SCROLL TO TOP ====================
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
}

// Add scroll to top button if page is long
window.addEventListener("scroll", function () {
  let scrollBtn = document.getElementById("scrollTopBtn");

  if (!scrollBtn) {
    scrollBtn = document.createElement("button");
    scrollBtn.id = "scrollTopBtn";
    scrollBtn.className = "btn btn-primary";
    scrollBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    scrollBtn.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        `;
    scrollBtn.onclick = scrollToTop;
    document.body.appendChild(scrollBtn);
  }

  // Show/hide button based on scroll position
  if (window.pageYOffset > 300) {
    scrollBtn.style.display = "block";
  } else {
    scrollBtn.style.display = "none";
  }
});

// ==================== FORMAT NUMBER ====================
function formatNumber(number) {
  return new Intl.NumberFormat("id-ID").format(number);
}

// ==================== COPY TO CLIPBOARD ====================
function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(
    function () {
      showSuccessMessage("Teks berhasil disalin!");
    },
    function (err) {
      showErrorMessage("Gagal menyalin teks!");
    }
  );
}

// ==================== DEBOUNCE FUNCTION ====================
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// ==================== SEARCH/FILTER TABLE ====================
function filterTable(searchInput, tableId) {
  const filter = searchInput.value.toUpperCase();
  const table = document.getElementById(tableId);
  const tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) {
    // Start from 1 to skip header
    let found = false;
    const td = tr[i].getElementsByTagName("td");

    for (let j = 0; j < td.length; j++) {
      if (td[j]) {
        const txtValue = td[j].textContent || td[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }
    }

    tr[i].style.display = found ? "" : "none";
  }
}

// ==================== EXPORT FUNCTIONS ====================
console.log("Sistem Pakar Troubleshooting Komputer - JavaScript Loaded");
console.log("Pondok Pesantren Al-Gontory");
