<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('journeyTextItem.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.journeyTextItem.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="journeyTextItem.name">
        <div class="validation-message">
            {{ $errors->first('journeyTextItem.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.journeyTextItem.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('journeyTextItem.text') ? 'invalid' : '' }}">
        <label class="form-label" for="text">{{ trans('cruds.journeyTextItem.fields.text') }}</label>
        <input class="form-control" type="text" name="text" id="text" wire:model.defer="journeyTextItem.text">
        <div class="validation-message">
            {{ $errors->first('journeyTextItem.text') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.journeyTextItem.fields.text_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('journeyTextItem.start') ? 'invalid' : '' }}">
        <label class="form-label" for="start">{{ trans('cruds.journeyTextItem.fields.start') }}</label>
        <x-date-picker class="form-control" wire:model="journeyTextItem.start" id="start" name="start" />
        <div class="validation-message">
            {{ $errors->first('journeyTextItem.start') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.journeyTextItem.fields.start_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('journeyTextItem.end') ? 'invalid' : '' }}">
        <label class="form-label" for="end">{{ trans('cruds.journeyTextItem.fields.end') }}</label>
        <x-date-picker class="form-control" wire:model="journeyTextItem.end" id="end" name="end" />
        <div class="validation-message">
            {{ $errors->first('journeyTextItem.end') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.journeyTextItem.fields.end_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('journeyTextItem.active') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.journeyTextItem.fields.active') }}</label>
        @foreach($this->listsForFields['active'] as $key => $value)
            <label class="radio-label"><input type="radio" name="active" wire:model="journeyTextItem.active" value="{{ $key }}">{{ $value }}</label>
        @endforeach
        <div class="validation-message">
            {{ $errors->first('journeyTextItem.active') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.journeyTextItem.fields.active_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('journeyTextItem.frequency') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.journeyTextItem.fields.frequency') }}</label>
        @foreach($this->listsForFields['frequency'] as $key => $value)
            <label class="radio-label"><input type="radio" name="frequency" wire:model="journeyTextItem.frequency" value="{{ $key }}">{{ $value }}</label>
        @endforeach
        <div class="validation-message">
            {{ $errors->first('journeyTextItem.frequency') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.journeyTextItem.fields.frequency_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.journey-text-items.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>