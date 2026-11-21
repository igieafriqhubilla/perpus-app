
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    nama: '',
    nis_nim: '',
    no_hp: '',
    foto: null,
});

// Alamat sementara gambar untuk pratinjau
const pratinjau = ref(null);

// Memilih foto anggota
function pilihFoto(event) {
    const berkas = event.target.files[0];

    form.foto = berkas ?? null;

    pratinjau.value = berkas
        ? URL.createObjectURL(berkas)
        : null;
}

// Mengirim data anggota
function submit() {
    form.post(route('members.store'));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6">
            <h1 class="text-xl font-bold mb-4">
                Tambah Anggota
            </h1>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Nama -->
                <div>
                    <label class="block mb-1">Nama</label>
                    <input
                        v-model="form.nama"
                        type="text"
                        class="w-full border rounded p-2"
                    >
                    <div v-if="form.errors.nama" class="text-red-600">
                        {{ form.errors.nama }}
                    </div>
                </div>

                <!-- NIS/NIM -->
                <div>
                    <label class="block mb-1">NIS/NIM</label>
                    <input
                        v-model="form.nis_nim"
                        type="text"
                        class="w-full border rounded p-2"
                    >
                    <div v-if="form.errors.nis_nim" class="text-red-600">
                        {{ form.errors.nis_nim }}
                    </div>
                </div>

                <!-- Nomor HP -->
                <div>
                    <label class="block mb-1">No. HP</label>
                    <input
                        v-model="form.no_hp"
                        type="text"
                        class="w-full border rounded p-2"
                    >
                    <div v-if="form.errors.no_hp" class="text-red-600">
                        {{ form.errors.no_hp }}
                    </div>
                </div>

                <!-- Foto -->
                <div>
                    <label class="block mb-1">
                        Foto (JPG/PNG, maksimal 2 MB)
                    </label>

                    <input
                        type="file"
                        accept="image/png, image/jpeg"
                        @change="pilihFoto"
                    >

                    <div v-if="form.errors.foto" class="text-red-600">
                        {{ form.errors.foto }}
                    </div>

                    <img
                        v-if="pratinjau"
                        :src="pratinjau"
                        alt="Pratinjau foto"
                        class="mt-2 h-40 w-40 rounded object-cover"
                    >
                </div>

                <!-- Tombol -->
                <div class="flex gap-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded disabled:opacity-50"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>

                    <a
                        href="/members"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded"
                    >
                        Kembali
                    </a>
                </div>

            </form>
        </div>
    </AuthenticatedLayout>
</template>
