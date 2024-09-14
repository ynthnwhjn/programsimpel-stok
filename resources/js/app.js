window.$ = window.jQuery = require('jquery');
window.moment = require('moment');
require('datatables.net');
require('datatables.net-bs');
require('bootstrap');
require('angular');
require('angular-ui-bootstrap');
require('angular-toastr');
require('angular-datatables');
require('eonasdan-bootstrap-datetimepicker');
require('angular-validation/dist/angular-validation');
require('angular-validation/dist/angular-validation-rule');
require('angular-ui-grid/ui-grid');
require('./adminlte');

(function () {
    'use strict';

    var app = angular.module('programsimpel', [
        'ui.grid',
        'ui.grid.edit',
        'ui.grid.rowEdit',
        'ui.grid.cellNav',
        'ui.grid.validate',
        'ui.grid.resizeColumns',
        'ui.grid.selection',
        'ui.grid.autoResize',
        'ui.grid.pinning',
        'ui.grid.pagination',
        'ui.grid.expandable',
        'ui.bootstrap',
        'validation',
        'validation.rule',
        'toastr',
        'datatables',
    ]);

    app.config(function ($validationProvider) {
        $validationProvider.showSuccessMessage = false;
        $validationProvider.setValidMethod('submit');
    });

    app.run(function ($templateCache, DTDefaultOptions) {
        console.log('run...')

        DTDefaultOptions.setLanguage({
            'lengthMenu': '_MENU_',
            'search': '',
            'searchPlaceholder': 'Search',
            'info': '_START_ - _END_ / _TOTAL_',
            'paginate': {
                "previous": "<i class='fa fa-angle-double-left'></i>",
                "next": "<i class='fa fa-angle-double-right'></i>"
            },
        });

        $templateCache.put('grid-form/input-search',
            "<div grid-form-input-search=\"col.colDef.searchOptions\"><input placeholder=\"Search...\" type=\"INPUT_TYPE\" ng-class=\"'colt' + col.uid\" uib-typeahead=\"item as item.label for item in getItems($viewValue)\" typeahead-append-to-body=\"true\" typeahead-on-select=\"onSelect($item, $model, $label, $event)\" ng-model=\"MODEL_COL_FIELD\"></div>"
        );
    });

    app.directive('datetimepicker', function ($parse) {
        return {
            require: 'ngModel',
            link: function ($scope, elem, attrs, ngModelCtrl) {
                console.log('datetimepicker...')

                var dpOptions = $parse(attrs.datetimepicker)($scope);
                elem.datetimepicker(dpOptions);

                ngModelCtrl.$render = function () {
                    if (ngModelCtrl.$viewValue) {
                        var newValue = moment(ngModelCtrl.$viewValue).format(dpOptions.format);
                        elem.data('DateTimePicker').date(newValue);
                    }
                }

                elem.on('dp.change', function (evt) {
                    if(evt.date) {
                        var newValue = moment(evt.date).format('YYYY-MM-DD HH:mm:ss');
                        ngModelCtrl.$setViewValue(newValue);
                    }
                });
            }
        }
    })

})();
