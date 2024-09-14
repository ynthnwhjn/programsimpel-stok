<div input-search="{{ $searchOptions }}">
    <input type="text" placeholder="Search..." class="form-control" {{ $attributes }} uib-typeahead="item as item.label for item in getItems($viewValue)" typeahead-on-select="onSelect($item, $model, $label, $event)">
</div>
