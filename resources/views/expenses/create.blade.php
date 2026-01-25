<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Expenses
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">

                @if ($errors->any())
                    <ul style="color:red;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="/expenses">
                    @csrf

                    <div>
                        <label>Date</label><br>
                        <input type ="date" name="expense_date" value="{{ old('expense_date') }}">
                    </div>

                    <div>
                        <label>Category</label><br>
                        <input type="text" name="category" value="{{ old('category') }}">
                    </div>

                    <div>
                        <label>Amount</label><br>
                        <input type="number" name="amount" value="{{ old('amount') }}">
                    </div>

                    <div>
                        <label>Note</label><br>
                        <textarea name="note">{{ old('note') }}</textarea>
                    </div>

                    <br>

                    <button type="submit">Save</button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>