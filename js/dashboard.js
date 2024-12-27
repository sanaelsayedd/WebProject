document.addEventListener('DOMContentLoaded', function() {
    // Arrow click for submenu
    let arrows = document.querySelectorAll(".arrow");
    arrows.forEach((arrow) => {
        arrow.addEventListener("click", (e) => {
            let arrowParent = e.target.parentElement.parentElement;
            arrowParent.classList.toggle("showMenu");
        });
    });

    // Sidebar toggle
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    
    sidebarBtn.addEventListener("click", () => {
        sidebar.classList.toggle("close");
        // Save state
        localStorage.setItem('sidebar-state', sidebar.classList.contains('close'));
    });

    // Restore sidebar state
    if (localStorage.getItem('sidebar-state') === 'true') {
        sidebar.classList.add('close');
    }
}); 