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

function loadPage(page) {
    fetch('php/get_po_recap.php')
        .then(response => response.json())
        .then(data => {
            let tableContent = '<table><thead><tr><th>PO No.</th><th>Color</th><th>Style #</th><th>Code Dest</th><th>Size</th><th>Qty</th><th>Portion</th></tr></thead><tbody>';
            data.forEach(row => {
                tableContent += `<tr><td>${row.po_no}</td><td>${row.color}</td><td>${row.style_no}</td><td>${row.code_dest}</td><td>${row.size}</td><td>${row.qty}</td><td>${row.portion}</td></tr>`;
            });
            tableContent += '</tbody></table>';
            document.getElementById("main-content").innerHTML = tableContent;
        });
}

document.addEventListener('click', function(event) {
    let sidebar = document.getElementById('sidebar');
    let toggleButton = document.querySelector('.toggle-btn');
    if (sidebar.style.left === "0" && !sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
        closeSidebar();
    }
});

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let username = document.querySelector('input[name="username"]').value;
    let password = document.querySelector('input[name="password"]').value;

    fetch('php/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${username}&password=${password}`
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            window.location.href = "home.html";
        } else {
            document.getElementById('error-message').textContent = "Invalid username or password.";
        }
    });
});

fetch('php/check_role.php')
    .then(response => response.json())
    .then(data => {
        if (data.role === 'admin') {
            document.getElementById('admin-actions').style.display = 'block';
        }
    });

document.getElementById('add-form').addEventListener('submit', function(event) {
    event.preventDefault();
    let formData = new FormData(this);
    fetch('php/add_po_recap.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload();
    });
});