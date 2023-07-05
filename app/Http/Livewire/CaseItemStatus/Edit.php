<?php

namespace App\Http\Livewire\CaseItemStatus;

use App\Models\CaseItemStatus;
use Livewire\Component;

class Edit extends Component
{
    public CaseItemStatus $caseItemStatus;

    public function mount(CaseItemStatus $caseItemStatus)
    {
        $this->caseItemStatus = $caseItemStatus;
    }

    public function render()
    {
        return view('livewire.case-item-status.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->caseItemStatus->save();

        return redirect()->route('admin.case-item-statuses.index');
    }

    protected function rules(): array
    {
        return [
            'caseItemStatus.status' => [
                'string',
                'required',
            ],
        ];
    }
}
