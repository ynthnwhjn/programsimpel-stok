@extends('ps::layouts.admin')

@section('pageTitle', 'Laporan Kartu Stok')

@section('content')
<div class="box" ng-controller="PageController">
    <div class="box-header with-border">
        <h3 class="box-title">@yield('pageTitle')</h3>

        <div class="btn-group">
            <button type="button" class="btn btn-default" ng-click="openFilterModal($event)"><i class="fa fa-search"></i></button>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-hover" datatable dt-options="dtOptions" dt-columns="dtColumns" dt-instance="dtInstance"></table>
    </div>
</div>

<script type="text/ng-template" id="filter_modal.html">
    <div class="modal-header">
        <button type="button" class="close" ng-click="$dismiss('cancel')" aria-label="Close">&times;</button>
        <h4 class="modal-title">Filter</h4>
    </div>
    <form name="frmFilter" class="form-horizontal" autocomplete="off" ng-submit="actionFilter(frmFilter, $event)">
        <div class="modal-body">
            <div class="form-group">
                <label class="control-label col-md-3">Gudang</label>
                <div class="col-md-6">
                    <x-input-search search-options="browseGudang" name="gudang_nama" ng-model="filter.gudang.nama" validator="required" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">Barang</label>
                <div class="col-md-6">
                    <x-input-search search-options="browseBarang" name="barang_nama" ng-model="filter.barang.nama" validator="required" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">Tgl Awal</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="tanggal_awal" ng-model="filter.tanggal_awal" datetimepicker="{'format': 'DD-MM-YYYY'}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">Tgl Akhir</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="tanggal_akhir" ng-model="filter.tanggal_akhir" datetimepicker="{'format': 'DD-MM-YYYY'}">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Search</button>
            <button type="button" class="btn btn-default" ng-click="$dismiss('cancel')">Cancel</button>
        </div>
    </form>
</script>
@endsection

@push('scripts')
<script type="text/javascript">
    (function() {
        'use strict';

        angular.module('programsimpel').controller('PageController', PageController);

        function PageController($scope, $uibModal, $http, $validation, DTOptionsBuilder, DTColumnBuilder) {
            $scope.filter = {};
            $scope.dtInstance = {};
            $scope.items = [];

            $scope.dtOptions = DTOptionsBuilder.newOptions()
                .withOption('ajax', {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    dataSrc: 'data',
                    url: route('laporan_kartu_stok.datasource'),
                    type: 'POST',
                    data: function (d) {
                        d = $scope.filter;
                        console.log(d)
                        console.log($scope.filter)

                        return d;
                    }
                });

            $scope.dtColumns = [
                DTColumnBuilder.newColumn(null)
                    .withTitle('#')
                    .withOption('width', 40)
                    .renderWith(function (data, type, row, meta) {
                        return meta.row + 1;
                    }),
                DTColumnBuilder.newColumn('tanggal').withTitle('Tanggal')
                    .withOption('width', 120),
                DTColumnBuilder.newColumn('kode').withTitle('Kode'),
                DTColumnBuilder.newColumn('masuk').withTitle('Masuk')
                    .withOption('width', 120)
                    .withOption('class', 'dt-right'),
                DTColumnBuilder.newColumn('keluar').withTitle('Keluar')
                    .withOption('width', 120)
                    .withOption('class', 'dt-right'),
                DTColumnBuilder.newColumn('saldo').withTitle('Saldo')
                    .withOption('width', 120)
                    .withOption('class', 'dt-right'),
            ];

            $scope.openFilterModal = function(evt) {
                var btn = angular.element(evt.currentTarget);
                btn.prop('disabled', true);

                var modalInstance = $uibModal.open({
                    templateUrl: 'filter_modal.html',
                    backdrop: 'static',
                    scope: $scope,
                    controller: function($scope, $uibModalInstance) {
                        $scope.browseBarang = {
                            items: function(viewValue) {
                                console.log(viewValue)

                                return $http.get('/api/browse/barang', {
                                    params: {
                                        keyword: viewValue,
                                    },
                                });
                            },
                            onSelect: function(item) {
                                $scope.filter.barang = item.value;
                                $scope.filter.barang_id = item.value.id;
                            },
                        };

                        $scope.browseGudang = {
                            items: function(viewValue) {
                                console.log(viewValue)

                                return $http.get('/api/browse/gudang', {
                                    params: {
                                        keyword: viewValue,
                                    },
                                });
                            },
                            onSelect: function(item) {
                                $scope.filter.gudang = item.value;
                                $scope.filter.gudang_id = item.value.id;
                            },
                        };

                        $scope.actionFilter = function(formCtrl) {
                            $validation.validate(formCtrl)
                                .success(function () {
                                    $uibModalInstance.close();
                                })
                                .error(function () {
                                    //
                                });
                        }
                    }
                });

                modalInstance.result.then(function() {
                    $scope.dtInstance.rerender();

                    btn.prop('disabled', false);
                }, function() {
                    btn.prop('disabled', false);
                });
            }
        }

    })();
</script>
@endpush
