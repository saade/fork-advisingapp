@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.edit') }}
                    {{ trans('cruds.journeyItem.title_singular') }}:
                    {{ trans('cruds.journeyItem.fields.id') }}
                    {{ $journeyItem->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('journey-item.edit', [$journeyItem])
        </div>
    </div>
</div>
@endsection