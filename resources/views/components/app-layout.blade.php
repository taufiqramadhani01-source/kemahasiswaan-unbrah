@props(['title' => null])
@component('layouts.app', ['title' => $title])
    {{ $slot }}
@endcomponent
