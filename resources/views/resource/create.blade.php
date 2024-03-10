@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Resource</h1>
    <form action="{{ route('resources.store') }}" method="POST">
        @csrf

        <!-- Country Dropdown -->
        <div class="form-group">
            <label for="country">Country</label>
            <select class="form-control" id="country" name="country">
                @foreach($countries as $country)
                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Other Resource Dropdown Placeholder -->
        <div class="form-group">
            <label for="otherResource">Other Resource</label>
            <select class="form-control" id="otherResource" name="otherResource">
                @foreach($otherResources as $resource)
                    <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
