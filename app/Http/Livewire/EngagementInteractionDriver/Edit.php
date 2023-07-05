<?php

namespace App\Http\Livewire\EngagementInteractionDriver;

use App\Models\EngagementInteractionDriver;
use Livewire\Component;

class Edit extends Component
{
    public EngagementInteractionDriver $engagementInteractionDriver;

    public function mount(EngagementInteractionDriver $engagementInteractionDriver)
    {
        $this->engagementInteractionDriver = $engagementInteractionDriver;
    }

    public function render()
    {
        return view('livewire.engagement-interaction-driver.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->engagementInteractionDriver->save();

        return redirect()->route('admin.engagement-interaction-drivers.index');
    }

    protected function rules(): array
    {
        return [
            'engagementInteractionDriver.driver' => [
                'string',
                'required',
            ],
        ];
    }
}
