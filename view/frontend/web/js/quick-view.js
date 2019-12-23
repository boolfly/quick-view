/**********************************************************************
 * Quick View Widget
 *
 * @copyright Copyright © Boolfly. All rights reserved.
 * @author    info@boolfly.com
 */
define([
    'jquery',
    'mage/translate',
    'Magento_Customer/js/customer-data',
    'matchMedia',
    'ko',
    'jquery/ui',
    'magnificPopup'
], function ($, $t, customerData, mediaCheck, ko) {
    'use strict';

    $.widget('boolfly.quickView', {
        /**
         * Default Option Value
         */
        options: {
            mediaBreakpoint: '(max-width: 767px)',
            responsive: true,
            loadingHtml: $t('Loading..'),
            quickViewText: $t('Quick View'),
            quickViewClasses: 'btn-quickview',
            quickViewHtml: '<a class="abutton {{quickViewClasses}} quick-view"' + ' title="{{quickViewText}}"' + ' href="{{quickViewUrl}}">' + '<span>{{quickViewText}}</span>' + '</a>',
            productItemSelector: '.product-item-info'
        },

        /**
         * Init Widget
         *
         * @private
         */
        _create: function () {
            if (!this.isProductPage()) {
                this.appendQuickViewLink();
                this.bindQuickViewPopup();
                this.responsiveQuickView();
            }
        },

        /**
         * Check Is Product Page
         *
         * @returns {boolean}
         */
        isProductPage: function () {
            return $('body').hasClass('catalog-product-view') === true;
        },

        /**
         * Append QuickView Link
         */
        appendQuickViewLink: function () {
            var url, buttonHtml = this.getButtonHtml();

            $(this.element).find(this.options.productItemSelector).each(function () {
                var item   = $(this);
                url        = item.find('[data-role=quick-view]').length ? item.find('[data-role=quick-view]').data('url') : '';
                if (url) {
                    var button = buttonHtml.replace('{{quickViewUrl}}', url);
                    $(button).insertAfter(item.find('a.product-item-photo'));
                }
            });

        },

        /**
         * Get Button Html
         *
         * @returns {string}
         */
        getButtonHtml: function () {
            var buttonHtml, options = this.options;
            buttonHtml              = options.quickViewHtml.replace(new RegExp('{{quickViewText}}', 'g'), options.quickViewText);
            return buttonHtml.replace('{{quickViewClasses}}', options.quickViewClasses);
        },

        /**
         * Bind Event Open Quick View Popup
         */
        bindQuickViewPopup: function () {
            var options = this.options;
            $('.' + options.quickViewClasses).magnificPopup({
                type: 'ajax',
                ajax: {
                    settings: {
                        cache: true
                    },
                    tError: $t('The content could not be loaded.')
                },
                tLoading: options.loadingHtml,
                closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close"></button>',
                callbacks: {
                    ajaxContentAdded: function () {
                        customerData.set('messages', {});
                        $('.mfp-content').trigger('contentUpdated');
                        ko.cleanNode($('.mfp-content')[0]);
                        $('.mfp-content').applyBindings();
                    }
                }
            });
        },

        /**
         * Process Responsive Quick View Link
         */
        responsiveQuickView: function () {
            if (this.options.responsive === true) {
                mediaCheck({
                    media: this.options.mediaBreakpoint,
                    entry: $.proxy(function () {
                        this.toggleMobileMode();
                    }, this),
                exit: $.proxy(function () {
                    this.toggleDesktopMode();
                }, this)
                });
            }
        },

        /**
         * Toggle Mobile Mode
         */
        toggleMobileMode: function () {
            $('.' + this.options.quickViewClasses).hide();
        },

        /**
         * Toggle Desktop & Tablet
         */
        toggleDesktopMode: function () {
            $('.' + this.options.quickViewClasses).show();
        }
    });

    return $.boolfly.quickView;
});