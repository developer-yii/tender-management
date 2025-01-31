// side-bar click menu

    $(document).ready(function () {
        $('.mobileIcon a').click(function () {
            $('.sidebar').toggleClass('show')
        });

        $('.sidebar').click(function () {
            $(this).removeClass('show');
        });

        $('.sidebar-fix').click(function (event) {
            event.stopPropagation();
        });

        $('.kiTools').click(function () {
            $('.dropDownInner').toggleClass('show')
        });

        const maxVisibleItems = 10; // Adjust this value as needed

        $('.btnOpen').click(function () {
            const hiddenItems = $('.docListOur li.hidden');

            if (hiddenItems.is(':hidden')) {
                hiddenItems.slideDown(300).removeClass('hidden');
                $(this).html('<i class="bi bi-caret-up-fill"></i>');
            } else {
                $('.docListOur li').slice(maxVisibleItems).slideUp(300, function () {
                    $(this).addClass('hidden');
                });
                $(this).html('<i class="bi bi-caret-down-fill"></i>');
            }
        });

    });

    // ==========

    $('.dropdown-button').on('click', function () {
            $('.dropdown-list').toggle();
        });

        $('.dropdown-list li').on('click', function () {
            var selectedText = $(this).text();
            $('.dropdown-button').text(selectedText);
            $('.dropdown-list').hide();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-list').hide();
            }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }



