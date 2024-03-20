@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Tests</h1>
            <a href="{{ route('tests.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Test</a>
        </div>

        <hr>
        <br>
        {{ $tests->links() }}
        <br>
        <hr>

        <table class="bg-white w-full border-collapse">
            <thead>
                <tr class="bg-gray-300">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Updated</th>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Referring Doctor</th>
                    <th class="border px-4 py-2">Referring Clinic</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tests as $test)
                    <tr>
                        <td class="border px-4 py-2">{{ $test['id'] }}</td>
                        <td class="border px-4 py-2">{{ $test['updated_at']->format('Y-m-d') }}</td>
                        <td class="border px-4 py-2">{{ $test['name'] }}</td>
                        <td class="border px-4 py-2">{{ $test['description'] }}</td>
                        <td class="border px-4 py-2">
                            @if ($test['health_professional'])
                                <a href="{{ route('doctors.show', $test['health_professional']['id']) }}">
                                    {{ $test['health_professional']['name'] }}
                                </a>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            @if ($test['health_professional'])
                                {{ $test['health_professional']['clinic_name'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
