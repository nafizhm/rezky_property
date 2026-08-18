<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @php
      $logo = \App\Models\PengaturanMedia::where('jenis_data', 'Logo Login')->first();
      $konfigurasi = \App\Models\PengaturanProfil::first();
      $icon = \App\Models\PengaturanMedia::where('jenis_data', 'fav icon')->where('stt_aktif', 1)->first();
      $faviconPath = $icon && $icon->nama_file ? asset('config_media/' . $icon->nama_file) : asset('default/favicon.ico');
      $bgLogin = \App\Models\PengaturanMedia::where('jenis_data', 'Background login')->first();
      $bgImage = $bgLogin && $bgLogin->nama_file ? asset('config_media/' . $bgLogin->nama_file) : asset('bg-login-perumahan.png');
  @endphp
  <link rel="icon" href="{{ $faviconPath }}" />
  <link rel="shortcut icon" href="{{ $faviconPath }}" />
  <title>{{ $konfigurasi->nama_perusahaan ?? 'Template Aplikasi' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('templates/plugins/toastr/toastr.min.css') }}">
  <style>
    :root{
      --green:#0d3329;
      --green-2:#174f3d;
      --gold:#c99a45;
      --gold-soft:#f5ead6;
      --text:#102e27;
      --muted:#6c7a73;
      --line:#e3dfd6;
      --white:rgba(255,255,255,.92);
    }
    *{box-sizing:border-box}
    body{
      margin:0; min-height:100vh; font-family:Inter,system-ui,-apple-system,Segoe UI,sans-serif;
      color:var(--text);
      background:url('{{ $bgImage }}') center center / cover no-repeat fixed;
      overflow-x:hidden;
    }
    body::before{
      content:""; position:fixed; inset:0; pointer-events:none;
      background:
        linear-gradient(90deg, rgba(5,20,17,.48) 0%, rgba(5,20,17,.10) 38%, rgba(5,20,17,.16) 100%),
        radial-gradient(circle at 50% 48%, rgba(255,255,255,.06) 0 15%, rgba(0,0,0,.18) 65%);
    }
    .page{
      position:relative; min-height:100vh; display:grid; place-items:center; padding:32px 18px;
    }
    .login-card{
      width:min(430px, 100%);
      padding:38px 38px 30px;
      border-radius:26px;
      background:linear-gradient(180deg, rgba(255,255,255,.95), rgba(255,255,255,.88));
      border:1px solid rgba(255,255,255,.72);
      box-shadow:0 30px 90px rgba(2,17,13,.38);
      backdrop-filter:blur(18px);
      -webkit-backdrop-filter:blur(18px);
    }
    .logo{
      display:flex; justify-content:center; margin-bottom:18px;
    }
    .logo svg{width:92px;height:62px;filter:drop-shadow(0 6px 16px rgba(201,154,69,.16))}
    .logo img{max-height:62px;width:auto;filter:drop-shadow(0 6px 16px rgba(201,154,69,.16))}
    .brand-small{text-align:center;letter-spacing:.22em;font-size:13px;font-weight:600;color:#35433e;margin:0 0 2px;text-transform:uppercase}
    h1{
      font-family:'Playfair Display',serif;text-align:center;margin:0;color:var(--green);
      font-size:45px;line-height:.98;letter-spacing:.03em;
    }
    .tagline{text-align:center;color:#596a62;font-size:13px;margin:14px 0 20px;font-weight:500}
    .divider{display:flex;align-items:center;gap:12px;margin:0 auto 20px;max-width:250px;color:var(--gold)}
    .divider::before,.divider::after{content:"";height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent);flex:1}.diamond{width:8px;height:8px;transform:rotate(45deg);border:1px solid var(--gold)}
    .login-title{text-align:center;color:var(--gold);font-size:20px;font-weight:800;margin-bottom:18px;letter-spacing:.04em}
    label{display:block;font-size:13px;font-weight:700;margin:0 0 7px;color:#17392e}.field{margin-bottom:16px}.input-wrap{display:flex;align-items:center;height:54px;border:1px solid var(--line);border-radius:13px;background:rgba(255,255,255,.78);overflow:hidden;transition:.2s}.input-wrap:focus-within{border-color:var(--gold);box-shadow:0 0 0 4px rgba(201,154,69,.14);background:#fff}.icon-box{width:54px;height:100%;display:grid;place-items:center;background:var(--gold-soft);color:#a8782d;flex:0 0 54px}.input-wrap input{width:100%;height:100%;border:0;outline:0;background:transparent;padding:0 16px;font-size:15px;color:#1e352e}.input-wrap input::placeholder{color:#a9afa9}.eye{padding-right:15px;color:#4a554f;display:grid;place-items:center;cursor:pointer}
    .row{display:flex;justify-content:space-between;align-items:center;gap:14px;margin:4px 0 22px;font-size:13px;font-weight:700}.remember{display:flex;align-items:center;gap:8px;color:#65716b;cursor:pointer}.remember input{width:15px;height:15px;accent-color:var(--green);cursor:pointer}a{color:var(--green-2);text-decoration:none}a:hover{text-decoration:underline}
    .btn{width:100%;height:58px;border:0;border-radius:14px;background:linear-gradient(135deg,var(--green-2),#08291f);color:#fff;font-weight:800;font-size:16px;letter-spacing:.03em;cursor:pointer;box-shadow:0 14px 26px rgba(8,41,31,.28);transition:.2s;display:flex;align-items:center;justify-content:center;gap:10px}.btn:hover{transform:translateY(-1px);filter:brightness(1.04)}.btn:disabled{opacity:.75;cursor:default;transform:none;filter:none}
    .footer{margin-top:27px;padding-top:20px;border-top:1px solid rgba(201,154,69,.25);display:flex;align-items:center;gap:13px;justify-content:center;text-align:left;color:#5c6862;font-size:12.5px;line-height:1.5}.footer b{display:block;color:#254439}.shield{width:34px;height:34px;color:var(--gold);flex:0 0 auto}
    .input-wrap.is-invalid{border-color:#dc3545;box-shadow:0 0 0 4px rgba(220,53,69,.14);background:#fff}
    .invalid-feedback{display:block;font-size:12px;color:#dc3545;margin-top:4px;font-weight:500}
    .spinner-border{display:inline-block;width:20px;height:20px;border:2px solid currentColor;border-right-color:transparent;border-radius:50%;animation:spinner-border .75s linear infinite}.d-none{display:none!important}
    @keyframes spinner-border{to{transform:rotate(360deg)}}
    @media(max-width:640px){body{background-position:center}.page{padding:18px}.login-card{padding:30px 22px 24px;border-radius:22px}h1{font-size:36px}.brand-small{font-size:11px}.row{font-size:12px}.footer{text-align:center;flex-direction:column;gap:8px}}
  </style>
</head>
<body>
  <main class="page">
    <section class="login-card">
      <div class="logo" aria-hidden="true">
        @if ($logo && $logo->nama_file)
          <img src="{{ asset('config_media/' . $logo->nama_file) }}" alt="{{ $konfigurasi->nama_perusahaan ?? 'Logo' }}">
        @else
          <svg viewBox="0 0 180 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M23 85L89 28l68 57" stroke="#c99a45" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M52 77v-34h25v34M102 77V48h25v29" stroke="#d8ac5b" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M26 93c31-14 87-14 127 0" stroke="#c99a45" stroke-width="7" stroke-linecap="round"/>
          </svg>
        @endif
      </div>
      <p class="brand-small">Manajemen Penjualan</p>
      <h1>{{ $konfigurasi->nama_perusahaan ?? 'PERUMAHAN' }}</h1>
      <p class="tagline">Kelola data, tingkatkan penjualan, bangun kepercayaan</p>
      <div class="divider"><span></span><i class="diamond"></i><span></span></div>
      <div class="login-title">LOGIN</div>
      <form id="formLogin" novalidate>
        @csrf
        <div class="field">
          <label for="username">Username</label>
          <div class="input-wrap">
            <span class="icon-box">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M20 21a8 8 0 0 0-16 0M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </span>
            <input id="username" name="username" type="text" placeholder="Username" autocomplete="username" spellcheck="false">
          </div>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <span class="icon-box">
              <svg width="21" height="21" viewBox="0 0 24 24" fill="none"><path d="M7 11V8a5 5 0 0 1 10 0v3M6 11h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </span>
            <input id="password" name="password" type="password" placeholder="Password" autocomplete="current-password">
            <span class="eye" id="eye-toggle" aria-label="Show password">
              <svg id="eye-icon" width="21" height="21" viewBox="0 0 24 24" fill="none"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="2"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/></svg>
            </span>
          </div>
        </div>
        <button class="btn" type="submit" id="submitBtn">
          <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          <span class="button-text">MASUK</span>
        </button>
      </form>
      <div class="footer">
        <svg class="shield" viewBox="0 0 24 24" fill="none"><path d="M12 3 20 6v6c0 5-3.4 8.6-8 10-4.6-1.4-8-5-8-10V6l8-3Z" stroke="currentColor" stroke-width="1.8"/><path d="m8.5 12.2 2.2 2.2 4.8-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <div><b>Sistem Informasi Manajemen Penjualan Perumahan</b>Aman &bull; Terpercaya &bull; Profesional</div>
      </div>
    </section>
  </main>

  <script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('templates/plugins/toastr/toastr.min.js') }}"></script>
  <script>
    const eyeToggle = document.getElementById("eye-toggle");
    const eyeIcon = document.getElementById("eye-icon");
    const passwordInput = document.getElementById("password");
    const eyeOpenSVG = '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z" /><circle cx="12" cy="12" r="3" />';
    const eyeClosedSVG = '<path d="M3 3l18 18" stroke-linecap="round" /><path d="M10.6 5.2A11.6 11.6 0 0 1 12 5c7 0 11 7 11 7a17.6 17.6 0 0 1-3.4 4.3M6.6 6.6C3.6 8.4 1 12 1 12s4 7 11 7c1.5 0 2.9-.3 4.1-.8" /><path d="M9.9 10c-.6.6-.9 1.3-.9 2 0 1.7 1.3 3 3 3 .7 0 1.4-.3 2-.9" />';
    let showPassword = false;
    if (eyeToggle) {
      eyeToggle.addEventListener("click", () => {
        showPassword = !showPassword;
        passwordInput.type = showPassword ? "text" : "password";
        eyeIcon.innerHTML = showPassword ? eyeOpenSVG : eyeClosedSVG;
        eyeToggle.setAttribute("aria-label", showPassword ? "Hide password" : "Show password");
      });
    }

    var audio = new Audio('{{ asset('audio/notification.ogg') }}');

    $(document).ready(function() {
      function refreshCsrfToken(callback) {
        $.get('{{ route('refresh.csrf') }}', function(data) {
          $('meta[name="csrf-token"]').attr('content', data.token);
          $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': data.token }
          });
          if (typeof callback === 'function') callback();
        });
      }

      $('#formLogin').on('submit', function(e) {
        e.preventDefault();
        let form = this;

        refreshCsrfToken(function() {
          let url = '{{ route('admin.loginPost') }}';
          let formData = new FormData(form);

          $('.input-wrap.is-invalid').removeClass('is-invalid');
          $('.invalid-feedback').remove();

          let submitBtn = $('#submitBtn');
          let spinner = submitBtn.find('.spinner-border');
          let btnText = submitBtn.find('.button-text');

          spinner.removeClass('d-none');
          btnText.text('Memproses...');
          submitBtn.prop('disabled', true);

          $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              window.location.href = response.redirect_url || "{{ route('beranda.index') }}";
            },
            error: function(xhr) {
              if (xhr.status === 422) {
                audio.play();
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(key, val) {
                  let input = $('#' + key);
                  input.closest('.input-wrap').addClass('is-invalid');
                  input.closest('.field').append(
                    '<span class="invalid-feedback" role="alert"><strong>' +
                    val[0] + '</strong></span>'
                  );
                });
              } else {
                toastr.error('Terjadi kesalahan pada server. Silakan coba lagi.');
              }
            },
            complete: function() {
              spinner.addClass('d-none');
              btnText.text('MASUK');
              submitBtn.prop('disabled', false);
            }
          });
        });
      });
    });
  </script>
</body>
</html>
