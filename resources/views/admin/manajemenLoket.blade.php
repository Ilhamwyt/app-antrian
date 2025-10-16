@extends('layout.app')

@section('title', 'Manajemen Loket')
@section('page_title', 'Manajemen Loket')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Notifikasi -->
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md flex justify-between items-center" id="alert">
        <div>
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        <button type="button" class="text-green-700" onclick="document.getElementById('alert').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Loket</h2>
        <button id="btnTambahLoket" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>Tambah Loket
        </button>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Loket
                    </th>
                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($counters as $counter)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $counter->id }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $counter->nama_loket }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-wrap gap-2">
                                <button onclick="editLoket('{{ $counter->id }}')" class="text-blue-600 hover:text-blue-900 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <form action="{{ route('loket.destroy', $counter->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus loket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 sm:px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada data loket
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Loket -->
<div id="modalTambahLoket" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto">
        <div class="flex justify-between items-center p-5 border-b">
            <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Loket</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" id="btnCloseModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="formLoket" action="{{ route('loket.store') }}" method="POST">
            @csrf
            <div id="methodField"></div>
            <div class="p-5">
                <div class="mb-4">
                    <label for="nama_loket" class="block text-sm font-medium text-gray-700 mb-1">Nama Loket</label>
                    <input type="text" name="nama_loket" id="nama_loket" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>
            <div class="px-5 py-4 border-t flex flex-wrap justify-end gap-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200" id="btnBatalModal">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan modal tambah loket
    document.getElementById('btnTambahLoket').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Tambah Loket';
        document.getElementById('formLoket').action = "{{ route('loket.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('nama_loket').value = '';
        document.getElementById('modalTambahLoket').classList.remove('hidden');
    });

    // Fungsi untuk menutup modal
    document.getElementById('btnCloseModal').addEventListener('click', function() {
        document.getElementById('modalTambahLoket').classList.add('hidden');
    });

    document.getElementById('btnBatalModal').addEventListener('click', function() {
        document.getElementById('modalTambahLoket').classList.add('hidden');
    });

    // Fungsi untuk edit loket
    function editLoket(id) {
        fetch(`{{ url('loket') }}/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Edit Loket';
                document.getElementById('formLoket').action = `{{ url('loket') }}/${id}`;
                document.getElementById('methodField').innerHTML = '@method("PUT")';
                document.getElementById('nama_loket').value = data.nama_loket;
                document.getElementById('modalTambahLoket').classList.remove('hidden');
            });
    }
</script>
@endsection