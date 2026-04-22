<div>
    <x-filament::page>
        <x-slot name="header">
            <h2 class="text-lg font-semibold">Daftar Users</h2>
        </x-slot>

        <x-filament::card>
            <div class="overflow-auto">
                <table class="w-full table-auto text-left">
                    <thead>
                        <tr class="text-sm text-gray-600">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->getUsers() as $index => $user)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">{{ $user->role ?? '-' }}</td>
                                <td class="px-4 py-2">{{ optional($user->created_at)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::card>
    </x-filament::page>
</div>
