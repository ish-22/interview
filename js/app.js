// js/app.js

// Dark Mode Toggle
function toggleDarkMode() {
  document.body.classList.toggle('dark');
  const isDark = document.body.classList.contains('dark');
  localStorage.setItem('darkMode', isDark);
  
  // Update toggle icon
  const toggles = document.querySelectorAll('.dark-toggle');
  toggles.forEach(toggle => {
    toggle.textContent = isDark ? '☀️' : '🌙';
  });
}

// Load dark mode preference
function loadDarkMode() {
  const isDark = localStorage.getItem('darkMode') === 'true';
  if (isDark) {
    document.body.classList.add('dark');
    const toggles = document.querySelectorAll('.dark-toggle');
    toggles.forEach(toggle => {
      toggle.textContent = '☀️';
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadDarkMode();
  // Login form validation
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    const nameInput = document.getElementById('loginName');
    const emailInput = document.getElementById('loginEmail');
    const passwordInput = document.getElementById('loginPassword');

    // Add error message elements
    const addErrorMessage = (input, message) => {
      let errorEl = input.parentNode.querySelector('.error-message');
      if (!errorEl) {
        errorEl = document.createElement('div');
        errorEl.className = 'error-message';
        input.parentNode.appendChild(errorEl);
      }
      errorEl.textContent = message;
      errorEl.style.display = message ? 'block' : 'none';
    };

    const validateField = (input, validator) => {
      const value = input.value.trim();
      const result = validator(value);
      
      input.classList.remove('error', 'success');
      if (result.valid) {
        input.classList.add('success');
        addErrorMessage(input, '');
      } else {
        input.classList.add('error');
        addErrorMessage(input, result.message);
      }
      return result.valid;
    };

    // Clear validation on input
    [nameInput, emailInput, passwordInput].forEach(input => {
      input.addEventListener('input', () => {
        if (input.classList.contains('error')) {
          input.classList.remove('error');
          addErrorMessage(input, '');
        }
      });
    });

    const validators = {
      name: (value) => {
        if (!value) return { valid: false, message: 'Name is required' };
        if (value.length < 2) return { valid: false, message: 'Name must be at least 2 characters' };
        if (!/^[a-zA-Z\s]+$/.test(value)) return { valid: false, message: 'Name can only contain letters and spaces' };
        return { valid: true };
      },
      email: (value) => {
        if (!value) return { valid: false, message: 'Email is required' };
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return { valid: false, message: 'Please enter a valid email address' };
        return { valid: true };
      },
      password: (value) => {
        if (!value) return { valid: false, message: 'Password is required' };
        if (value.length < 6) return { valid: false, message: 'Password must be at least 6 characters' };
        return { valid: true };
      }
    };

    // Real-time validation
    nameInput.addEventListener('blur', () => validateField(nameInput, validators.name));
    emailInput.addEventListener('blur', () => validateField(emailInput, validators.email));
    passwordInput.addEventListener('blur', () => validateField(passwordInput, validators.password));

    // Form submission
    loginForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const isNameValid = validateField(nameInput, validators.name);
      const isEmailValid = validateField(emailInput, validators.email);
      const isPasswordValid = validateField(passwordInput, validators.password);
      
      if (!isNameValid || !isEmailValid || !isPasswordValid) {
        return;
      }
      
      const user = { 
        name: nameInput.value.trim(), 
        email: emailInput.value.trim(), 
        loggedAt: new Date().toISOString() 
      };
      localStorage.setItem('loggedInUser', JSON.stringify(user));
      window.location.href = 'index.php';
    });
  }

  // Order form handling
  const orderForm = document.getElementById('orderForm');
  if (orderForm) {
    const nameEl = document.getElementById('name');
    const phoneEl = document.getElementById('phone');
    const emailEl = document.getElementById('email');
    const billBox = document.getElementById('billBySomeoneElse');
    const popup = document.getElementById('popup');
    const popupMsg = document.getElementById('popupMsg');
    const popupClose = document.getElementById('popupClose');
    
    console.log('Popup elements:', { popup, popupMsg, popupClose });
    const homePreview = document.getElementById('homePreview');
    const hpName = document.getElementById('hpName');
    const hpPhone = document.getElementById('hpPhone');
    const hpEmail = document.getElementById('hpEmail');
    const clearBtn = document.getElementById('clearBtn');

    // populate preview if localStorage has lastOrder
    const showPreview = (data) => {
      if (!data) {
        homePreview.classList.add('hidden');
        return;
      }
      hpName.textContent = data.name || '—';
      hpPhone.textContent = data.phone || '—';
      hpEmail.textContent = data.email || '—';
      homePreview.classList.remove('hidden');
    };

    const last = localStorage.getItem('lastOrder');
    if (last) {
      try { showPreview(JSON.parse(last)); } catch(e){ showPreview(null); }
    }

    clearBtn && clearBtn.addEventListener('click', () => {
      nameEl.value = phoneEl.value = emailEl.value = '';
      billBox.checked = false;
    });

    // Popup close functionality
    if (popupClose) {
      popupClose.addEventListener('click', function() {
        if (popup) {
          popup.classList.add('hidden');
        }
      });
    }
    
    // Close popup when clicking outside
    if (popup) {
      popup.addEventListener('click', function(e) {
        if (e.target === popup) {
          popup.classList.add('hidden');
        }
      });
    }

    orderForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = nameEl.value.trim();
      const phone = phoneEl.value.trim();
      const email = emailEl.value.trim();
      const billBySomeoneElse = billBox.checked;

      // client-side validation: name & phone required
      if (!name) return alert('Name is required.');
      if (!phone) return alert('Phone is required.');
      if (!/^[0-9]{7,15}$/.test(phone)) return alert('Phone must be digits only (7-15 digits).');

      // Email requirement: if bill is NOT made by someone else (checkbox unchecked), email is mandatory
      if (!billBySomeoneElse && email === '') return alert('Email is required when bill is not made by someone else.');
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return alert('Invalid email.');

      // send to server (AJAX to this same page)
      try {
        const res = await fetch(window.location.href, {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({ name, phone, email, bill_by_someone_else: billBySomeoneElse ? 1 : 0 })
        });
        const data = await res.json();
        if (!data.success) {
          return alert('Server: ' + (data.message || 'Failed to save'));
        }

        // Save to localStorage
        const orderObj = { id: data.id || null, name, phone, email, billBySomeoneElse, createdAt: new Date().toISOString() };
        localStorage.setItem('lastOrder', JSON.stringify(orderObj));
        // also keep an array of orders
        const all = JSON.parse(localStorage.getItem('orders') || '[]');
        all.push(orderObj);
        localStorage.setItem('orders', JSON.stringify(all));

        // show home preview (requirement: after Order click a home page containing your name/phone/email should appear)
        showPreview(orderObj);

        // show popup success
        if (popupMsg) {
          popupMsg.textContent = 'Submit Success';
        }
        if (popup) {
          popup.classList.remove('hidden');
        }
      } catch (err) {
        console.error(err);
        alert('Error sending request. See console.');
      }
    });
  }

  // If we are on index.php show greeting if logged in
  const logged = localStorage.getItem('loggedInUser');
  if (logged && document.querySelector('.intro-card')) {
    try {
      const u = JSON.parse(logged);
      const intro = document.querySelector('.intro-card');
      if (intro) {
        intro.innerHTML = `
          <h3>Welcome Back, ${u.name}!</h3>
          <p><strong>Name:</strong> ${u.name}<br>
          <strong>Email:</strong> ${u.email}<br>
          <strong>Login Time:</strong> ${new Date(u.loggedAt).toLocaleString()}<br>
          <em>You are successfully logged in and your data is saved in browser storage.</em></p>
        `;
      }
    } catch(e) {}
  }
});
