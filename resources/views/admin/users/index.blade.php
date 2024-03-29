<?php

/**
 * @var \App\Models\User[] $users
 */
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Пользователи') }}
        </h2>
    </x-slot>
    <script src="/js/app.js"></script>

    <div class="py-12">
        <div id="example"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- This example requires Tailwind CSS v2.0+ -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Имя
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Статус
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Роль
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Изменить</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full"
                                                         src="{{$user->avatar}}"
                                                         alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{$user->name}}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{$user->email}} | {{ $user->login ? "@" . $user->login : 'Нет логина'}}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{$user->status === 'active' ? 'green' : 'red'}}-100 {{$user->isActive() ? 'text-green-800' : 'text-red-600'}}">
                                              {{$user->status}}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{$user->role}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button class="text-indigo-600 hover:text-indigo-900 user-form" data-json='@json($user)'>Изменить</button>
                                        </td>
                                    </tr>

                                @endforeach

                                <!-- More people... -->
                                </tbody>
                            </table>

                        </div>

                        <div class="mt-6">
                            {{$users->links()}}

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
