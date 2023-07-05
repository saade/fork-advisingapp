<?php

namespace App\Http\Livewire\KbItem;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\KbItem;
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
            'except' => 'question',
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
        $this->sortBy            = 'question';
        $this->sortDirection     = 'desc';
        $this->perPage           = 25;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new KbItem())->orderable;
    }

    public function render()
    {
        $query = KbItem::with(['quality', 'status', 'category', 'institution'])->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $kbItems = $query->paginate($this->perPage);

        return view('livewire.kb-item.index', compact('kbItems', 'query'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('kb_item_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        KbItem::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(KbItem $kbItem)
    {
        abort_if(Gate::denies('kb_item_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $kbItem->delete();
    }
}
