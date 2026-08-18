<script>
    var audio = new Audio('{{ asset('audio/notification.ogg') }}');

    $(function() {
        @if (Session::has('success'))
            audio.play();
            toastr.success("{{ Session::get('success') }}", "Berhasil!", {
                closeButton: true,
                progressBar: true,
                timeOut: 3000,
                positionClass: 'toast-bottom-right',
            });
        @endif

        @if ($errors->any())
            audio.play();
            toastr.error("{{ $errors->first() }}", "Kesalahan!", {
                closeButton: true,
                progressBar: true,
                timeOut: 3000,
                positionClass: 'toast-bottom-right'
            });
        @endif
    });
</script>
