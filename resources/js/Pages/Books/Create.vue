<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    kode_buku: '',
    judul: '',
    pengarang: '',
    penerbit: '',
    tahun: '',
    stok: 0,
});

function submit() {
    form.post(route('books.store'));
}
</script>

<template>
    <AuthenticatedLayout>
        <h1>Tambah Buku</h1>

        <form @submit.prevent="submit">
            <label>Kode Buku</label>
            <input v-model="form.kode_buku" type="text">
            <span v-if="form.errors.kode_buku">{{ form.errors.kode_buku }}</span>

            <label>Judul</label>
            <input v-model="form.judul" type="text">
            <span v-if="form.errors.judul">{{ form.errors.judul }}</span>

            <label>Pengarang</label>
            <input v-model="form.pengarang" type="text">
            <span v-if="form.errors.pengarang">{{ form.errors.pengarang }}</span>

            
            <label>Penerbit</label>
            <input v-model="form.penerbit" type="text">
            <span v-if="form.errors.penerbit">{{ form.errors.penerbit }}</span>

            <label>Tahun</label>
            <input v-model="form.tahun" type="text">
            <span v-if="form.errors.tahun">{{ form.errors.tahun }}</span>

            <input v-model="form.stok" type="number" min="0">
            <span v-if="form.errors.stok">{{ form.errors.stok }}</span>


            <button type="submit">Simpan</button>
        </form>
    </AuthenticatedLayout>
</template>