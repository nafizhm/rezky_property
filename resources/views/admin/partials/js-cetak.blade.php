<script>
    $(document).on('click', '.btn-cetak-item', function() {
        var id = $(this).data('id');
        var name = $(this).data('nama');
        var documents = $(this).data('documents');

        if (!documents || documents.length === 0) {
            toastr.error('Tidak ada dokumen tersedia untuk dicetak.', 'GAGAL!', {
                progressBar: true,
                timeOut: 3500,
                positionClass: "toast-bottom-right"
            });
            return;
        }

        $('#cetakCustomerId').val(id);
        $('#cetakCustomerName').text(name);

        var listHtml = '';
        documents.forEach(function(doc) {
            var checked = doc.checked ? 'checked' : '';
            listHtml += `
                <label class="list-group-item d-flex align-items-center py-2">
                    <input type="checkbox" class="mr-2 dokumen-checkbox" value="${doc.route}"
                           data-doc-name="${doc.name}" ${checked}>
                    ${doc.name}
                </label>
            `;
        });
        $('#cetakDokumenList').html(listHtml);

        $('#modalCetak').modal('show');
    });

    $('#btnCetakDokumen').on('click', function() {
        var btn = $(this);
        var id = $('#cetakCustomerId').val();
        var checked = $('.dokumen-checkbox:checked');

        if (checked.length === 0) {
            toastr.error('Pilih minimal satu dokumen untuk dicetak.', 'GAGAL!', {
                progressBar: true,
                timeOut: 3500,
                positionClass: "toast-bottom-right"
            });
            return;
        }

        btn.prop('disabled', true);
        btn.find('.spinner-border').removeClass('d-none');
        btn.find('.btn-text').text('Mencetak...');

        checked.each(function() {
            var route = $(this).val().replace(':id', id);
            window.open(route, '_blank');
        });

        setTimeout(function() {
            btn.prop('disabled', false);
            btn.find('.spinner-border').addClass('d-none');
            btn.find('.btn-text').text('Cetak');
            $('#modalCetak').modal('hide');
        }, 500);
    });

    $('#modalCetak').on('hidden.bs.modal', function() {
        $('#cetakCustomerId').val('');
        $('#cetakCustomerName').text('-');
        $('#cetakDokumenList').html('');
    });
</script>
