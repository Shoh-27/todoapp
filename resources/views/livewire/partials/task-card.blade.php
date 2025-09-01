<div class="bg-white p-4 rounded-lg shadow-md cursor-grab transition-shadow hover:shadow-lg" x-sortable-item="{{ $task->id }}">
    {{-- Vazifa sarlavhasi va tavsifi --}}
    <div>
        <p class="font-semibold {{ $task->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">{{ $task->title }}</p>
        @if($task->description )
            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 50) }}</p>
        @endif
    </div>

    {{-- Boshqaruv tugmalari --}}
    <div class="text-xs mt-3 flex justify-between items-center">
        <button wire:click="toggleTask({{ $task->id }})" class="text-gray-500 hover:text-green-600 transition" title="Bajarildi/Bekor qilish">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
        <div class="flex gap-3">
            <button wire:click="startEditing({{ $task->id }} )" class="text-gray-500 hover:text-blue-600 transition" title="Tahrirlash">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z" />
                </svg>
            </button>
            <button wire:click="deleteTask({{ $task->id }} )" wire:confirm="Haqiqatan ham bu vazifani o'chirmoqchimisiz?" class="text-gray-500 hover:text-red-600 transition" title="O'chirish">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</div>

