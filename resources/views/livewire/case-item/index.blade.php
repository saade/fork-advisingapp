<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select
                class="form-select w-full sm:w-1/6"
                wire:model="perPage"
            >
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('case_item_delete')
                <button
                    class="btn btn-rose ml-3 disabled:cursor-not-allowed disabled:opacity-50"
                    type="button"
                    wire:click="confirm('deleteSelected')"
                    wire:loading.attr="disabled"
                    {{ $this->selectedCount ? '' : 'disabled' }}
                >
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if (file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export
                    format="csv"
                    model="CaseItem"
                />
                <livewire:excel-export
                    format="xlsx"
                    model="CaseItem"
                />
                <livewire:excel-export
                    format="pdf"
                    model="CaseItem"
                />
            @endif

            @can('case_item_create')
                <x-csv-import route="{{ route('admin.case-items.csv.store') }}" />
            @endcan

        </div>
        <div class="w-full sm:w-1/2 sm:text-right">
            Search:
            <input
                class="inline-block w-full sm:w-1/3"
                type="text"
                wire:model.debounce.300ms="search"
            />
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-index table w-full">
                <thead>
                    <tr>
                        <th class="w-9">
                        </th>
                        <th class="w-28">
                            {{ trans('cruds.caseItem.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.caseItem.fields.casenumber') }}
                            @include('components.table.sort', ['field' => 'casenumber'])
                        </th>
                        <th>
                            {{ trans('cruds.caseItem.fields.student') }}
                            @include('components.table.sort', ['field' => 'student.full'])
                        </th>
                        <th>
                            {{ trans('cruds.recordStudentItem.fields.sisid') }}
                            @include('components.table.sort', ['field' => 'student.sisid'])
                        </th>
                        <th>
                            {{ trans('cruds.recordStudentItem.fields.otherid') }}
                            @include('components.table.sort', ['field' => 'student.otherid'])
                        </th>
                        <th>
                            {{ trans('cruds.caseItem.fields.institution') }}
                            @include('components.table.sort', ['field' => 'institution.name'])
                        </th>
                        <th>
                            {{ trans('cruds.caseItem.fields.assigned_to') }}
                            @include('components.table.sort', ['field' => 'assigned_to.name'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($caseItems as $caseItem)
                        <tr>
                            <td>
                                <input
                                    type="checkbox"
                                    value="{{ $caseItem->id }}"
                                    wire:model="selected"
                                >
                            </td>
                            <td>
                                {{ $caseItem->id }}
                            </td>
                            <td>
                                {{ $caseItem->casenumber }}
                            </td>
                            <td>
                                @if ($caseItem->student)
                                    <span class="badge badge-relationship">{{ $caseItem->student->full ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($caseItem->student)
                                    {{ $caseItem->student->sisid ?? '' }}
                                @endif
                            </td>
                            <td>
                                @if ($caseItem->student)
                                    {{ $caseItem->student->otherid ?? '' }}
                                @endif
                            </td>
                            <td>
                                @if ($caseItem->institution)
                                    <span
                                        class="badge badge-relationship">{{ $caseItem->institution->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($caseItem->assignedTo)
                                    <span
                                        class="badge badge-relationship">{{ $caseItem->assignedTo->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('case_item_show')
                                        <a
                                            class="btn btn-sm btn-info mr-2"
                                            href="{{ route('admin.case-items.show', $caseItem) }}"
                                        >
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('case_item_edit')
                                        <a
                                            class="btn btn-sm btn-success mr-2"
                                            href="{{ route('admin.case-items.edit', $caseItem) }}"
                                        >
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('case_item_delete')
                                        <button
                                            class="btn btn-sm btn-rose mr-2"
                                            type="button"
                                            wire:click="confirm('delete', {{ $caseItem->id }})"
                                            wire:loading.attr="disabled"
                                        >
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
            @if ($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $caseItems->links() }}
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
