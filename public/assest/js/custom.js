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

        const maxVisibleItems = 9; // Adjust this value as needed

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


    // ===== calendar Date



