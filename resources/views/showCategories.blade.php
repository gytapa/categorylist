@extends('main')
<a id="back" href="/"> Back to Homepage</a>
@if (isset($response))
@if ($response == 'true')
    <div class="alert alert-success">
        {{$message}}
    </div>
@endif
@if ($response == 'false')
    <div class="alert alert-danger">
        {{$message}}
    </div>
@endif
@endif

    <h1>Categories</h1>
<span>Add new category without subcategory:</span>
{!! $table !!}