@extends('ps::layouts.admin')

@section('pageTitle', 'Pengeluaran Barang')

@section('content')
<div class="box" ng-controller="PageController">
    <div class="box-header with-border">
        <h3 class="box-title">@yield('pageTitle')</h3>
    </div>
    <form name="frm" ng-submit="save(frm, $event)">
        <div class="box-body">
            <fieldset @if($action_method == 'show') disabled @endif>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" readonly placeholder="AUTO" class="form-control" name="kode" ng-model="formfield.kode">
                        </div>

                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" class="form-control" name="tanggal" ng-model="formfield.tanggal" datetimepicker="{'format': 'DD-MM-YYYY'}" validator="required">
                        </div>

                        <div class="form-group">
                            <label>Gudang</label>
                            <x-input-search search-options="browseGudang" name="gudang_asal_nama" ng-model="formfield.gudang_asal.nama" validator="required" />
                        </div>
                    </div>
                </div>

                <x-grid-form :grid-options="'gridform1'"/>
            </fieldset>
        </div>
        <div class="box-footer">
            @if($action_method != 'show')
                <button type="submit" class="btn btn-primary">Save</button>
            @endif
            <a href="{{ route('pengeluaran_barang.index') }}" class="btn btn-default">Back</a>
        </div>
    </form>
</div>

@if(isset($item))
@javascript('item', $item)
@endif

@endsection

@push('scripts')
<script type="text/javascript">
    (function () {
        'use strict';

        angular.module('programsimpel').controller('PageController', PageController);

        function PageController($scope, $http, $validation, $gridFormValidation, $q) {
            $scope.formfield = {};

            $scope.browseGudang = {
                items: function (viewValue) {
                    console.log(viewValue)

                    return $http.get('/api/browse/gudang', {
                        params: {
                            keyword: viewValue,
                        },
                    });
                },
                onSelect: function (item) {
                    $scope.formfield.gudang_asal = item.value;
                    $scope.formfield.gudangasal_id = item.value.id;
                },
            };

            $scope.browseBarang = {
                items: function (viewValue) {
                    console.log(viewValue)

                    return $http.get('/api/browse/barang', {
                        params: {
                            keyword: viewValue,
                        },
                    });
                },
                onSelect: function (item, rowEntity) {
                    rowEntity.barang = item.value;
                    rowEntity.barang_id = item.value.id;
                },
            };

            $scope.gridform1 = {
                data: [{}],
                columnDefs: [
                    {
                        name: 'barang_nama',
                        field: 'barang.nama',
                        displayName: 'Barang',
                        editableCellTemplate: 'grid-form/input-search',
                        searchOptions: $scope.browseBarang,
                        validators: {
                            required: true
                        },
                        width: 400,
                    },
                    {
                        name: 'jumlah',
                        field: 'jumlah',
                        cellFilter: 'number',
                        cellClass: 'text-right',
                        validators: {
                            required: true
                        },
                        width: 90,
                    },
                    {
                        name: 'keterangan',
                        field: 'keterangan',
                    },
                ],
            };

            if (window.item) {
                $scope.formfield = item;

                $scope.gridform1.data = item.stok_mutasi_detail;
            }

            $scope.save = function (formCtrl, evt) {
                var btnSubmit = angular.element(evt.currentTarget).find('button[type="submit"]');
                btnSubmit.prop('disabled', true);
                evt.preventDefault();

                var q1 = $validation.validate(formCtrl),
                    q2 = $gridFormValidation.validate($scope.gridApi);

                $q.all([q1, q2]).then(function () {
                    $scope.formfield.pengeluaran_barang_detail = $scope.gridform1.data;

                    $http({
                        url: form_action,
                        method: form_method,
                        data: $scope.formfield,
                    })
                        .then(function name(response) {
                            console.log(response)

                            if (response.data.redirect_to) {
                                window.location.href = response.data.redirect_to;
                            }

                            btnSubmit.prop('disabled', false);
                        }, function (rejection) {
                            btnSubmit.prop('disabled', false);
                        });
                }, function () {
                    console.log('reject?')
                    btnSubmit.prop('disabled', false);
                });
            }
        }

    })();
</script>
@endpush
