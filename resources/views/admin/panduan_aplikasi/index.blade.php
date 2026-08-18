<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Aplikasi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }
        .video-list a {
            display: block;
            padding: 10px 15px;
            margin-bottom: 6px;
            background: #fff;
            border-radius: 6px;
            border: 1px solid #e7e7e7;
            text-decoration: none;
            color: #333;
            transition: .3s;
        }
        .video-list a:hover {
            background: #6f00ff;
            color: #fff;
        }
        .active-video {
            background: #6f00ff !important;
            color: #fff !important;
        }
        .video-box {
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #ddd;
            min-height: 320px;
        }
        .video-list-container {
            max-height: 80vh;
            overflow-y: auto;
        }
        .video-empty {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 18px;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📚 Panduan Aplikasi</h3>

        <select class="form-select w-auto" id="filter-role">
            @foreach ($roles as $r)
                <option value="{{ $r->id }}" {{ $r->id == 1 ? 'selected' : '' }}>
                    {{ $r->role }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="video-list video-list-container" id="videoList"></div>
        </div>

        <div class="col-md-8">
            <div class="video-box" id="videoBox">
                <h5 id="videoTitle"></h5>
                <div class="ratio ratio-16x9" id="videoContainer">
                    <iframe id="videoFrame" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    const videoList = document.getElementById('videoList');
    const videoFrame = document.getElementById('videoFrame');
    const videoTitle = document.getElementById('videoTitle');
    const roleSelect = document.getElementById('filter-role');
    const videoContainer = document.getElementById('videoContainer');

    function convertToEmbed(url) {
        if (!url) return '';
        return url.replace('youtu.be/', 'www.youtube.com/embed/');
    }

    function showEmptyVideo() {
        videoContainer.innerHTML = '<div class="video-empty">Video belum tersedia</div>';
    }

    function showIframe(url) {
        videoContainer.innerHTML = '<iframe id="videoFrame" allowfullscreen></iframe>';
        document.getElementById('videoFrame').src = url;
    }

    function loadMenu(roleId) {
        fetch(`/panduan-aplikasi/menu?id_role=${roleId}`)
            .then(res => res.json())
            .then(data => {
                videoList.innerHTML = '';
                videoTitle.textContent = '';
                videoContainer.innerHTML = '';

                if (data.length === 0) {
                    videoList.innerHTML = '<div class="text-muted">Tidak ada menu panduan</div>';
                    showEmptyVideo();
                    return;
                }

                data.forEach((item, index) => {
                    const a = document.createElement('a');
                    a.href = '#';
                    a.textContent = `${String(index + 1).padStart(2, '0')}. ${item.judul}`;
                    a.dataset.url = convertToEmbed(item.link_yt);

                    if (index === 0) a.classList.add('active-video');

                    a.addEventListener('click', e => {
                        e.preventDefault();

                        document.querySelectorAll('#videoList a')
                            .forEach(i => i.classList.remove('active-video'));

                        a.classList.add('active-video');
                        videoTitle.textContent = item.judul;

                        if (!item.link_yt) {
                            showEmptyVideo();
                        } else {
                            showIframe(a.dataset.url);
                        }
                    });

                    videoList.appendChild(a);
                });

                videoTitle.textContent = data[0].judul;

                if (!data[0].link_yt) {
                    showEmptyVideo();
                } else {
                    showIframe(convertToEmbed(data[0].link_yt));
                }
            });
    }

    loadMenu(1);

    roleSelect.addEventListener('change', function () {
        loadMenu(this.value);
    });
</script>

</body>
</html>
