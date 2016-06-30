+function ($) {
    'use strict';
    $.attributeField = function (selector) {
        return $('[data-attribute-name="' + selector.replace(/\s/g, '').split(',').join('"], [data-attribute-name="') + '"]');
    };

    $.fn.attributeContainer = function (selector) {

        var containers = $();
        selector = selector || '.attribute-form-row';

        this.each(function () {
            var $this = $(this);

            if ($this.is('[data-attribute-name]')) {
                containers = containers.add($this.closest(selector));
            }

        });

        return containers;
    }
}(jQuery);