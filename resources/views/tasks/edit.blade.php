<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vazifani Tahrirlash') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Vazifa nomi')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $task->title)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="deadline" :value="__('Muddati')" />
                            <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline', $task->deadline ? $task->deadline->format('Y-m-d') : '')" />
                            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Saqlash') }}</x-primary-button>
                            <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Bekor qilish') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
