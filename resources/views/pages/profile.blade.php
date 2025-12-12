@extends('layouts.app')

@section('title', 'Profil Saya - Toko Online')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <section class="mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-green-100">


                <!-- Header Banner -->
                <div class="h-40 bg-gradient-to-r from-green-400 to-green-600"></div>


                <!-- Avatar -->
                <div class="relative -mt-16 flex flex-col items-center">
                    <!-- Profile Preview -->
                    @if ($user->profile_image)
                        <img id="profile-preview" src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image"
                            class="w-40 h-40 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div id="profile-preview"
                            class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-200">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                </path>
                            </svg>
                        </div>
                    @endif

                    <!-- Edit Button -->
                    <button id="edit-photo-btn"
                        class="mt-4 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-lg transition-all duration-200 hover:scale-105 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828H9v-2.828zM5 19h14">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Ubah</span>
                    </button>

                    <!-- Hidden Input File -->
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
                </div>



                <!-- User Info -->
                <div class="flex flex-col text-center px-6 py-6 justify-center">
                    <h1 class="ml-5 text-3xl font-bold text-gray-800">{{ $user->name }}
                        <button id="openEditNameModal" class="text-gray-500 hover:text-gray-700 transition p-1 rounded-full"
                            title="Edit nama">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-.878.518L7 15l1.654-3.95a2 2 0 01.346-.55z">
                                </path>
                            </svg>
                        </button>
                    </h1>
                    <p class="text-green-600 font-medium">Member Platinum</p>
                    <p class="text-gray-500 mt-1">Bergabung sejak 2022</p>
                </div>
                <!-- Modal -->
                <div id="editNameModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
                    <div class="bg-white w-80 p-6 rounded-xl shadow-lg">

                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Nama</h2>

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $user->name }}"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-green-400 focus:border-green-400">

                            <div class="flex justify-end mt-4 gap-2">
                                <button type="button" id="closeEditNameModal"
                                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                                    Batal
                                </button>

                                <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                                    Simpan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
                <script>
                    const openBtn = document.getElementById('openEditNameModal');
                    const closeBtn = document.getElementById('closeEditNameModal');
                    const modal = document.getElementById('editNameModal');

                    openBtn.addEventListener('click', () => {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });

                    closeBtn.addEventListener('click', () => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });

                    // Click outside to close
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }
                    });
                </script>


                <!-- Stats Section -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 px-10 pb-10">
                    <div class="bg-green-50 p-4 rounded-2xl text-center border border-green-100">
                        <p class="text-2xl font-bold text-green-700">152</p>
                        <p class="text-gray-600 text-sm">Pesanan</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-2xl text-center border border-green-100">
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                        @endphp
                        <p class="text-2xl font-bold text-green-700">{{ $cartCount }}</p>
                        <p class="text-gray-600 text-sm">Keranjang</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-2xl text-center border border-green-100">
                        <p class="text-2xl font-bold text-green-700">8</p>
                        <p class="text-gray-600 text-sm">Diterima</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-2xl text-center border border-green-100">
                        <p class="text-2xl font-bold text-green-700">27</p>
                        <p class="text-gray-600 text-sm">Ulasan</p>
                    </div>
                </div>


                <!-- Detail Cards -->
                <div class="px-8 pb-12 space-y-6">
                    <div class="p-5 bg-gray-100 rounded-2xl border border-green-100">
                        <h3 class="font-semibold text-gray-800 mb-1">Email</h3>

                        @if ($user->email)
                            <p class="text-gray-700">{{ $user->email }}</p>
                        @else
                            <p class="text-gray-400 italic">Belum ditambahkan</p>
                        @endif
                    </div>
                    <div class="p-5 bg-gray-100 rounded-2xl border border-green-100 relative">
                        <div class="flex justify-between mb-1">
                            <h3 class="font-semibold text-gray-800">Nomor Telepon</h3>

                            <button onclick="openModal('phoneModal')" class="p-1 text-gray-500 hover:text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.828H9v-2.828z">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        {{-- Isi Data --}}
                        @if ($user->phone_number)
                            <p class="text-gray-700">{{ $user->phone_number }}</p>
                        @else
                            <p class="text-gray-400 italic">Belum ditambahkan</p>
                        @endif

                        {{-- Alert Sukses --}}
                        @if (session('success_phone'))
                            <div class="mt-3">
                                <div
                                    class="bg-green-500 text-white px-4 py-3 rounded-lg shadow flex justify-between items-center">
                                    <span>{{ session('success_phone') }}</span>

                                    <button onclick="this.parentElement.remove()" class="hover:text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>


                    <div class="p-5 bg-gray-100 rounded-2xl border border-green-100">
                        <div class="bg-gray-100 rounded-2xl border border-green-100">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="font-semibold text-gray-800">Alamat Utama</h3>

                                <button onclick="openModal('addressModal')"
                                    class="p-1 text-gray-500 hover:text-gray-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0
                               112.828 2.828L11.828 13.828H9v-2.828z">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            @if ($user->address)
                                <p class="text-gray-700 leading-relaxed">
                                    {{ $user->address }}
                                </p>
                            @else
                                <p class="text-gray-400 italic">Belum ditambahkan</p>
                            @endif
                        </div>

                        {{-- Alert Success --}}
                        @if (session('success'))
                            <div class="mt-4">
                                <div
                                    class="bg-green-500 text-white px-4 py-3 rounded-lg shadow flex justify-between items-center">
                                    <span>{{ session('success') }}</span>

                                    <button onclick="this.parentElement.remove()" class="hover:text-gray-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                    </div>


                    <div id="phoneModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
                        <div class="bg-white w-80 p-6 rounded-xl shadow-lg">
                            <h2 class="text-xl font-semibold mb-4">Edit Nomor Telepon</h2>

                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="text" name="phone_number" value="{{ $user->phone_number }}"
                                    class="w-full px-3 py-2 border rounded-lg">

                                <div class="flex justify-end mt-4 gap-2">
                                    <button type="button" onclick="closeModal('phoneModal')"
                                        class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>

                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="addressModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
                        <div class="bg-white w-96 p-6 rounded-xl shadow-lg">
                            <h2 class="text-xl font-semibold mb-4">Edit Alamat</h2>

                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <textarea name="address" rows="4" class="w-full px-3 py-2 border rounded-lg">{{ $user->address }}</textarea>

                                <div class="flex justify-end mt-4 gap-2">
                                    <button type="button" onclick="closeModal('addressModal')"
                                        class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>

                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <script>
                        function openModal(id) {
                            const modal = document.getElementById(id);
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        }

                        function closeModal(id) {
                            const modal = document.getElementById(id);
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }
                    </script>

                </div>

            </div>
        </section>
    </div>

    <script>
        document.getElementById('edit-photo-btn').addEventListener('click', function() {
            document.getElementById('profile_image').click();
        });

        // Preview setelah pilih gambar
        document.getElementById('profile_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const preview = document.getElementById('profile-preview');

            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview.tagName === "IMG") {
                    preview.src = e.target.result;
                } else {
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="w-40 h-40 rounded-full object-cover border-2 border-gray-200">`;
                }
            };

            reader.readAsDataURL(file);
        });
    </script>

@endsection
