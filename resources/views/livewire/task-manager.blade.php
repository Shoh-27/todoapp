<div>
    {{-- Sarlavha --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vazifalar Doskasi (Drag & Drop)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 🔹 Formlar qismi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kategoriya qo‘shish --}}
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <form wire:submit.prevent="addCategory" class="space-y-4">
                        <h3 class="text-lg font-semibold">➕ Yangi Kategoriya</h3>
                        <input wire:model.defer="newCategoryName" type="text"
                               placeholder="Kategoriya nomi"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-300"
                               required>
                        @error('newCategoryName')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                            Qo'shish
                        </button>
                    </form>
                </div>

                {{-- Vazifa qo‘shish --}}
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <form wire:submit.prevent="addTask" class="space-y-4">
                        <h3 class="text-lg font-semibold">📝 Yangi Vazifa</h3>

                        <input wire:model.defer="newTaskTitle" type="text"
                               placeholder="Vazifa nomi"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-300"
                               required>

                        <textarea wire:model.defer="newTaskDescription"
                                  placeholder="Vazifa tavsifi (ixtiyoriy)"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-300"></textarea>

                        <select wire:model.defer="selectedCategory"
                                class="w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Kategoriyani tanlang --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <button type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md w-full transition">
                            Vazifa Qo'shish
                        </button>
                    </form>
                </div>
            </div>

            {{-- 🔹 Drag & Drop maydonlari --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                 x-data
                 x-init="
                     Array.from($el.querySelectorAll('[x-sortable]')).forEach(el => {
                         new Sortable(el, {
                             group: 'tasks',
                             animation: 150,
                             onEnd: (evt) => {
                                 let items = Array.from(evt.to.children).map((item, index) => ({
                                     order: index + 1,
                                     value: item.getAttribute('x-sortable-item'),
                                     parent: evt.to.getAttribute('x-sortable-parent')
                                 }));
                                 $wire.call('updateTaskOrder', items);
                             }
                         });
                     });
                 ">

                {{-- Kategoriyalardagi vazifalar --}}
                @foreach ($categories as $category)
                    <div class="bg-gray-100 p-4 rounded-xl shadow-inner">
                        <h3 class="font-semibold text-lg mb-4">{{ $category->name }}</h3>
                        <div class="space-y-3 min-h-[120px]" x-sortable x-sortable-parent="{{ $category->id }}">
                            @forelse ($category->tasks as $task)
                                <div class="bg-white p-3 rounded-md shadow cursor-grab hover:bg-gray-50 transition"
                                     x-sortable-item="{{ $task->id }}">
                                    <p class="{{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                                        {{ $task->title }}
                                    </p>
                                    @if($task->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                                    @endif

                                    <div class="text-xs mt-2 flex justify-between items-center">
                                        <button wire:click="toggleTask({{ $task->id }})"
                                                class="text-blue-500 hover:text-blue-700">
                                            {{ $task->is_completed ? 'Bekor qilish' : 'Bajarildi' }}
                                        </button>
                                        <div class="flex gap-2">
                                            <button wire:click="startEditing({{ $task->id }})"
                                                    class="text-yellow-500 hover:text-yellow-700">✏️</button>
                                            <button wire:click="deleteTask({{ $task->id }})"
                                                    class="text-red-500 hover:text-red-700">🗑️</button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 italic text-sm">Vazifalar yo‘q</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

                {{-- Umumiy vazifalar --}}
                <div class="bg-gray-100 p-4 rounded-xl shadow-inner">
                    <h3 class="font-semibold text-lg mb-4">Umumiy</h3>
                    <div class="space-y-3 min-h-[120px]" x-sortable x-sortable-parent="">
                        @forelse ($tasks as $task)
                            <div class="bg-white p-3 rounded-md shadow cursor-grab hover:bg-gray-50 transition"
                                 x-sortable-item="{{ $task->id }}">
                                <p class="{{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                                    {{ $task->title }}
                                </p>
                                @if($task->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                                @endif

                                <div class="text-xs mt-2 flex justify-between items-center">
                                    <button wire:click="toggleTask({{ $task->id }})"
                                            class="text-blue-500 hover:text-blue-700">
                                        {{ $task->is_completed ? 'Bekor qilish' : 'Bajarildi' }}
                                    </button>
                                    <div class="flex gap-2">
                                        <button wire:click="startEditing({{ $task->id }})"
                                                class="text-yellow-500 hover:text-yellow-700">✏️</button>
                                        <button wire:click="deleteTask({{ $task->id }})"
                                                class="text-red-500 hover:text-red-700">🗑️</button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-sm">Vazifalar yo‘q</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔹 Modal: Vazifa tahrirlash --}}
    @if($editingTask)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-bold mb-4">✏️ Vazifani tahrirlash</h3>
                <form wire:submit.prevent="saveTask" class="space-y-4">
                    <input wire:model.defer="editingTaskTitle" type="text"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-300" required>
                    <textarea wire:model.defer="editingTaskDescription"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-300"
                              placeholder="Vazifa tavsifi (ixtiyoriy)"></textarea>
                    <div class="flex justify-end mt-4 gap-2">
                        <button type="button" wire:click="cancelEditing"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Bekor</button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- SortableJS --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    @endpush
</div>
