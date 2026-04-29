const stage = document.getElementById('stage');
const panel = document.getElementById('panel');
const container = stage.closest('.interactive-container');
const quadrants = stage.querySelectorAll('.wing-quadrant');
const labels = stage.querySelectorAll('.zone-label');
const buttons = panel.querySelectorAll('.app-button');
const details = container.querySelectorAll('.detail-content');
const detailHint = document.getElementById('detail-hint');

const isTouch = window.matchMedia('(hover: none)').matches;

// Two independent pieces of state:
//   selectedZone = the panel's persistent selection (set by button click or tap)
//   hoveredZone  = the butterfly's transient hover (desktop only, clears on mouseleave)
// The butterfly's visual state (overlay/glow) is driven by whichever is active,
// preferring hover. The panel (buttons + detail) is driven primarily by hover,
// and falls back to selectedZone when hover clears.
let selectedZone = null;

function updateStageVisual(zoneName) {
  // Control what the butterfly shows: color wash, glow, dim-others
  if (zoneName) {
    stage.classList.add('has-active');
    quadrants.forEach(q => q.classList.toggle('active', q.dataset.zone === zoneName));
    labels.forEach(l => l.classList.toggle('active', l.dataset.label === zoneName));
  } else {
    stage.classList.remove('has-active');
    quadrants.forEach(q => q.classList.remove('active'));
    labels.forEach(l => l.classList.remove('active'));
  }
}

function updatePanelVisual(zoneName) {
  // Control which button is highlighted and which detail is visible
  buttons.forEach(b => {
    const isActive = b.dataset.zone === zoneName;
    b.classList.toggle('active', isActive);
    b.setAttribute('aria-selected', isActive ? 'true' : 'false');
  });
  details.forEach(d => d.classList.toggle('visible', d.dataset.detail === zoneName));
  // Hide hint when any zone is active, show it when nothing is
  detailHint.classList.toggle('hidden', zoneName !== null);
}

function render() {
  // The displayed zone is the current hover if present, else the selected one.
  // For simplicity on touch devices, we treat taps as "selected".
  updatePanelVisual(selectedZone);
}

// --- Wing interactions ---
if (isTouch) {
  // Tap toggles persistent selection
  quadrants.forEach(q => {
    q.addEventListener('click', (e) => {
      e.stopPropagation();
      const name = q.dataset.zone;
      if (selectedZone === name) {
        selectedZone = null;
        updateStageVisual(null);
      } else {
        selectedZone = name;
        updateStageVisual(name);
      }
      render();
    });
  });
} else {
  // Hover: show on butterfly AND in panel. When mouse leaves a wing, clear
  // the butterfly visual AND the panel — so nothing is shown unless a button
  // was clicked to pin a selection.
  quadrants.forEach(q => {
    q.addEventListener('mouseenter', () => {
      updateStageVisual(q.dataset.zone);
      updatePanelVisual(q.dataset.zone);
    });
  });

  // When the pointer leaves the stage entirely, clear wing visuals and
  // revert the panel to whatever was last selected (or nothing).
  stage.addEventListener('mouseleave', () => {
    updateStageVisual(null);
    updatePanelVisual(selectedZone);
  });
}

// --- Button interactions (desktop + touch) ---
buttons.forEach(b => {
  b.addEventListener('click', (e) => {
    e.stopPropagation();
    const name = b.dataset.zone;
    if (selectedZone === name) {
      // Toggle off
      selectedZone = null;
      updateStageVisual(null);
      updatePanelVisual(null);
    } else {
      // Select and highlight the wing as well
      selectedZone = name;
      updateStageVisual(name);
      updatePanelVisual(name);
    }
  });
});

// --- Touch: tap outside to dismiss ---
if (isTouch) {
  document.addEventListener('click', (e) => {
    if (!container.contains(e.target)) {
      selectedZone = null;
      updateStageVisual(null);
      updatePanelVisual(null);
    }
  });
}
