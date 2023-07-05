<?php

namespace App\Http\Livewire\Institution;

use App\Models\Institution;
use Livewire\Component;

class Create extends Component
{
    public Institution $institution;

    public function mount(Institution $institution)
    {
        $this->institution = $institution;
    }

    public function render()
    {
        return view('livewire.institution.create');
    }

    public function submit()
    {
        $this->validate();

        $this->institution->save();

        return redirect()->route('admin.institutions.index');
    }

    protected function rules(): array
    {
        return [
            'institution.code' => [
                'string',
                'nullable',
            ],
            'institution.name' => [
                'string',
                'required',
            ],
            'institution.description' => [
                'string',
                'nullable',
            ],
        ];
    }
}
