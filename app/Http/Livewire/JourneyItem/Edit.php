<?php

namespace App\Http\Livewire\JourneyItem;

use App\Models\JourneyItem;
use Livewire\Component;

class Edit extends Component
{
    public JourneyItem $journeyItem;

    public array $listsForFields = [];

    public function mount(JourneyItem $journeyItem)
    {
        $this->journeyItem = $journeyItem;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.journey-item.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->journeyItem->save();

        return redirect()->route('admin.journey-items.index');
    }

    protected function rules(): array
    {
        return [
            'journeyItem.name' => [
                'string',
                'nullable',
            ],
            'journeyItem.body' => [
                'string',
                'required',
            ],
            'journeyItem.start' => [
                'required',
                'date_format:' . config('project.datetime_format'),
            ],
            'journeyItem.end' => [
                'required',
                'date_format:' . config('project.datetime_format'),
            ],
            'journeyItem.frequency' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['frequency'])),
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['frequency'] = $this->journeyItem::FREQUENCY_RADIO;
    }
}
