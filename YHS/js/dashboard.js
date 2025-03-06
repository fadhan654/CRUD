document.addEventListener("DOMContentLoaded", function() {
    // Fetch total PO Recap
    fetch("php/get_dashboard_stats.php")
    .then(response => response.json())
    .then(data => {
        document.getElementById("totalPO").innerText = data.totalPO;
        document.getElementById("totalUsers").innerText = data.totalUsers;

        // Chart Data
        const barCtx = document.getElementById("barChart").getContext("2d");
        new Chart(barCtx, {
            type: "bar",
            data: {
                labels: data.months,
                datasets: [{
                    label: "PO Recap Per Bulan",
                    data: data.poCounts,
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });

        // Pie Chart Data
        const pieCtx = document.getElementById("pieChart").getContext("2d");
        new Chart(pieCtx, {
            type: "pie",
            data: {
                labels: data.categories,
                datasets: [{
                    data: data.categoryCounts,
                    backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
                }]
            },
            options: { responsive: true }
        });
    })
    .catch(error => console.error("Error fetching dashboard stats:", error));
});