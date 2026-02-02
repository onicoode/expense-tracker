<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ubah Pengeluaran
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

                <form method="POST" action="/expenses/{{ $expense->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <label>Date</label><br>
                        <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date) }}">
                    </div>

                    <div>
                        <label>Category</label><br>
                        <input type="text" name="category"
                            value="{{ old('category', $expense->category) }}">
                    </div>

                    <div>
                        <label>Amount</label><br>
                        <input type="number" name="amount"
                            value="{{ old('amount', $expense->amount) }}">
                    </div>

                    <div>
                        <label>Note</label><br>
                        <textarea name="note">{{ old('note', $expense->note) }}</textarea>
                    </div>

                    <div>
                        <label>Bukti (Opsional)</label><br>
                        
                        @if($expense->receipt)
                            <div style="margin-bottom: 10px;">
                                <strong>Bukti saat ini:</strong><br>
                                @if(Str::endsWith($expense->receipt, '.pdf'))
                                    <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                        📄 Lihat PDF
                                    </a>
                                @else
                                    <img 
                                        src="{{ asset('storage/' . $expense->receipt) }}" 
                                        alt="Receipt" 
                                        style="max-width: 200px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;"
                                    >
                                @endif
                            </div>
                        @endif
                        
                        <input 
                            type="file" 
                            name="receipt" 
                            accept="image/*,application/pdf"
                            capture="environment"
                            class="mt-1 block"
                        >
                        <br>
                        <small style="color: #666;">
                            📸 Upload bukti baru untuk mengganti yang lama
                        </small>
                    </div>                    

                    <br>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Ubah</button>

                    <a href="/expenses" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Batal</a>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>