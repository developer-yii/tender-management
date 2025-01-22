// document.addEventListener("DOMContentLoaded", function () {
//     const employees = document.querySelectorAll(".employProfile");
//     const tenders = document.querySelectorAll(".markusTender ul li");
//     const employeeNameElement = document.getElementById("employee-name");

//     // Function to handle active employee change
//     function activateEmployee(employeeId) {
//         // Remove active class from all employees
//         employees.forEach(emp => emp.classList.remove("active"));

//         // Add active class to the clicked employee
//         const activeEmployee = document.querySelector(`.employProfile[data-employee-id='${employeeId}']`);
//         if (activeEmployee) {
//             activeEmployee.classList.add("active");
//         }

//         // Update the employee name dynamically
//         if (activeEmployee && employeeNameElement) {
//             const employeeName = activeEmployee.querySelector(".textBox h5").textContent;
//             employeeNameElement.textContent = employeeName; // Update the employee name
//         }

//         // Hide all tenders
//         tenders.forEach(tender => tender.style.display = "none");

//         // Show tenders related to the active employee
//         document.querySelectorAll(`.markusTender ul li[data-employee-id='${employeeId}']`).forEach(tender => {
//             tender.style.display = "";
//         });
//     }

//     // Add click event listeners to employees
//     employees.forEach(employee => {
//         employee.addEventListener("click", function () {
//             const employeeId = this.getAttribute("data-employee-id");
//             activateEmployee(employeeId);
//         });
//     });

//     // Initialize the first employee as active
//     if (employees.length > 0) {
//         const firstEmployeeId = employees[0].getAttribute("data-employee-id");
//         activateEmployee(firstEmployeeId);
//     }
// });

$(document).ready(function () {
    // Function to load tenders for a selected employee
    function loadEmployeeTenders(employeeId, employeeName) {
        // Highlight the selected employee
        $('.employProfile').removeClass('active');
        $(`.employProfile[data-employee-id="${employeeId}"]`).addClass('active');

        // Set employee name in the title
        $('#employee-name').text(employeeName);

        // Fetch tenders via AJAX
        $.ajax({
            url: assignTenderListUrl,
            type: 'GET',
            data : {id : employeeId},
            success: function (response) {
                const tenderList = $('#tenderList');
                tenderList.empty(); // Clear existing tenders

                if (response.tenders && response.tenders.length > 0) {
                    response.tenders.forEach(tender => {
                        tenderList.append(`
                            <li>
                                <div class="statusIcon">
                                    <img src="${baseUrl}images/${tender.status_icon}" alt="${tender.status_text}">
                                </div>
                                <div class="text">
                                    <p>${tender.tender_name}</p>
                                </div>
                            </li>
                        `);
                    });
                } else {
                    tenderList.append(`
                        <li>
                            <div class="statusIcon">
                                <img src="${baseUrl}images/gray-dot.png" alt="">
                            </div>
                            <div class="text">
                                <p>No tenders found for this employee.</p>
                            </div>
                        </li>
                    `);
                }
            },
            error: function () {
                // alert('Failed to load tenders.');
            }
        });
    }

    // Add click event listener for employee profiles
    $('.employProfile').on('click', function () {
        const employeeId = $(this).data('employee-id');
        const employeeName = $(this).find('h5').text();
        loadEmployeeTenders(employeeId, employeeName);
    });

    // Automatically trigger click on the first employee on page load
    const firstEmployee = $('.employProfile').first();
    if (firstEmployee.length) {
        const employeeId = firstEmployee.data('employee-id');
        const employeeName = firstEmployee.find('h5').text();
        loadEmployeeTenders(employeeId, employeeName);
    }
});