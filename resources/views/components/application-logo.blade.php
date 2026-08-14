@if(setting('logo'))
    <img src="{{ asset('storage/' . setting('logo')) }}" alt="Logo" class="h-12 w-auto">
@else
    <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" class="h-12 w-auto">
@endif