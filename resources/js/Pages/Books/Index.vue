
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    books: Object,
    search: String,
});

const keyword = ref(props.search);

function cari() {
    router.get('/books', { search: keyword.value });
}

function hapus(id) {
    if (confirm('Yakin ingin menghapus buku ini?')) {
        router.delete(route('books.destroy', id));
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <h1>Data Buku</h1>

        <div v-if="$page.props.flash.success">
            {{ $page.props.flash.success }}
        </div>

        <Link href="/books/create">+ Tambah Buku</Link>

        <!-- Kotak pencarian -->
        <input
            v-model="keyword"
            @keyup.enter="cari"
            type="text"
            placeholder="Cari judul atau pengarang..."
        >

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="book in books.data" :key="book.id">
                    <td>{{ book.kode_buku }}</td>
                    <td>{{ book.judul }}</td>
                    <td>{{ book.pengarang }}</td>
                    <td>{{ book.tahun }}</td>
                    <td>{{ book.stok }}</td>
                    <td>
                        <Link :href="`/books/${book.id}/edit`">Edit</Link>
                        <button @click="hapus(book.id)">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </AuthenticatedLayout>
</template>
