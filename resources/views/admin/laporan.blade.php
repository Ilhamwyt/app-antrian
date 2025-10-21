@extends('layout.app')

@section('title', 'Laporan Pengunjung')
@section('page_title', 'Laporan Pengunjung')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Flash Message -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- Filter dan Export -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" 
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" 
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.laporan.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>
            <button type="button" onclick="openAddModal()" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Data
            </button>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telpon</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permasalahan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solusi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindak Lanjut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($visitors ?? [] as $visitor)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $visitor->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $visitor->queue->queue_number ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $visitor->nim }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $visitor->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $visitor->phone }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $visitor->complaint }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $visitor->solution }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($visitor->status == 'selesai')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-lg text-xs font-medium">Selesai</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium">Perlu Tindak Lanjut</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $visitor->forward_to ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $visitor->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button type="button" onclick="openEditModal({{ $visitor->id }}, '{{ $visitor->nim }}', '{{ $visitor->name }}', '{{ $visitor->complaint }}', '{{ $visitor->solution }}', '{{ $visitor->status }}', '{{ $visitor->forward_to }}')" 
                                class="text-indigo-600 hover:text-indigo-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <form action="{{ route('admin.laporan.destroy', $visitor->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data pengunjung</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        @if(isset($visitors) && $visitors->hasPages())
            {{ $visitors->appends(request()->query())->links() }}
        @endif
    </div>
</div>

<!-- Modal Edit Data -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Data Pengunjung</h3>
            <form id="editForm" method="POST" class="mt-4 text-left">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                    <input type="text" id="edit_nim" name="nim" required 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" id="edit_name" name="name" required 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="edit_complaint" class="block text-sm font-medium text-gray-700 mb-1">Permasalahan</label>
                    <textarea id="edit_complaint" name="complaint" rows="3" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                </div>
                <div class="mb-4">
                    <label for="edit_solution" class="block text-sm font-medium text-gray-700 mb-1">Solusi</label>
                    <textarea id="edit_solution" name="solution" rows="3" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex space-x-4">
                        <div class="flex items-center">
                            <input type="radio" id="edit_status_selesai" name="status" value="selesai" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" onchange="toggleEditForwardTo(false)">
                            <label for="edit_status_selesai" class="ml-2 block text-sm text-gray-700">Selesai</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="edit_status_tindak_lanjut" name="status" value="perlu_tindak_lanjut" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" onchange="toggleEditForwardTo(true)">
                            <label for="edit_status_tindak_lanjut" class="ml-2 block text-sm text-gray-700">Perlu Tindak Lanjut</label>
                        </div>
                    </div>
                </div>
                <div id="edit_forward_to_container" class="mb-4">
                    <label for="edit_forward_to" class="block text-sm font-medium text-gray-700 mb-1">Kepada Siapa</label>
                    <input type="text" id="edit_forward_to" name="forward_to" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="closeEditModal()" 
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </button>
                    <button type="submit" 
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan/menyembunyikan field "Kepada Siapa" pada form tambah
    function toggleAddForwardTo(show) {
        const forwardToContainer = document.getElementById('add_forward_to_container');
        if (show) {
            forwardToContainer.classList.remove('hidden');
        } else {
            forwardToContainer.classList.add('hidden');
            document.getElementById('add_forward_to').value = '';
        }
    }
    
    // Fungsi untuk menampilkan/menyembunyikan field "Kepada Siapa" pada form edit
    function toggleEditForwardTo(show) {
        const forwardToContainer = document.getElementById('edit_forward_to_container');
        if (show) {
            forwardToContainer.classList.remove('hidden');
        } else {
            forwardToContainer.classList.add('hidden');
            document.getElementById('edit_forward_to').value = '';
        }
    }
    
    // Modal Tambah Data
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }
    
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }
    
    // Modal Edit Data
    function openEditModal(id, nim, name, complaint, solution, status, forward_to) {
        document.getElementById('editForm').action = `/laporan/${id}`;
        document.getElementById('edit_nim').value = nim;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_complaint').value = complaint;
        document.getElementById('edit_solution').value = solution;
        
        // Set status radio button
        if (status === 'selesai') {
            document.getElementById('edit_status_selesai').checked = true;
            document.getElementById('edit_status_tindak_lanjut').checked = false;
            toggleEditForwardTo(false);
        } else {
            document.getElementById('edit_status_selesai').checked = false;
            document.getElementById('edit_status_tindak_lanjut').checked = true;
            toggleEditForwardTo(true);
        }
        
        document.getElementById('edit_forward_to').value = forward_to || '';
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    // Close modals when clicking outside
    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        
        if (event.target == addModal) {
            closeAddModal();
        }
        
        if (event.target == editModal) {
            closeEditModal();
        }
    }
    
    // Tambahkan event listener saat dokumen dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Event listener untuk radio button status pada form tambah
        document.getElementById('add_status_selesai').addEventListener('change', function() {
            toggleAddForwardTo(false);
        });
        
        document.getElementById('add_status_tindak_lanjut').addEventListener('change', function() {
            toggleAddForwardTo(true);
        });
        
        // Event listener untuk radio button status pada form edit
        document.getElementById('edit_status_selesai').addEventListener('change', function() {
            toggleEditForwardTo(false);
        });
        
        document.getElementById('edit_status_tindak_lanjut').addEventListener('change', function() {
            toggleEditForwardTo(true);
        });
        
        // Set default state
        toggleAddForwardTo(false);
        toggleEditForwardTo(false);
    });
</script>
@endsection