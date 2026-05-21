async function login() {
  const emailInput    = document.getElementById('email').value.trim();
  const passwordInput = document.getElementById('password').value;

  if (emailInput === '' || passwordInput === '') {
    alert('Please fill in both your email and password.');
    return;
  }

  const formData = new FormData();
  formData.append('action',   'login');
  formData.append('email',    emailInput);
  formData.append('password', passwordInput);

  try {
    const response = await fetch('login.php', { method: 'POST', body: formData });
    const result   = await response.json();
    if (result.success) {
      window.location.href = 'dashboard.php';
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert('Could not connect to the server. Please check your internet connection.');
  }
}

async function signup() {
  const fullName      = document.getElementById('name').value.trim();
  const emailInput    = document.getElementById('email').value.trim();
  const countryInput  = document.getElementById('country').value.trim();
  const ageInput      = document.getElementById('age').value.trim();
  const mobileInput   = document.getElementById('mobile').value.trim();
  const passwordInput = document.getElementById('password').value;

  if (!fullName || !emailInput || !countryInput || !ageInput || !mobileInput || !passwordInput) {
    alert('Please fill in all the required registration fields.');
    return;
  }

  const age = parseInt(ageInput, 10);
  if (isNaN(age) || age < 1 || age > 120) {
    alert('Please enter a valid age between 1 and 120.');
    return;
  }

  if (passwordInput.length < 6) {
    alert('Password must be at least 6 characters long.');
    return;
  }

  const formData = new FormData();
  formData.append('action',   'signup');
  formData.append('name',     fullName);
  formData.append('email',    emailInput);
  formData.append('country',  countryInput);
  formData.append('age',      ageInput);
  formData.append('mobile',   mobileInput);
  formData.append('password', passwordInput);

  try {
    const response = await fetch('signup.php', { method: 'POST', body: formData });
    const result   = await response.json();
    if (result.success) {
      window.location.href = 'verify.php';
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert('Could not complete registration due to a server error.');
  }
}

async function resetPassword() {
  const emailInput = document.getElementById('email').value.trim();

  if (emailInput === '') {
    alert('Please enter your email address to receive a recovery code.');
    return;
  }

  const formData = new FormData();
  formData.append('action', 'reset_password');
  formData.append('email',  emailInput);

  try {
    const response = await fetch('forgot-password.php', { method: 'POST', body: formData });
    const result   = await response.json();
    if (result.success) {
      alert(result.message);
      window.location.href = 'verify.php';
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert('Failed to send reset link. Please try again later.');
  }
}

async function verifyCode() {
  const codeInput = document.getElementById('code').value.trim();

  if (codeInput === "") {
    alert("Please enter the verification code to continue.");
    return;
  }

  const formData = new FormData();
  formData.append('action', 'verify');
  formData.append('code', codeInput);

  try {
    const response = await fetch('verify.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.success) {
      window.location.href = result.redirect; 
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert('Server connection failed. Could not verify the security code.');
  }
}

async function updatePassword() {
  const passwordInput = document.getElementById('new_password').value;
  const confirmInput = document.getElementById('confirm_password').value;

  if (passwordInput === "" || confirmInput === "") {
    alert("Please fill in both password fields.");
    return;
  }

  if (passwordInput !== confirmInput) {
    alert("Passwords do not match. Please type them carefully.");
    return;
  }

  if (passwordInput.length < 6) {
    alert("Password must be at least 6 characters long.");
    return;
  }

  const formData = new FormData();
  formData.append('action', 'update_password');
  formData.append('password', passwordInput);

  try {
    const response = await fetch('new-password.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.success) {
      alert(result.message);
      window.location.href = 'login.php';
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert('Server error. Failed to update your password.');
  }
}

function openReviewModal(historyId) {
  const modal = document.getElementById('qzhstModal');
  const contentBox = document.getElementById('modalReviewContent');
  
  contentBox.innerHTML = '<p style="text-align:center;color:#625985;">Loading attempt records...</p>';
  modal.classList.add('active');

  const formData = new FormData();
  formData.append('action', 'get_review');
  formData.append('history_id', historyId);

  fetch('history.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    if (result.success && result.data.length > 0) {
      contentBox.innerHTML = '';
      
      result.data.forEach((item, index) => {
        const itemBox = document.createElement('div');
        itemBox.className = 'qzhst-review-item-box';

        const checkOptClass = (optLetter) => {
          if (item.correct_option === optLetter) return 'correct-choice';
          if (item.selected_option === optLetter && item.selected_option !== item.correct_option) return 'wrong-choice';
          return '';
        };

        const getOptBadge = (optLetter) => {
          if (item.correct_option === optLetter) return '<span class="qzhst-option-badge">✔ Correct</span>';
          if (item.selected_option === optLetter) return '<span class="qzhst-option-badge">❌ Your Choice</span>';
          return '';
        };

        itemBox.innerHTML = `
          <h4><strong>Q${index + 1}:</strong> ${escapeHtml(item.question_text)}</h4>
          <div class="qzhst-option-row ${checkOptClass('A')}"><span>A. ${escapeHtml(item.option_a)}</span>${getOptBadge('A')}</div>
          <div class="qzhst-option-row ${checkOptClass('B')}"><span>B. ${escapeHtml(item.option_b)}</span>${getOptBadge('B')}</div>
          <div class="qzhst-option-row ${checkOptClass('C')}"><span>C. ${escapeHtml(item.option_c)}</span>${getOptBadge('C')}</div>
          <div class="qzhst-option-row ${checkOptClass('D')}"><span>D. ${escapeHtml(item.option_d)}</span>${getOptBadge('D')}</div>
        `;
        contentBox.appendChild(itemBox);
      });
    } else {
      contentBox.innerHTML = '<p style="text-align:center;color:#625985;">Could not retrieve answer sheets for this attempt.</p>';
    }
  })
  .catch(error => {
    console.error(error);
    contentBox.innerHTML = '<p style="text-align:center;color:#ff4949;">Server processing error.</p>';
  });
}

function closeReviewModal() {
  document.getElementById('qzhstModal').classList.remove('active');
}

function escapeHtml(text) {
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
