function toggleSidebar() {
    let sidebar = document.getElementById('sidebar');
    let closeButton = document.querySelector('.close-btn');
    if (sidebar.style.left === "-250px") {
        sidebar.style.left = "0";
        closeButton.style.display = "block"; 
    } else {
        sidebar.style.left = "-250px";
        closeButton.style.display = "none"; 
    }
}

function closeSidebar() {
    let sidebar = document.getElementById('sidebar');
    let closeButton = document.querySelector('.close-btn');
    if (sidebar.style.left === "0") {
        sidebar.style.left = "-250px";
        closeButton.style.display = "none"; 
    }
}

// Fungsi memuat data PO Recap hanya jika halaman po_recap.html terbuka
function loadPage() {
    fetch('php/get_po_recap.php')
        .then(response => response.json())
        .then(data => {
            let tableContent = '<table><thead><tr><th>PO No.</th><th>Color</th><th>Style #</th><th>Code Dest</th><th>Packing</th><th>Size</th><th>Qty</th><th>Portion</th></tr></thead><tbody>';
            data.forEach(row => {
                tableContent += `<tr><td>${row.po_no}</td><td>${row.color}</td><td>${row.style_no}</td><td>${row.code_dest}</td><td>${row.packing}</td><td>${row.size}</td><td>${row.qty}</td><td>${row.portion_value}</td></tr>`;
            });
            tableContent += '</tbody></table>';
            document.getElementById("po-table").innerHTML = tableContent;
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('click', function(event) {
    let sidebar = document.getElementById('sidebar');
    let toggleButton = document.querySelector('.toggle-btn');
    if (sidebar.style.left === "0" && !sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
        closeSidebar();
    }
});

document.addEventListener("DOMContentLoaded", function() {
    // Hanya panggil loadPage() jika halaman saat ini adalah po_recap.html
    if (window.location.pathname.includes("po_recap.html")) {
        loadPage();
    }

    // Login Form Handling
    let loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            let username = document.querySelector('input[name="username"]').value;
            let password = document.querySelector('input[name="password"]').value;
            let role = document.querySelector('select[name="role"]')?.value; // Ambil nilai role jika ada

            fetch('php/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}${role ? `&role=${encodeURIComponent(role)}` : ''}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "home.html"; // Redirect setelah login
                } else {
                    let errorMessage = document.getElementById('error-message');
                    if (errorMessage) {
                        errorMessage.textContent = "Invalid username, password, or role.";
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Navigasi ke halaman PO.RECAP
    let poRecapLink = document.querySelector('a[href="po_recap.html"]');
    if (poRecapLink) {
        poRecapLink.addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = "po_recap.html";
        });
    }

    // Role Check Handling
    fetch('php/check_role.php')
        .then(response => response.json())
        .then(data => {
            if (data.role === 'admin') {
                let adminActions = document.getElementById('admin-actions');
                if (adminActions) {
                    adminActions.style.display = 'block';
                }
            }
        })
        .catch(error => console.error('Error:', error));

    // Add PO.RECAP Form Handling
    let addForm = document.getElementById('add-form');
    if (addForm) {
        addForm.addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            fetch('php/add_po_recap.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (window.location.pathname.includes("po_recap.html")) {
                    loadPage(); // Muat ulang data jika di halaman po_recap.html
                }
                this.reset();
            })
            .catch(error => console.error('Error:', error));
        });
    }
});