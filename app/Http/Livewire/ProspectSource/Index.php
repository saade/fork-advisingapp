<?php

namespace App\Http\Livewire\ProspectSource;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\ProspectSource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSorting, WithConfirmation;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'source',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->sortBy            = 'source';
        $this->sortDirection     = 'desc';
        $this->perPage           = 10;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new ProspectSource())->orderable;
    }

    public function render()
    {
        $query = ProspectSource::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $prospectSources = $query->paginate($this->perPage);

        return view('livewire.prospect-source.index', compact('prospectSources', 'query'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('prospect_source_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ProspectSource::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(ProspectSource $prospectSource)
    {
        abort_if(Gate::denies('prospect_source_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prospectSource->delete();
    }
}
