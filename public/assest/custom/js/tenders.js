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
                tenderList.empty();

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