@php
    $existingStt = $proses->pluck('stt_aduan')->toArray();
    $nextStt = count($existingStt); // karena urut dari 0
    $progress = min(($nextStt / 4) * 100, 100);
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Timeline Progress --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-clipboard-list"></i> Progress Pengaduan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar"
                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                    <div class="row text-center small">
                        <div class="col-3">
                            <span class="badge {{ $nextStt > 0 ? 'bg-success' : 'bg-secondary' }}">Terkirim</span>
                        </div>
                        <div class="col-3">
                            <span
                                class="badge {{ $nextStt > 1 ? 'bg-success' : ($nextStt == 1 ? 'bg-warning' : 'bg-secondary') }}">Admin</span>
                        </div>
                        <div class="col-3">
                            <span
                                class="badge {{ $nextStt > 2 ? 'bg-success' : ($nextStt == 2 ? 'bg-warning' : 'bg-secondary') }}">Pengerjaan</span>
                        </div>
                        <div class="col-3">
                            <span
                                class="badge {{ $nextStt > 3 ? 'bg-success' : ($nextStt == 3 ? 'bg-warning' : 'bg-secondary') }}">Selesai</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- History Cards --}}
            @foreach ($proses as $index => $item)
                <div class="card shadow-sm mb-3 border-0">
                    <div class="card-header bg-light border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">Tahap {{ $index + 1 }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($item->tgl_update)->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        {{-- Status Aduan --}}
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label fw-semibold text-dark">
                                <i class="fas fa-flag me-2"></i>Status Aduan
                            </label>
                            <div class="col-sm-9">
                                @php
                                    switch ($item->stt_proses_aduan) {
                                        case 0:
                                            $status = 'Aduan Terkirim';
                                            $badgeClass = 'bg-info';
                                            break;
                                        case 1:
                                            $status = 'Proses Admin';
                                            $badgeClass = 'bg-warning';
                                            break;
                                        case 2:
                                            $status = 'Proses Pengerjaan';
                                            $badgeClass = 'bg-primary';
                                            break;
                                        case 3:
                                            $status = 'Proses Selesai';
                                            $badgeClass = 'bg-success';
                                            break;
                                        default:
                                            $status = '-';
                                            $badgeClass = 'bg-secondary';
                                    }
                                @endphp
                                <div class="d-flex align-items-center">
                                    <span class="badge {{ $badgeClass }} me-2">{{ $status }}</span>
                                    <input type="text" class="form-control border-0 bg-transparent ps-0" disabled
                                        value="{{ $status }}" style="display: none;">
                                </div>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="row">
                            <label class="col-sm-3 col-form-label fw-semibold text-dark">
                                <i class="fas fa-comment-dots me-2"></i>Isi Tanggapan
                            </label>
                            <div class="col-sm-9">
                                <div class="bg-light rounded p-3">
                                    <p class="mb-0 text-dark">{{ $item->catatan ?: 'Tidak ada catatan' }}</p>
                                    <textarea class="form-control" disabled style="display: none;">{{ $item->catatan }}</textarea>
                                </div>
                            </div>
                        </div>

                        @if ($item->stt_proses_aduan == 3)
                            <div class="row mt-3">
                                <label class="col-sm-3 col-form-label fw-semibold text-dark">
                                    <i class="fas fa-comment-dots me-2"></i>Isi Tanggapan
                                </label>
                                <div class="col-sm-9">
                                    <a type="button" class="btn btn-sm btn-info lihat-lampiran-proses"
                                        data-id="{{ $aduan->id }}" style="cursor:pointer;"
                                        data-title="Lampiran Proses">
                                        Lihat Foto
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Card Baru Buat Status Selanjutnya --}}
            @if ($nextStt <= 3)
                <input type="hidden" name="id_aduan" value="{{ $aduan->id }}">
                <input type="hidden" name="stt_aduan" value="{{ $nextStt }}">

                <div class="card shadow border-success border-2 mb-4">
                    <div class="card-body pt-4">

                        {{-- Status Aduan (readonly text) --}}
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label fw-semibold text-dark">
                                <i class="fas fa-flag me-2"></i>Status Aduan
                            </label>
                            <div class="col-sm-9">
                                @php
                                    switch ($nextStt) {
                                        case 0:
                                            $nextStatus = 'Aduan Terkirim';
                                            $nextBadgeClass = 'bg-info';
                                            break;
                                        case 1:
                                            $nextStatus = 'Proses Admin';
                                            $nextBadgeClass = 'bg-warning';
                                            break;
                                        case 2:
                                            $nextStatus = 'Proses Pengerjaan';
                                            $nextBadgeClass = 'bg-primary';
                                            break;
                                        case 3:
                                            $nextStatus = 'Proses Selesai';
                                            $nextBadgeClass = 'bg-success';
                                            break;
                                        default:
                                            $nextStatus = '-';
                                            $nextBadgeClass = 'bg-secondary';
                                    }
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge {{ $nextBadgeClass }} me-2">{{ $nextStatus }}</span>
                                </div>
                                <input type="text" class="form-control" readonly value="{{ $nextStatus }}">
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label fw-semibold text-dark">
                                <i class="fas fa-comment-dots me-2"></i>Isi Tanggapan <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <textarea name="catatan" class="form-control border-2" required rows="4"
                                    placeholder="Masukkan tanggapan atau catatan untuk tahap ini..."></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Berikan tanggapan yang jelas dan detail mengenai progress pengaduan.
                                </div>
                            </div>
                        </div>

                        @if ($nextStt == 3)
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label fw-bold" for="fotoProses">Foto Kondisi</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control-file" id="fotoProses" name="fotoProses[]"
                                        multiple accept=".jpg,.jpeg,.png" onchange="previewFoto(this)">
                                    <small class="form-text text-muted">
                                        Format: JPG, PNG, GIF. Maksimal 2MB
                                    </small>
                                </div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-3 col-form-label fw-bold">Preview Foto</label>
                                <div id="preview-container" class="d-flex flex-wrap gap-2 position-relative">
                                    <div id="no-preview"
                                        class="preview-foto d-flex align-items-center justify-content-center text-muted"
                                        style="width: 150px; height: 150px; border: 1px dashed #ccc;">
                                        <i class="fas fa-image fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Tombol Simpan --}}
                        <div class="row">
                            <div class="col-sm-9 offset-sm-3">
                                <div class="d-grid d-sm-flex">
                                    <button type="submit" id="submitBtn" class="btn btn-primary btn-md px-4 me-2">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                            aria-hidden="true"></span>
                                        <i class="fas fa-save me-2"></i>
                                        <span class="button-text">Simpan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.5em 0.75em;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-1px);
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, .05);
    }

    .text-muted {
        color: #6c757d !important;
    }

    .fw-semibold {
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .col-sm-3 {
            margin-bottom: 0.5rem;
        }

        .d-grid {
            gap: 0.5rem;
        }
    }
</style>
