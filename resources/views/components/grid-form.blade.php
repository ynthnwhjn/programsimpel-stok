<div grid-form="{{ $gridOptions }}">
    @if($actionMethod == 'show')
        <div style="height: 250px;" ui-grid="gridOptions" ui-grid-auto-resize ui-grid-resize-columns></div>
    @else
        <div style="height: 250px;" ui-grid="gridOptions" ui-grid-auto-resize ui-grid-resize-columns ui-grid-edit ui-grid-cellNav ui-grid-selection ui-grid-validate></div>

        <div style="margin-top: 8px; margin-bottom: 8px;">
            <button type="button" class="btn btn-default" ng-click="actionAddGridRow($event)">
                <i class="fa fa-plus"></i>
            </button>
            <button type="button" class="btn btn-default" ng-click="actionDeleteGridRow($event)">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    @endif
</div>
