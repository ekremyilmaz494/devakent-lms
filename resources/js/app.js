import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

// Register Collapse on whichever Alpine instance becomes active.
// On Livewire pages: window.Alpine = Livewire's bundled Alpine (already set before this module runs).
// On guest pages:    window.Alpine = our npm Alpine (set below, since Livewire is absent).
document.addEventListener('alpine:init', () => {
    (window.Alpine || Alpine).plugin(Collapse);
});

// Only set window.Alpine for guest pages where Livewire hasn't set it yet.
// On Livewire pages this is a no-op — Livewire's Alpine stays and starts itself.
if (!window.Alpine) {
    window.Alpine = Alpine;
}
