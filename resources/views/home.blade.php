<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Home
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">
                <h3> Welcome, {{ auth()->user()->name }}</h3>

                <p>This is your landing Page.</p>
                <br><br>
                <a href="/expenses">Go to Expenses</a>
                <br><br>
                <a href="/reports/monthly">Laporan Bulanan</a>                
            </div>
        </div>
    </div>
</x-app-layout>