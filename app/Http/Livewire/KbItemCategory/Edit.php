<?php

namespace App\Http\Livewire\KbItemCategory;

use App\Models\KbItemCategory;
use Livewire\Component;

class Edit extends Component
{
    public KbItemCategory $kbItemCategory;

    public function mount(KbItemCategory $kbItemCategory)
    {
        $this->kbItemCategory = $kbItemCategory;
    }

    public function render()
    {
        return view('livewire.kb-item-category.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->kbItemCategory->save();

        return redirect()->route('admin.kb-item-categories.index');
    }

    protected function rules(): array
    {
        return [
            'kbItemCategory.category' => [
                'string',
                'required',
            ],
        ];
    }
}
