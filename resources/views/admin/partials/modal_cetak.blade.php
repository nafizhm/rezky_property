<div class="modal fade" id="modalCetak" tabindex="-1" role="dialog" data-focus="false"
     aria-labelledby="modalCetakLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-indigo">
                <h5 class="modal-title text-white font-weight-bold" id="modalCetakLabel">
                    Cetak Dokumen
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-3 text-muted">Pilih dokumen yang akan dicetak untuk:</p>
                <h6 class="font-weight-bold mb-3" id="cetakCustomerName">-</h6>
                <input type="hidden" id="cetakCustomerId">

                <div id="cetakDokumenList" class="list-group"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnCetakDokumen">
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    <span class="btn-text">Cetak</span>
                </button>
            </div>
        </div>
    </div>
</div>
