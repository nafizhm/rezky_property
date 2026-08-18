<div class="modal-header bg-indigo">
    <h5 class="modal-title">QR Code</h5>
   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body text-center">
    <div class="my-3">
        {!! $qrCode !!}
    </div>
    <p class="small">Scan QR untuk membuka: <a href="{{ $url }}" target="_blank">{{ $url }}</a></p>
</div>
