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
