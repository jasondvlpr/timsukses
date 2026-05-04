<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Kirim Laporan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-8 text-slate-900">
                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" 
                        x-data="{ 
                            fileName: '', 
                            fileSize: '', 
                            isUploading: false, 
                            progress: 0,
                            handleFile(e) {
                                const file = e.target.files[0];
                                if (file) {
                                    if (file.size > 2 * 1024 * 1024) {
                                        alert('Ukuran file maksimal 2MB!');
                                        e.target.value = '';
                                        this.fileName = '';
                                        return;
                                    }
                                    this.fileName = file.name;
                                    this.fileSize = (file.size / 1024).toFixed(2) + ' KB';
                                }
                            }
                        }"
                        @submit="isUploading = true; let interval = setInterval(() => { if(progress < 95) progress += 5; else clearInterval(interval); }, 100);"
                    >
                        @csrf
                        
                        <div class="mb-6">
                            <label for="website_id" class="block text-sm font-bold text-slate-700 mb-2">Pilih Website</label>
                            <select name="website_id" id="website_id" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition">
                                <option value="">-- Umum (Tidak spesifik website) --</option>
                                @foreach($websites as $website)
                                    <option value="{{ $website->id }}">{{ $website->name }} ({{ $website->url }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('website_id')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="subject" class="block text-sm font-bold text-slate-700 mb-2">Subjek Laporan <span class="text-red-500">*</span></label>
                            <select name="subject" id="subject" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                                <option value="">-- Pilih Subjek Laporan --</option>
                                <option value="Pengajuan Web Baru">Pengajuan Web Baru</option>
                                <option value="Transaksi Claim Gaji">Transaksi Claim Gaji</option>
                                <option value="Nawala">Nawala</option>
                                <option value="Update Web">Update Web</option>
                                <option value="Keluhan Lainnya">Keluhan Lainnya</option>
                            </select>
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="priority" class="block text-sm font-bold text-slate-700 mb-2">Prioritas <span class="text-red-500">*</span></label>
                            <div class="flex gap-6">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="priority" value="low" class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-600 group-hover:text-slate-900 transition">Low</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="priority" value="medium" class="text-yellow-600 focus:ring-yellow-500" checked>
                                    <span class="ml-2 text-sm text-slate-600 group-hover:text-slate-900 transition">Medium</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="priority" value="high" class="text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-slate-600 group-hover:text-slate-900 transition">High</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Detail Laporan <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="5" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" placeholder="Jelaskan masalah Anda secara lengkap di sini..." required></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-8">
                            <label for="attachment" class="block text-sm font-bold text-slate-700 mb-2">Lampiran Gambar <span class="text-slate-400 font-normal text-xs">(Maksimal 2MB)</span></label>
                            
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-lg hover:border-slate-300 transition relative"
                                :class="fileName ? 'bg-blue-50 border-blue-300' : 'bg-white'">
                                
                                <div class="space-y-1 text-center">
                                    <template x-if="!fileName">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </template>
                                    
                                    <template x-if="fileName">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <p class="text-sm font-bold text-slate-900 mt-2" x-text="fileName"></p>
                                            <p class="text-xs text-slate-500" x-text="fileSize"></p>
                                        </div>
                                    </template>

                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label for="attachment" class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-500 focus-within:outline-none px-1">
                                            <span x-text="fileName ? 'Ganti File' : 'Upload a file'"></span>
                                            <input id="attachment" name="attachment" type="file" class="sr-only" accept="image/*" @change="handleFile">
                                        </label>
                                        <p class="pl-1" x-show="!fileName">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-slate-500" x-show="!fileName">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-4" x-show="isUploading">
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs font-bold text-blue-700">Mengirim data...</span>
                                    <span class="text-xs font-bold text-blue-700" x-text="progress + '%'"></span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                                </div>
                                <p class="mt-2 text-[10px] text-slate-400 italic">Mohon tunggu, jangan tutup halaman ini.</p>
                            </div>

                            <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('tickets.index') }}" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 font-semibold transition" x-show="!isUploading">Batal</a>
                            <button type="submit" class="btn-primary px-8 py-2 rounded-lg font-semibold shadow-lg flex items-center gap-2" :disabled="isUploading">
                                <svg x-show="isUploading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="isUploading ? 'Sedang Mengirim...' : 'Kirim Laporan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
