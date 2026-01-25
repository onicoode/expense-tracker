<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Expenses
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">

                @if ($expenses->isEmpty())
                    <a href="/expenses/create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">+ Add Expense</a>
                    <br><br>                
                    <p>No expense yet.</p>
                @else
                    <a href="/expenses/create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">+ Add Expense</a>
                    <br><br>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $expense->expense_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $expense->category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($expense->amount) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $expense->note }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <a href="/expenses/{{ $expense->id }}/edit">Edit</a>

                                        <form action="/expenses/{{ $expense->id }}" method="POST"
                                            style="display:inline"
                                            onsubmit="return confirm('Delete this expense?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
