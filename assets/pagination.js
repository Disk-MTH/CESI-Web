(function($, window) {
    $.fn.pagination = function(options) {
        var $owner = this,
            settings = $.extend({
                    count: 0, //total pages
                    page: 1, //active page
                    maxVisible: null, //max visible pages at the same time
                    href: 'javascript:void(0);', //href for each page
                    hrefVariable: '{{number}}', //variable for href
                    nextIcon: 'Next', //next page lot icon (a lot of page is maxVisible)
                    prevIcon: 'Previous', //previous page lot icon
                },
                $owner.data('settings') || {},
                options || {});

        if(settings.count <= 0) return this;
        if (settings.page < 1) settings.page = 1;
        if (!settings.maxVisible || isNaN(settings.maxVisible)) settings.maxVisible = settings.count;

        function href(c) {
            return settings.href.replace(settings.hrefVariable, c);
        }

        return this.each(function() {
            var $container = $(this);
            var start = settings.page - Math.floor(settings.maxVisible / 2);
            start = Math.max(start, 1);
            start = Math.min(start, 1 + settings.count - settings.maxVisible);
            var end = Math.min(start + settings.maxVisible - 1, settings.count);

            $container.empty();
            $container.append('<ul class="pagination"></ul>');
            var $pagination = $container.find('.pagination');

            if (settings.page > 1) {
                $pagination.append('<li class="page-item"><a class="page-link" href="' + href(settings.page - 1) + '">' + settings.prevIcon + '</a></li>');
            }

            for (var i = start; i <= end; i++) {
                var className = i === settings.page ? 'page-item active' : 'page-item';
                $pagination.append('<li class="' + className + '"><a class="page-link" href="' + href(i) + '">' + i + '</a></li>');
            }

            if (settings.page < settings.count) {
                $pagination.append('<li class="page-item"><a class="page-link" href="' + href(settings.page + 1) + '">' + settings.nextIcon + '</a></li>');
            }

            $pagination.find('.page-link').click(function(e) {
                e.preventDefault();
                settings.page = parseInt($(this).text(), 10);
                $owner.pagination(settings);
            });
        });
    }
})(jQuery, window);