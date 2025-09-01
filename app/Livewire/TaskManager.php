<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskManager extends Component
{
    use AuthorizesRequests;

    // Barcha vazifalar va kategoriyalar
    public $categories;
    public $tasks;

    // Yangi kategoriya qo'shish uchun
    public $newCategoryName = '';

    // Yangi vazifa qo'shish uchun
    public $newTaskTitle = '';
    public $newTaskDescription = '';
    public $selectedCategory = null;

    // Vazifani tahrirlash uchun
    public $editingTask;
    public $editingTaskTitle = '';
    public $editingTaskDescription = '';

    // Validatsiya qoidalari
    protected function rules()
    {
        return [
            'newCategoryName' => 'required|string|max:255',
            'newTaskTitle' => 'required|string|max:255',
            'newTaskDescription' => 'nullable|string',
            'editingTaskTitle' => 'required|string|max:255',
            'editingTaskDescription' => 'nullable|string',
            'selectedCategory' => 'nullable|exists:categories,id,user_id,' . Auth::id(),
        ];
    }

    // Komponent ilk yuklanganda ishlaydi
    public function mount()
    {
        $this->loadData();
    }

    // Ma'lumotlarni bazadan yuklash
    public function loadData()
    {
        $this->categories = Auth::user()->categories()->with('tasks')->get();
        $this->tasks = Auth::user()->tasks()->whereNull('category_id')->orderBy('position')->get();
    }

    // Yangi kategoriya qo'shish
    public function addCategory()
    {
        $this->validate(['newCategoryName' => $this->rules()['newCategoryName']]);
        Auth::user()->categories()->create(['name' => $this->newCategoryName]);
        $this->reset('newCategoryName');
        $this->loadData();
    }

    // Yangi vazifa qo'shish
    public function addTask()
    {
        $this->validate([
            'newTaskTitle' => $this->rules()['newTaskTitle'],
            'newTaskDescription' => $this->rules()['newTaskDescription'],
            'selectedCategory' => $this->rules()['selectedCategory'],
        ]);

        Auth::user()->tasks()->create([
            'title' => $this->newTaskTitle,
            'description' => $this->newTaskDescription,
            'category_id' => $this->selectedCategory,
        ]);

        $this->reset('newTaskTitle', 'newTaskDescription', 'selectedCategory');
        $this->loadData();
    }

    // Vazifani "bajarildi" deb belgilash
    public function toggleTask(Task $task)
    {
        $this->authorize('update', $task);
        $task->is_completed = !$task->is_completed;
        $task->save();
        $this->loadData();
    }

    // Tahrirlash oynasini ochish
    public function startEditing(Task $task)
    {
        $this->authorize('update', $task);
        $this->editingTask = $task;
        $this->editingTaskTitle = $task->title;
        $this->editingTaskDescription = $task->description;
    }

    // Tahrirlangan vazifani saqlash
    public function saveTask()
    {
        if (!$this->editingTask) return;

        $this->authorize('update', $this->editingTask);
        $this->validate([
            'editingTaskTitle' => $this->rules()['editingTaskTitle'],
            'editingTaskDescription' => $this->rules()['editingTaskDescription'],
        ]);

        $this->editingTask->update([
            'title' => $this->editingTaskTitle,
            'description' => $this->editingTaskDescription,
        ]);

        $this->cancelEditing();
        $this->loadData();
    }

    // Tahrirlashni bekor qilish
    public function cancelEditing()
    {
        $this->reset('editingTask', 'editingTaskTitle', 'editingTaskDescription');
    }

    // Vazifani o'chirish
    public function deleteTask(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        $this->loadData();
    }

    // Drag-and-drop'dan keyin tartibni yangilash
    public function updateTaskOrder($orderedIds)
    {
        foreach ($orderedIds as $item) {
            $task = Task::find($item['value']);
            if ($task && $task->user_id == Auth::id()) {
                $task->update([
                    'position' => $item['order'],
                    'category_id' => $item['parent'] ?: null
                ]);
            }
        }
        $this->loadData();
    }

    // Komponentni render qilish
    public function render()
    {
        return view('livewire.task-manager');
    }
}
