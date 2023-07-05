<?php

namespace App\Http\Livewire\EngagementInteractionRelation;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\EngagementInteractionRelation;
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
            'except' => 'relation',
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
        $this->sortBy            = 'relation';
        $this->sortDirection     = 'desc';
        $this->perPage           = 10;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new EngagementInteractionRelation())->orderable;
    }

    public function render()
    {
        $query = EngagementInteractionRelation::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $engagementInteractionRelations = $query->paginate($this->perPage);

        return view('livewire.engagement-interaction-relation.index', compact('engagementInteractionRelations', 'query'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('engagement_interaction_relation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        EngagementInteractionRelation::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(EngagementInteractionRelation $engagementInteractionRelation)
    {
        abort_if(Gate::denies('engagement_interaction_relation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $engagementInteractionRelation->delete();
    }
}
