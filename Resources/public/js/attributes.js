+function ($) {
    'use strict';
    $.attributeField = function (selector) {
        return $('[data-attribute-name="' + selector.replace(/\s/g, '').split(',').join('"], [data-attribute-name="') + '"]');
    };

    $.fn.attributeContainer = function () {

        var containers = $();

        this.each(function () {
            var $this = $(this);

            if ($this.is('[data-attribute-name]')) {
                containers = containers.add($this.closest('.attribute-form-row'));
            }

        });

        return containers;
    }
}(jQuery);