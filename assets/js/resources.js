function handleResourcesFilter() {
  const btnToggleFilter = document.querySelector('.btn-toggle-filter');
  const searchContainer = document.querySelector('.advanced-search-container');

  if (!btnToggleFilter || !searchContainer) {
    return;
  }

  const buttonStatusText = btnToggleFilter.querySelector('.button-status-text');
  
  btnToggleFilter.setAttribute('aria-expanded', 'false');
  btnToggleFilter.setAttribute('aria-controls', 'advanced-search-container');
  searchContainer.setAttribute('id', 'advanced-search-container');
  searchContainer.setAttribute('aria-hidden', 'true');

  btnToggleFilter.addEventListener('click', function() {
    const isExpanded = searchContainer.classList.contains('active');

    searchContainer.classList.toggle('active');
    
    buttonStatusText.textContent = isExpanded ? 'Open' : 'Close';
    
    btnToggleFilter.setAttribute('aria-expanded', !isExpanded);
    searchContainer.setAttribute('aria-hidden', isExpanded);

  });
}

function handleVideoTranscript() {
  const transcriptToggles = document.querySelectorAll('.transcript-toggle');

  if (!transcriptToggles) {
    return;
  }
//show/hide the video transcript similar to an accordion by toggling the active class and aria-expanded attribute and make the transcript content height updating from 0 the scroll height
  transcriptToggles.forEach((toggle) => {
    toggle.addEventListener('click', function() {
      const transcriptContent = toggle.nextElementSibling;
      const isExpanded = transcriptContent.classList.contains('active');

      transcriptContent.classList.toggle('active');
      toggle.setAttribute('aria-expanded', !isExpanded);
      window.requestAnimationFrame(function() {
        transcriptContent.style.height = isExpanded ? '0' : transcriptContent.scrollHeight + 'px';
      });
    });
  });
}

window.addEventListener('DOMContentLoaded', () => {
  handleResourcesFilter();
  handleVideoTranscript();
});


