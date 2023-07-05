<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('record_student_item_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="RecordStudentItem" format="csv" />
                <livewire:excel-export model="RecordStudentItem" format="xlsx" />
                <livewire:excel-export model="RecordStudentItem" format="pdf" />
            @endif




        </div>
        <div class="w-full sm:w-1/2 sm:text-right">
            Search:
            <input type="text" wire:model.debounce.300ms="search" class="w-full sm:w-1/3 inline-block" />
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-index w-full">
                <thead>
                    <tr>
                        <th class="w-9">
                        </th>
                        <th>
                            {{ trans('cruds.recordStudentItem.fields.sisid') }}
                            @include('components.table.sort', ['field' => 'sisid'])
                        </th>
                        <th>
                            {{ trans('cruds.recordStudentItem.fields.otherid') }}
                            @include('components.table.sort', ['field' => 'otherid'])
                        </th>
                        <th>
                            {{ trans('cruds.recordStudentItem.fields.full') }}
                            @include('components.table.sort', ['field' => 'full'])
                        </th>
                        <th>
                            {{ trans('cruds.recordStudentItem.fields.preferred') }}
                            @include('components.table.sort', ['field' => 'preferred'])
                        </th>
                        <th>
                            {{ trans('cruds.recordStudentItem.fields.mobile') }}
                            @include('components.table.sort', ['field' => 'mobile'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recordStudentItems as $recordStudentItem)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $recordStudentItem->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $recordStudentItem->sisid }}
                            </td>
                            <td>
                                {{ $recordStudentItem->otherid }}
                            </td>
                            <td>
                                {{ $recordStudentItem->full }}
                            </td>
                            <td>
                                {{ $recordStudentItem->preferred }}
                            </td>
                            <td>
                                {{ $recordStudentItem->mobile }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('record_student_item_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.record-student-items.show', $recordStudentItem) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('record_student_item_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.record-student-items.edit', $recordStudentItem) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('record_student_item_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $recordStudentItem->id }})" wire:loading.attr="disabled">
                                            {{ trans('global.delete') }}
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">No entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body">
        <div class="pt-3">
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $recordStudentItems->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
    if (!confirm("{{ trans('global.areYouSure') }}")) {
        return
    }
@this[e.callback](...e.argv)
})
    </script>
@endpush