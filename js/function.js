// Pengguna //
if (document.querySelector('body').classList.contains('page-pengguna')) {
    window.editData = function (id, nama, username, level) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_user').value = username;
        document.getElementById('edit_level').value = level;
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    window.hapusData = function (id, nama) {
        document.getElementById('hapus_id').value = id;
        document.getElementById('hapus_nama').textContent = nama;
        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }
}

// Varietas //
if (document.querySelector('body').classList.contains('page-varietas')) {
    window.viewData = function(id, kode, nama, subkriteriaArray) {
        document.getElementById('view_id').value = id; 
        document.getElementById('view_kode_varietas').value = kode;
        document.getElementById('view_nama_varietas').value = nama;

        const inputs = document.querySelectorAll('#modalView input[id^="view_subkriteria_"]');
        inputs.forEach(input => input.value = '');

        if (subkriteriaArray && subkriteriaArray.length > 0) {
            subkriteriaArray.forEach((subId, index) => {
                const input = document.getElementById('view_subkriteria_' + index);

                // ambil nama subkriteria dari select edit (sudah ada semua option)
                const select = document.querySelectorAll('#modalEdit select[name="subkriteria[]"]')[index];
                if (select) {
                    const option = select.querySelector(`option[value="${subId}"]`);
                    if (input && option) {
                        input.value = option.textContent;
                    }
                }
            });
        }

        new bootstrap.Modal(document.getElementById('modalView')).show();
    }
    window.editData = function(id, kode, nama, subkriteriaArray) {
        document.getElementById('edit_id').value = id; 
        document.getElementById('edit_kode_varietas').value = kode;
        document.getElementById('edit_nama_varietas').value = nama;
        let selects = document.querySelectorAll('.edit-sub')
        selects.forEach(s => s.value = "")
        subkriteriaArray.forEach((s, i) => { if (selects[i]) selects[i].value = s })

        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    window.hapusData = function (id, kode) {
        document.getElementById('hapus_id').value = id; 
        document.getElementById('hapus_kode_varietas').textContent = kode;

        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }
}

// Kriteria //
if (document.querySelector('body').classList.contains('page-kriteria')) {
    window.editData = function (id, kode, nama, jenis) {
        document.getElementById('edit_id').value = id; 
        document.getElementById('edit_kode_kriteria').value = kode;
        document.getElementById('edit_nama_kriteria').value = nama;
        document.getElementById('edit_jenis_kriteria').value = jenis;

        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    window.hapusData = function (id, kode) {
        document.getElementById('hapus_id').value = id; 
        document.getElementById('hapus_kode_kriteria').textContent = kode;

        new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }
}
        
//Subkriteria//
if (document.querySelector('body').classList.contains('page-subkriteria')) {
    window.editData = function(id, nama, nilai) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nilai').value = nilai;
        
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    window.hapusData = function(id, nama) {
        document.getElementById('hapus_id').value = id;
        document.getElementById('hapus_nama').textContent = nama;
                
        new bootstrap.Modal(document.getElementById('modalHapus')).show();

    }
}
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#dataTable tbody tr');
            
    tableRows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});