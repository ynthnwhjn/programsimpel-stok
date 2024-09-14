(function () {
    'use strict';

    angular.module('programsimpel')
        .factory('$gridFormValidation', function ($q, uiGridValidateService) {
            return {
                validate: function (gridApi) {
                    var deferred = $q.defer();
                    var errors = {};

                    console.log('gridFormValidation.validate...')
                    console.log(gridApi)

                    deferred.promise.success = function (fn) {
                        deferred.promise.then(function (value) {
                            fn(value);
                        });
                        return deferred.promise;
                    };

                    deferred.promise.error = function (fn) {
                        deferred.promise.then(null, function (value) {
                            fn(value);
                        });
                        return deferred.promise;
                    };

                    angular.forEach(gridApi.grid.renderContainers.body.visibleRowCache, function (row, rowIndex) {
                        angular.forEach(gridApi.grid.renderContainers.body.visibleColumnCache, function (col, colIndex) {
                            if (col.colDef.validators) {
                                var cellValue = gridApi.grid.getCellValue(row, col);

                                uiGridValidateService.runValidators(row.entity, col.colDef, cellValue, NaN, gridApi.grid);
                                console.log(cellValue)

                                if (!cellValue) {
                                    uiGridValidateService.setInvalid(row.entity, col.colDef);
                                    console.log('setInvalid...')

                                    if ('required' in col.colDef.validators) {
                                        uiGridValidateService.setError(row.entity, col.colDef, 'required');
                                    }
                                }

                                var isInvalid = uiGridValidateService.isInvalid(row.entity, col.colDef);
                                console.log('isInvalid...', isInvalid)

                                if (isInvalid) {
                                    errors[col.name] = uiGridValidateService.getErrorMessages(row.entity, col.colDef);
                                }
                            }
                        });
                    });

                    if (Object.keys(errors).length) {
                        deferred.reject('error');
                    }
                    else {
                        deferred.resolve('success');
                    }

                    return deferred.promise;
                }
            }
        })
        .directive('gridForm', function ($parse, $timeout, $gridFormValidation, toastr) {
            return {
                link: function ($scope, elem, attrs) {
                    $scope.gridFormOptions = $parse(attrs.gridForm)($scope);

                    function handleWindowResize() {
                        $timeout(function () {
                            $scope.gridApi.core.handleWindowResize();
                        }, 500);
                    }

                    angular.forEach($scope.gridFormOptions.columnDefs, function (column) {
                        column.cellTooltip = true;
                        column.headerTooltip = true;

                        if (column.validators !== undefined) {
                            column.cellTemplate = 'ui-grid/cellTitleValidator';

                            if (column.validators.required == true) {
                                column.headerCellClass = 'required';
                            }
                        }
                    });

                    var defaultOptions = {
                        enableColumnResizing: true,
                        enableSelectAll: true,
                        enableFullRowSelection: false,
                        multiSelect: true,
                        enableSorting: false,
                        enableColumnMenus: false,
                        onRegisterApi: function (gridApi) {
                            console.log(gridApi)
                            $scope.gridApi = gridApi;

                            $scope.gridFormOptions.gridApi = gridApi;

                            angular.element('.sidebar-toggle').on('click', function () {
                                console.log('sidebar-toggle click')
                                handleWindowResize();
                            });

                            handleWindowResize();
                        }
                    };

                    $scope.gridOptions = angular.extend(defaultOptions, $scope.gridFormOptions);

                    $scope.actionAddGridRow = function (evt) {
                        $gridFormValidation.validate($scope.gridApi)
                            .success(function () {
                                $scope.gridOptions.data.push({});
                            })
                            .error(function () {
                                toastr.error('Detail wajib diisi');
                            });
                    }

                    $scope.actionDeleteGridRow = function (evt) {
                        var selectedRows = $scope.gridApi.selection.getSelectedRows();

                        if (selectedRows.length) {
                            for (var a = 0; a < selectedRows.length; a++) {
                                var selectedIndex = $scope.gridOptions.data.indexOf(selectedRows[a]);
                                $scope.gridOptions.data.splice(selectedIndex, 1);
                            }
                        }
                    }

                    console.log($scope.gridOptions)
                }
            }
        })
})();
