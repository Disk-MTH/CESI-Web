(function($) {
    $.fn.pagination = function(options) {
        const $owner = this,
            settings = $.extend({
                    page: 1,
                    count: 0,
                    maxVisible: null,
                    href: "javascript:void({{number}});",
                    hrefVariable: "{{number}}",
                    previous: "&laquo;",
                    next: "&raquo;",
                },
                $owner.data('settings') || {},
                options || {});

        if(settings.count <= 0) return this;
        if (settings.page < 1) settings.page = 1;
        if (!settings.maxVisible || isNaN(settings.maxVisible)) settings.maxVisible = settings.count;
        if (settings.maxVisible < 1 || settings.maxVisible > settings.count) settings.maxVisible = settings.count;

        function href(page) {
            return settings.href.replace(settings.hrefVariable, page);
        }

        return this.each(function() {
            const $container = $(this);
            let start = settings.page - Math.floor(settings.maxVisible / 2);
            start = Math.max(start, 1);
            start = Math.min(start, 1 + settings.count - settings.maxVisible);
            const end = Math.min(start + settings.maxVisible - 1, settings.count);

            $container.empty();
            $container.append('<ul class="pagination"></ul>');
            const $pagination = $container.find('.pagination');

            if (settings.maxVisible < settings.count) {
                const previousClass = settings.page === 1 ? 'page-item disabled' : 'page-item';
                $pagination.append('<li class="' + previousClass + '"><a class="page-link previous-page" href="' + href(settings.page - 1) + '">' + settings.previous + '</a></li>');
            }

            for (var i = start; i <= end; i++) {
                const pageClass = i === settings.page ? 'page-item active' : 'page-item';
                $pagination.append('<li class="' + pageClass + '"><a class="page-link" href="' + href(i) + '">' + i + '</a></li>');
            }

            if (settings.maxVisible < settings.count) {
                const nextClass = settings.page === settings.count ? 'page-item disabled' : 'page-item';
                $pagination.append('<li class="' + nextClass + '"><a class="page-link next-page" href="' + href(settings.page + 1) + '">' + settings.next + '</a></li>');
            }

            $pagination.find('.page-link').not('.previous-page, .next-page').click(function(e) {
                e.preventDefault();
                settings.page = parseInt($(this).text(), 10);
                $owner.pagination(settings);
                $owner.trigger("changePage", [settings.page]);
            });

            $pagination.find('.previous-page').click(function(e) {
                e.preventDefault();
                if (settings.page > 1) {
                    settings.page--;
                    $owner.pagination(settings);
                    $owner.trigger("changePage", [settings.page]);
                }
            });

            $pagination.find('.next-page').click(function(e) {
                e.preventDefault();
                if (settings.page < settings.count) {
                    settings.page++;
                    $owner.pagination(settings);
                    $owner.trigger("changePage", [settings.page]);
                }
            });
        });
    }
})(jQuery);