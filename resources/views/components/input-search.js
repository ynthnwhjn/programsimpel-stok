(function () {
    'use strict';

    angular.module('programsimpel')
        .directive('inputSearch', function ($parse) {
            return {
                scope: true,
                link: function ($scope, elem, attrs) {
                    console.log(attrs)

                    var searchOptions = $parse(attrs.inputSearch)($scope);
                    console.log(searchOptions)

                    $scope.getItems = function (viewValue) {
                        return searchOptions.items(viewValue)
                            .then(function (response) {
                                return response.data.items;
                            });
                    }

                    $scope.onSelect = function ($item, $model, $label, $event) {
                        console.log($item)
                        if(searchOptions.onSelect) {
                            searchOptions.onSelect($item);
                        }
                    }
                }
            }
        })

        .directive('gridFormInputSearch', function ($parse, $timeout, uiGridEditConstants) {
            return {
                scope: true,
                require: ['?^uiGrid', '?^uiGridRenderContainer'],
                link: function ($scope, elem, attrs) {
                    var searchOptions = $parse(attrs.gridFormInputSearch)($scope);

                    console.log('gridFormInputSearch...')
                    var elemInput = elem.find('input');

                    $scope.$on(uiGridEditConstants.events.BEGIN_CELL_EDIT, function () {
                        $timeout(function () {
                            elemInput.focus();
                        });
                    });

                    $scope.getItems = function (viewValue) {
                        return searchOptions.items(viewValue)
                            .then(function (response) {
                                return response.data.items;
                            });
                    }

                    $scope.onSelect = function ($item, $model, $label, $event) {
                        console.log($item)
                        if (searchOptions.onSelect) {
                            searchOptions.onSelect($item, $scope.row.entity);
                        }

                        $scope.$emit(uiGridEditConstants.events.END_CELL_EDIT);
                    }

                    $scope.$on('$destroy', function unbindEvents() {
                        elem.off();
                    });
                }
            }
        })

})();
