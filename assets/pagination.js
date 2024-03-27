(function ($) {
    $.fn.pagination = function (options) {
        const $owner = this,
            settings = $.extend({
                    page: 1,
                    count: 0,
                    maxVisible: null,
                    href: "javascript:void({{number}});",
                    hrefVariable: "{{number}}",
                    previous: "&laquo;",
                    next: "&raquo;",
                    paginationClass: "pagination",
                    pageItemClass: "page-item",
                    pageLinkClass: "page-link",
                    activeClass: "active",
                    inactiveClass: "disabled",
                },
                $owner.data('settings') || {},
                options || {});

        if (settings.count <= 0) return this;
        if (settings.page < 1) settings.page = 1;
        if (!settings.maxVisible || isNaN(settings.maxVisible)) settings.maxVisible = settings.count;
        if (settings.maxVisible < 1 || settings.maxVisible > settings.count) settings.maxVisible = settings.count;

        this.changePage = function() {
            $owner.trigger("changePage", [settings.page]);
        }

        function href(page) {
            return settings.href.replace(settings.hrefVariable, page);
        }

        return this.each(function () {
            const $container = $(this);
            let start = settings.page - Math.floor(settings.maxVisible / 2);
            start = Math.max(start, 1);
            start = Math.min(start, 1 + settings.count - settings.maxVisible);
            const end = Math.min(start + settings.maxVisible - 1, settings.count);

            $container.empty();
            $container.append('<ul class="' + settings.paginationClass + '"></ul>');
            const $pagination = $container.find("." + settings.paginationClass);

            if (settings.maxVisible < settings.count) {
                const previousClass = settings.page === 1 ? settings.pageItemClass + " " + settings.inactiveClass : settings.pageItemClass;
                $pagination.append('<li class="' + previousClass + '"><a class="' + settings.pageLinkClass + ' previous-page" href="' + href(settings.page - 1) + '">' + settings.previous + '</a></li>');
            }

            for (let i = start; i <= end; i++) {
                const pageClass = i === settings.page ? settings.pageItemClass + " " + settings.activeClass : settings.pageItemClass;
                $pagination.append('<li class="' + pageClass + '"><a class="' + settings.pageLinkClass + '" href="' + href(i) + '">' + i + '</a></li>');
            }

            if (settings.maxVisible < settings.count) {
                const nextClass = settings.page === settings.count ? "page-item disabled" : "page-item";
                $pagination.append('<li class="' + nextClass + '"><a class="' + settings.pageLinkClass + ' next-page" href="' + href(settings.page + 1) + '">' + settings.next + '</a></li>');
            }

            $pagination.find("." + settings.pageLinkClass).not(".previous-page, .next-page").click(function (e) {
                e.preventDefault();
                settings.page = parseInt($(this).text(), 10);
                $owner.pagination(settings);
                // $owner.trigger("changePage", [settings.page]);
                this.changePage();
            });

            $pagination.find(".previous-page").click(function (e) {
                e.preventDefault();
                if (settings.page > 1) {
                    settings.page--;
                    $owner.pagination(settings);
                    // $owner.trigger("changePage", [settings.page]);
                    this.changePage();
                }
            });

            $pagination.find(".next-page").click(function (e) {
                e.preventDefault();
                if (settings.page < settings.count) {
                    settings.page++;
                    $owner.pagination(settings);
                    // $owner.trigger("changePage", [settings.page]);
                    this.changePage();
                }
            });
        });
    }
})(jQuery);