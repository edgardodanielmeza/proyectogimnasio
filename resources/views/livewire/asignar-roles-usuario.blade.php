<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <h2 class="text-2xl font-semibold mb-4">Asignar Roles a Usuarios</h2>

                <!-- Campo de búsqueda de usuarios -->
                <div class="mb-4">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="searchTermUser"
                        placeholder="Buscar usuarios por nombre, apellido o email..."
                        class="form-input rounded-md shadow-sm mt-1 block w-full"
                    />
                </div>

                <!-- Lista de usuarios encontrados -->
                @if(!empty($users_list) && strlen($searchTermUser) >= 3)
                    <ul class="border border-gray-300 rounded-md mb-4">
                        @forelse($users_list as $user)
                            <li
                                class="px-4 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                                wire:click="selectUser({{ $user->id }})"
                            >
                                {{ $user->name }} ({{ $user->email }})
                            </li>
                        @empty
                            <li class="px-4 py-2 text-gray-500">No se encontraron usuarios.</li>
                        @endforelse
                    </ul>
                @endif

                <!-- Sección de asignación de roles para el usuario seleccionado -->
                @if($selected_user_info)
                    <div class="mt-6 border-t pt-4">
                        <h3 class="text-xl font-semibold mb-2">
                            Usuario Seleccionado: <span class="font-normal">{{ $selected_user_info->name }} ({{ $selected_user_info->email }})</span>
                        </h3>

                        @if(session()->has('message'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('message') }}</span>
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        <h4 class="text-lg font-medium mb-3">Roles Disponibles:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($roles_list as $role)
                                <div
                                    class="flex items-center justify-between p-3 border rounded-md cursor-pointer
                                           {{ in_array($role->name, $user_roles_list ?? []) ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-gray-100 hover:bg-gray-200' }}"
                                    wire:click="toggleRole('{{ $role->name }}')"
                                >
                                    <span>{{ $role->name }}</span>
                                    @if(in_array($role->name, $user_roles_list ?? []))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 5a1 1 0 112 0v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V5z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500">No hay roles disponibles para asignar.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
