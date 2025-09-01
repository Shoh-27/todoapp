<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vazifalar Ro\'yxati') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Yangi vazifa qo'shish formasi --}}
                    <form action="{{ route('tasks.store') }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex flex-col sm:flex-row items-end gap-2">
                            <div class="flex-grow">
                                <x-input-label for="title" :value="__('Yangi vazifa')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>
                            <div>
                                <x-input-label for="deadline" :value="__('Muddati')" />
                                <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" />
                            </div>
                            <x-primary-button>{{ __('Qo\'shish') }}</x-primary-button>
                        </div>
                    </form>

                    {{-- Vazifalar ro'yxati --}}
                    <div class="space-y-4">
                        @forelse ($tasks as $task)
                            <div class="flex items-center justify-between p-4 border rounded-lg {{ $task->is_completed ? 'bg-gray-100' : '' }}">
                                <div class="flex-grow">
                                    <span class="{{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                                        {{ $task->title }}
                                    </span>
                                    @if($task->deadline)
                                        <div class="text-sm text-gray-500 mt-1">
                                            <span class="font-bold">Muddati:</span> {{ $task->deadline->format('d.m.Y') }}
                                            @if(!$task->is_completed && $task->deadline->isPast())
                                                <span class="text-red-500 font-semibold">(Muddati o'tgan)</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    {{-- Bajarildi/Bekor qilish tugmasi --}}
                                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 text-sm rounded {{ $task->is_completed ? 'bg-yellow-400 hover:bg-yellow-500' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                                            {{ $task->is_completed ? 'Bekor' : 'Bajarildi' }}
                                        </button>
                                    </form>
                                    {{-- Tahrirlash tugmasi --}}
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="px-3 py-1 text-sm rounded bg-blue-500 hover:bg-blue-600 text-white">Tahrirlash</a>
                                    {{-- O'chirish tugmasi --}}
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-sm rounded bg-red-500 hover:bg-red-600 text-white" onclick="return confirm('Haqiqatan ham o\'chirmoqchimisiz?')">O'chirish</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p>Hozircha vazifalar mavjud emas.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
