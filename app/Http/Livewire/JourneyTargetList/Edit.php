<?php

namespace App\Http\Livewire\JourneyTargetList;

use App\Models\JourneyTargetList;
use Livewire\Component;

class Edit extends Component
{
    public JourneyTargetList $journeyTargetList;

    public function mount(JourneyTargetList $journeyTargetList)
    {
        $this->journeyTargetList = $journeyTargetList;
    }

    public function render()
    {
        return view('livewire.journey-target-list.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->journeyTargetList->save();

        return redirect()->route('admin.journey-target-lists.index');
    }

    protected function rules(): array
    {
        return [
            'journeyTargetList.name' => [
                'string',
                'nullable',
            ],
            'journeyTargetList.description' => [
                'string',
                'nullable',
            ],
            'journeyTargetList.query' => [
                'string',
                'nullable',
            ],
        ];
    }
}
