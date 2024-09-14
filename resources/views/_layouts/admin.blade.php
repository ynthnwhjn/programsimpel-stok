<!DOCTYPE html>
<html lang="en" ng-app="programsimpel">
@include('shared.head')
<body class="hold-transition skin-black sidebar-mini fixed">
<div class="wrapper">
    @include('shared.main-header')
    @include('shared.main-sidebar')
    <div class="content-wrapper">
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

@routes
@javascript('form_action', $form_action)
@javascript('form_method', $form_method)
<script src="/js/app.js"></script>
@stack('scripts')
<script src="/js/app.components.js"></script>
</body>
</html>
