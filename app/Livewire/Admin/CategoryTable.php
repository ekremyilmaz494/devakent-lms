<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryTable extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $color = '#3B82F6';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name,' . $this->editingId,
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    protected $messages = [
        'name.required' => 'Kategori adı zorunludur.',
        'name.unique' => 'Bu kategori adı zaten mevcut.',
        'color.regex' => 'Geçerli bir renk kodu girin (örn: #3B82F6).',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'color']);
        $this->color = '#3B82F6';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editingId = $cat->id;
        $this->name = $cat->name;
        $this->color = $cat->color;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = ['name' => $this->name, 'color' => $this->color];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Kategori güncellendi.');
        } else {
            Category::create($data);
            session()->flash('success', 'Kategori oluşturuldu.');
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name', 'color']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $cat = Category::findOrFail($this->deletingId);

        if ($cat->courses()->count() > 0) {
            session()->flash('error', 'Bu kategoriye bağlı eğitimler bulunmaktadır. Önce eğitimlerin kategorisini değiştirin.');
            $this->showDeleteModal = false;
            return;
        }

        $cat->delete();
        session()->flash('success', 'Kategori silindi.');
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->withCount('courses')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.category-table', compact('categories'));
    }
}
