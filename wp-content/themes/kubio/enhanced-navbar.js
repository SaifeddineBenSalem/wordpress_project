// Enhanced Language Dropdown JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const languageDropdowns = document.querySelectorAll('.language-dropdown');
    
    languageDropdowns.forEach(function(dropdown) {
        const toggle = dropdown.querySelector('.language-dropdown-toggle');
        const menu = dropdown.querySelector('.language-dropdown-menu');
        
        if (toggle && menu) {
            // Toggle dropdown on click
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropdown.classList.toggle('open');
            });
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
            // Close dropdown on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdown.classList.remove('open');
                }
            });
        }
    });
}); 