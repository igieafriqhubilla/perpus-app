
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    book: Object,
});

const form = useForm({
    kode_buku: props.book.kode_buku,
    judul: props.book.judul,
    pengarang: props.book.pengarang,
    penerbit: props.book.penerbit ?? '',
    tahun: props.book.tahun ?? '',
    stok: props.book.stok,
});

function submit() {
    form.put(route('books.update', props.book.id));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6">
            <h1 class="mb-4 text-xl font-bold">Edit Buku</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label>Kode Buku</label>
                    <input v-model="form.kode_buku" type="text"
                        class="block w-full rounded border p-2">
                    <div v-if="form.errors.kode_buku" class="text-red-600">
                        {{ form.errors.kode_buku }}
                    </div>
                </div>

                <div>
                    <label>Judul</label>
                    <input v-model="form.judul" type="text"
                        class="block w-full rounded border p-2">
                    <div v-if="form.errors.judul" class="text-red-600">
                        {{ form.errors.judul }}
                    </div>
                </div>

                <div>
                    <label>Pengarang</label>
                    <input v-model="form.pengarang" type="text"
                        class="block w-full rounded border p-2">
                    <div v-if="form.errors.pengarang" class="text-red-600">
                        {{ form.errors.pengarang }}
                    </div>
                </div>

                <div>
                    <label>Penerbit</label>
                    <input v-model="form.penerbit" type="text"
                        class="block w-full rounded border p-2">
                </div>

                <div>
                    <label>Tahun</label>
                    <input v-model="form.tahun" type="text"
                        class="block w-full rounded border p-2">
                    <div v-if="form.errors.tahun" class="text-red-600">
                        {{ form.errors.tahun }}
                    </div>
                </div>

                <div>
                    <label>Stok</label>
                    <input v-model="form.stok" type="number"
                        class="block w-full rounded border p-2">
                    <div v-if="form.errors.stok" class="text-red-600">
                        {{ form.errors.stok }}
                    </div>
                </div>

                <button type="submit"
                    class="rounded bg-blue-600 px-4 py-2 text-white"
                    :disabled="form.processing">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
