document.addEventListener('DOMContentLoaded', function () {
  var searchInput = document.getElementById('resourceSearch');
  var shelfTabs = document.querySelectorAll('.shelf-tab');
  var cards = document.querySelectorAll('.resource-card-col');
  var emptyState = document.getElementById('emptyState');
  var resultCount = document.getElementById('resultCount');
  var activeCategory = 'all';

  function applyFilters() {
    var term = (searchInput.value || '').trim().toLowerCase();
    var visible = 0;

    cards.forEach(function (col) {
      var category = col.dataset.category;
      var haystack = col.dataset.search;
      var matchesCategory = activeCategory === 'all' || category === activeCategory;
      var matchesSearch = term === '' || haystack.indexOf(term) !== -1;
      var show = matchesCategory && matchesSearch;

      col.classList.toggle('d-none', !show);
      if (show) visible++;
    });

    if (resultCount) {
      resultCount.textContent = visible + (visible === 1 ? ' resource' : ' resources');
    }
    if (emptyState) {
      emptyState.classList.toggle('d-none', visible !== 0);
    }
  }

  shelfTabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      shelfTabs.forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');
      activeCategory = tab.dataset.category;
      applyFilters();
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', applyFilters);
  }

  // Preview button: placeholder modal until backend file storage is wired up
  document.querySelectorAll('.btn-preview').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var title = btn.dataset.title;
      var modalTitle = document.getElementById('previewModalLabel');
      var modalBody = document.getElementById('previewModalBody');
      if (modalTitle) modalTitle.textContent = title;
      if (modalBody) {
        modalBody.innerHTML =
          '<div class="text-center text-muted py-5">' +
          '<i class="bi bi-file-earmark-text" style="font-size:2.5rem;"></i>' +
          '<p class="mt-3 mb-0">Live preview will be available once the resource library is connected to the file server.</p>' +
          '</div>';
      }
      var modalEl = document.getElementById('previewModal');
      if (modalEl && window.bootstrap) {
        new bootstrap.Modal(modalEl).show();
      }
    });
  });

  applyFilters();
});
