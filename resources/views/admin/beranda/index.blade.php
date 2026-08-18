@extends('admin.layout_admin')

@section('content')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
      --bg: #f6f8fc;
      --surface: #ffffff;
      --text: #111827;
      --muted: #6b7280;
      --line: #e5e7eb;
      --primary: #5b2cff;
      --primary-soft: #ede9fe;
      --shadow: 0 10px 30px rgba(17, 24, 39, .07);
      --radius: 18px;
    }

    * { box-sizing: border-box; }
    .dashboard-shell {
      margin: 0;
      font-family: "Inter", sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    .dashboard-shell {
      width: 100%;
      min-height: 100vh;
      padding: 28px;
    }

    .dashboard-wrap {
      max-width: 1550px;
      margin: 0 auto;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 20px;
      margin-bottom: 22px;
    }

    .title h1 {
      margin: 0 0 8px;
      font-size: 32px;
      line-height: 1.2;
    }

    .title p {
      margin: 0;
      color: var(--muted);
      font-size: 14px;
    }

    .top-actions {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    .icon-btn, .date-btn, .filter-btn {
      border: 1px solid var(--line);
      background: #fff;
      color: #374151;
      border-radius: 12px;
      min-height: 42px;
      padding: 0 14px;
      display: inline-flex;
      align-items: center;
      gap: 9px;
      font-weight: 600;
      box-shadow: 0 5px 15px rgba(17,24,39,.04);
      cursor: pointer;
    }

    .icon-btn { width: 42px; justify-content: center; padding: 0; }

    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(5, minmax(0, 1fr));
      gap: 14px;
      margin-bottom: 18px;
    }

    .kpi-card {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 18px;
      display: flex;
      align-items: center;
      gap: 15px;
      box-shadow: var(--shadow);
      min-height: 118px;
      min-width: 0;
      overflow: hidden;
    }

    .kpi-card > div:last-child {
      min-width: 0;
      flex: 1;
    }

    .kpi-icon {
      width: 54px;
      height: 54px;
      border-radius: 15px;
      display: grid;
      place-items: center;
      color: #fff;
      font-size: 23px;
      flex: 0 0 auto;
    }

    .blue { background: linear-gradient(135deg,#0ea5e9,#2563eb); }
    .green { background: linear-gradient(135deg,#22c55e,#15803d); }
    .orange { background: linear-gradient(135deg,#f59e0b,#f97316); }
    .purple { background: linear-gradient(135deg,#7c3aed,#4f46e5); }
    .red { background: linear-gradient(135deg,#fb7185,#e11d48); }

    .kpi-label {
      font-size: 13px;
      color: #4b5563;
      margin-bottom: 7px;
      white-space: nowrap;
    }

    .kpi-value {
      font-size: clamp(17px, 1.25vw, 21px);
      font-weight: 800;
      margin-bottom: 5px;
      line-height: 1.15;
      overflow-wrap: anywhere;
      word-break: normal;
    }

    .kpi-value.money {
      font-size: clamp(15px, 1.08vw, 20px);
      letter-spacing: -.4px;
      white-space: normal;
    }

    .kpi-note {
      color: var(--muted);
      font-size: 12px;
    }

    .positive { color: #16a34a; font-weight: 600; }

    .panel {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      margin-bottom: 18px;
      overflow: hidden;
    }

    .panel-body { padding: 20px; }

    .section-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      margin-bottom: 16px;
    }

    .section-head h2 {
      margin: 0;
      font-size: 18px;
    }

    .section-head span {
      color: var(--muted);
      font-size: 13px;
      font-weight: 500;
    }

    .pipeline {
      display: grid;
      grid-template-columns: repeat(7, minmax(145px, 1fr));
      gap: 24px;
      overflow-x: auto;
      padding: 2px 5px 10px;
    }

    .pipeline-card {
      position: relative;
      min-height: 170px;
      border-radius: 14px;
      padding: 16px 12px;
      text-align: center;
      border: 1.5px solid;
      background: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .pipeline-card:not(:last-child)::after {
      content: "\f054";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      position: absolute;
      top: 50%;
      right: -19px;
      transform: translateY(-50%);
      color: #111827;
      font-size: 15px;
    }

    .pipeline-card h3 {
      margin: 0 0 8px;
      font-size: 14px;
    }

    .pipeline-card .count {
      font-size: 30px;
      font-weight: 800;
      margin-bottom: 18px;
    }

    .pipeline-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      min-height: 34px;
      padding: 8px 12px;
      border-radius: 10px;
      color: currentColor;
      background: rgba(255, 255, 255, .78);
      border: 1px solid currentColor;
      font-size: 12px;
      font-weight: 800;
      text-decoration: none;
      transition: .18s ease;
    }

    .pipeline-btn:hover {
      color: #fff;
      background: var(--pipeline-accent);
      border-color: var(--pipeline-accent);
      text-decoration: none;
    }

    .c-red { --pipeline-accent:#e11d48; color:#e11d48; border-color:#fb7185; background:#fff5f7; }
    .c-cyan { --pipeline-accent:#0891b2; color:#0891b2; border-color:#22d3ee; background:#f2fdff; }
    .c-green { --pipeline-accent:#15803d; color:#15803d; border-color:#4ade80; background:#f3fff6; }
    .c-orange { --pipeline-accent:#c56a00; color:#c56a00; border-color:#f59e0b; background:#fff9ed; }
    .c-pink { --pipeline-accent:#db2777; color:#db2777; border-color:#f472b6; background:#fff5fb; }
    .c-purple { --pipeline-accent:#5b21b6; color:#5b21b6; border-color:#8b5cf6; background:#faf7ff; }
    .c-blue { --pipeline-accent:#1d4ed8; color:#1d4ed8; border-color:#60a5fa; background:#f5f9ff; }

    .projects-table-wrap {
      overflow-x: hidden;
    }

    .projects-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
      min-width: 0;
    }

    .projects-table th,
    .projects-table td {
      padding: 13px 10px;
      border-bottom: 1px solid var(--line);
      text-align: center;
      vertical-align: middle;
      font-size: 12px;
    }

    .projects-table th:not(:first-child),
    .projects-table td:not(:first-child) {
      width: 86px;
      border-left: 1px solid var(--line);
      border-right: 1px solid var(--line);
    }

    .projects-table th {
      color: #6b7280;
      background: #fafafa;
      font-weight: 700;
    }

    .projects-table td:first-child,
    .projects-table th:first-child {
      text-align: left;
      width: 330px;
    }

    .project-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .project-thumb {
      width: 80px;
      height: 54px;
      object-fit: cover;
      border-radius: 10px;
      background: linear-gradient(135deg,#dbeafe,#dcfce7);
      border: 1px solid #dbe4f0;
    }

    .project-name {
      font-weight: 800;
      margin-bottom: 6px;
    }

    .project-meta {
      color: var(--muted);
      font-size: 11px;
    }

    .code-pill {
      display: inline-flex;
      padding: 3px 7px;
      border-radius: 6px;
      background: #eef2ff;
      color: #4338ca;
      font-weight: 700;
      margin-right: 5px;
    }

    .metric-number {
      font-size: 16px;
      font-weight: 800;
      display: block;
      margin-bottom: 3px;
    }

    .detail-btn {
      border: 1px solid #c7d2fe;
      background: #fff;
      color: #4f46e5;
      border-radius: 9px;
      padding: 7px 12px;
      font-weight: 700;
      cursor: pointer;
    }

    .table-total td {
      background: #fafafa;
      font-weight: 800;
    }

    .bottom-grid {
      display: grid;
      grid-template-columns: 1.15fr .95fr 1.05fr;
      gap: 16px;
    }

    .chart-box {
      height: 270px;
    }

    .bar-chart {
      height: 210px;
      display: flex;
      align-items: end;
      gap: 12px;
      padding: 18px 8px 30px;
      border-left: 1px solid var(--line);
      border-bottom: 1px solid var(--line);
    }

    .bar-group {
      height: 100%;
      flex: 1;
      display: flex;
      align-items: end;
      gap: 4px;
      position: relative;
    }

    .bar {
      width: 50%;
      border-radius: 6px 6px 0 0;
      min-height: 8px;
    }

    .bar.target { background: #ddd6fe; }
    .bar.actual { background: #5b2cff; }

    .bar-label {
      position: absolute;
      bottom: -23px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 10px;
      color: #6b7280;
    }

    .legend {
      display:flex;
      justify-content:center;
      gap:18px;
      font-size:11px;
      color:#6b7280;
      margin-top:12px;
    }

    .legend i {
      width:10px;
      height:10px;
      display:inline-block;
      border-radius:3px;
      margin-right:5px;
    }

    .marketing-list, .activity-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .marketing-list,
    .bank-list {
      max-height: 360px;
      overflow-y: auto;
      padding-right: 4px;
    }

    .marketing-item, .activity-item {
      display: flex;
      align-items: center;
      gap: 11px;
      padding-bottom: 10px;
      border-bottom: 1px solid #f0f1f4;
    }

    .bank-item {
      display: grid;
      grid-template-columns: 28px 1fr 74px 54px;
      align-items: center;
      gap: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid #f0f1f4;
    }

    .rank {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #f3f4f6;
      display: grid;
      place-items: center;
      font-size: 11px;
      font-weight: 800;
    }

    .avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: linear-gradient(135deg,#c7d2fe,#fbcfe8);
      display: grid;
      place-items: center;
      color: #4f46e5;
      font-weight: 800;
      flex: 0 0 auto;
    }

    .item-main {
      flex: 1;
      min-width: 0;
    }

    .item-title {
      font-size: 12px;
      font-weight: 700;
      margin-bottom: 3px;
    }

    .item-sub {
      color: var(--muted);
      font-size: 10px;
    }

    .item-value {
      color: var(--primary);
      font-weight: 800;
      font-size: 12px;
    }

    .activity-icon {
      width: 32px;
      height: 32px;
      border-radius: 9px;
      display: grid;
      place-items: center;
      color:#fff;
      flex: 0 0 auto;
    }

    .activity-time {
      color: var(--muted);
      font-size: 10px;
      white-space: nowrap;
    }

    @media (max-width: 1250px) {
      .kpi-grid { grid-template-columns: repeat(2, 1fr); }
      .kpi-card:last-child { grid-column: span 2; }
      .bottom-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 700px) {
      .dashboard-shell { padding: 15px; }
      .topbar { flex-direction: column; }
      .title h1 { font-size: 26px; }
      .kpi-grid { grid-template-columns: 1fr; }
      .kpi-card:last-child { grid-column: auto; }
      .panel-body { padding: 15px; }
    }
  
      body.dark-mode .dashboard-shell {
        background: #111827;
        color: #e5e7eb;
      }

      body.dark-mode .panel,
      body.dark-mode .kpi-card,
      body.dark-mode .icon-btn,
      body.dark-mode .date-btn,
      body.dark-mode .filter-btn {
        background: #1f2937;
        border-color: #374151;
        color: #e5e7eb;
      }

      body.dark-mode .title p,
      body.dark-mode .kpi-label,
      body.dark-mode .kpi-note,
      body.dark-mode .section-head span,
      body.dark-mode .project-meta,
      body.dark-mode .item-sub,
      body.dark-mode .activity-time {
        color: #a8b2c7;
      }
    </style>

    <div class="content-wrapper">
  <main class="dashboard-shell">
    <div class="dashboard-wrap">

      <header class="topbar">
        <div class="title">
          <h1>Selamat datang, {{ $username ?? 'dev' }}</h1>
          <p>Ringkasan penjualan dan aktivitas hari ini</p>
        </div>

        <div class="top-actions">
          <button class="icon-btn"><i class="fa-regular fa-bell"></i></button>
          <button class="icon-btn"><i class="fa-regular fa-calendar"></i></button>
          <button class="date-btn"><i class="fa-regular fa-calendar-days"></i> 26 Juli 2026</button>
        </div>
      </header>

      <section class="kpi-grid">
        <article class="kpi-card">
          <div class="kpi-icon blue"><i class="fa-solid fa-building"></i></div>
          <div>
            <div class="kpi-label">Jumlah Project</div>
            <div class="kpi-value" style="color:#2563eb">{{ $summaryMetrics['jumlah_project'] ?? 0 }}</div>
          </div>
        </article>

        <article class="kpi-card">
          <div class="kpi-icon green"><i class="fa-solid fa-layer-group"></i></div>
          <div>
            <div class="kpi-label">Total Unit</div>
            <div class="kpi-value" style="color:#16a34a">{{ $summaryMetrics['total_unit'] ?? 0 }}</div>
          </div>
        </article>

        <article class="kpi-card">
          <div class="kpi-icon orange"><i class="fa-solid fa-wallet"></i></div>
          <div>
            <div class="kpi-label">Booking Fee Hari Ini</div>
            <div class="kpi-value money" style="color:#f97316">Rp {{ number_format($summaryMetrics['booking_fee_hari_ini'] ?? 0, 0, ',', '.') }}</div>
          </div>
        </article>

        <article class="kpi-card">
          <div class="kpi-icon purple"><i class="fa-regular fa-credit-card"></i></div>
          <div>
            <div class="kpi-label">Piutang</div>
            <div class="kpi-value money" style="color:#5b2cff">Rp {{ number_format($summaryMetrics['piutang'] ?? 0, 0, ',', '.') }}</div>
            <div class="kpi-note">Total {{ $summaryMetrics['piutang_customer'] ?? 0 }} customer</div>
          </div>
        </article>

        <article class="kpi-card">
          <div class="kpi-icon red"><i class="fa-regular fa-clock"></i></div>
          <div>
            <div class="kpi-label">Tagihan Tempo</div>
            <div class="kpi-value" style="color:#e11d48">{{ $summaryMetrics['tagihan_tempo_customer'] ?? 0 }} Customer</div>
            <div class="kpi-note">Total Rp {{ number_format($summaryMetrics['tagihan_tempo_total'] ?? 0, 0, ',', '.') }}</div>
          </div>
        </article>
      </section>

      <section class="panel">
        <div class="panel-body">
          <div class="section-head">
            <h2>Pipeline Penjualan <span>(Semua Project)</span></h2>
          </div>

          <div class="pipeline">
            <article class="pipeline-card c-red">
              <h3>Booking</h3>
              <div class="count">{{ $pipelineCounts['booking'] ?? 0 }}</div>
              <a class="pipeline-btn" href="{{ route('pengajuan-hold.index') }}">Buka Menu <i class="fa-solid fa-arrow-right"></i></a>
            </article>

            <article class="pipeline-card c-cyan">
              <h3>SPPR</h3>
              <div class="count">{{ $pipelineCounts['sppr'] ?? 0 }}</div>
              <a class="pipeline-btn" href="{{ route('sppr.index') }}">Buka Menu <i class="fa-solid fa-arrow-right"></i></a>
            </article>

            <article class="pipeline-card c-green">
              <h3>Wawancara</h3>
              <div class="count">{{ $pipelineCounts['wawancara'] ?? 0 }}</div>
              <a class="pipeline-btn" href="{{ route('wawancara.index') }}">Buka Menu <i class="fa-solid fa-arrow-right"></i></a>
            </article>

            <article class="pipeline-card c-orange">
              <h3>ACC Bank</h3>
              <div class="count">{{ $pipelineCounts['acc_bank'] ?? 0 }}</div>
              <a class="pipeline-btn" href="{{ route('acc-bank.index') }}">Buka Menu <i class="fa-solid fa-arrow-right"></i></a>
            </article>

            <article class="pipeline-card c-pink">
              <h3>PPJB</h3>
              <div class="count">{{ $pipelineCounts['ppjb'] ?? 0 }}</div>
              <a class="pipeline-btn" href="{{ route('ppjb.index') }}">Buka Menu <i class="fa-solid fa-arrow-right"></i></a>
            </article>

            <article class="pipeline-card c-purple">
              <h3>Akad</h3>
              <div class="count">{{ $pipelineCounts['akad'] ?? 0 }}</div>
              <a class="pipeline-btn" href="{{ route('akad.index') }}">Buka Menu <i class="fa-solid fa-arrow-right"></i></a>
            </article>

            <article class="pipeline-card c-blue">
              <h3>BAST</h3>
              <div class="count">{{ $pipelineCounts['bast'] ?? 0 }}</div>
              <a class="pipeline-btn" href="{{ route('bast.index') }}">Buka Menu <i class="fa-solid fa-arrow-right"></i></a>
            </article>
          </div>

        </div>
      </section>

      <section class="panel">
        <div class="panel-body">
          <div class="section-head">
            <h2>Statistik per Project / Perumahan</h2>
          </div>

          <div class="projects-table-wrap">
            <table class="projects-table">
              <thead>
                <tr>
                  <th>Project / Perumahan</th>
                  <th>Booking</th>
                  <th>SPPR</th>
                  <th>Wawancara</th>
                  <th>ACC Bank</th>
                  <th>PPJB</th>
                  <th>Akad</th>
                  <th>BAST</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($projectStats as $project)
                  <tr>
                    <td>
                      <div class="project-info">
                        <div class="project-thumb"></div>
                        <div>
                          <div class="project-name">{{ $project['nama'] }}</div>
                          <div class="project-meta"><span class="code-pill">{{ $project['kode'] }}</span>{{ $project['total_unit'] }} Unit</div>
                        </div>
                      </div>
                    </td>
                    <td><span class="metric-number" style="color:#e11d48">{{ $project['booking'] }}</span></td>
                    <td><span class="metric-number" style="color:#0891b2">{{ $project['sppr'] }}</span></td>
                    <td><span class="metric-number" style="color:#15803d">{{ $project['wawancara'] }}</span></td>
                    <td><span class="metric-number" style="color:#c56a00">{{ $project['acc_bank'] }}</span></td>
                    <td><span class="metric-number" style="color:#db2777">{{ $project['ppjb'] }}</span></td>
                    <td><span class="metric-number" style="color:#5b21b6">{{ $project['akad'] }}</span></td>
                    <td><span class="metric-number" style="color:#1d4ed8">{{ $project['bast'] }}</span></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted">Belum ada data project.</td>
                  </tr>
                @endforelse

                <tr class="table-total">
                  <td>Total</td>
                  <td>{{ $projectTotals['booking'] ?? 0 }}</td>
                  <td>{{ $projectTotals['sppr'] ?? 0 }}</td>
                  <td>{{ $projectTotals['wawancara'] ?? 0 }}</td>
                  <td>{{ $projectTotals['acc_bank'] ?? 0 }}</td>
                  <td>{{ $projectTotals['ppjb'] ?? 0 }}</td>
                  <td>{{ $projectTotals['akad'] ?? 0 }}</td>
                  <td>{{ $projectTotals['bast'] ?? 0 }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
      <section class="bottom-grid">
        <article class="panel">
          <div class="panel-body">
            <div class="section-head">
              <h2>Grafik Penjualan Bulanan <span>(Semua Project)</span></h2>
              <button class="filter-btn">Tahun 2026 <i class="fa-solid fa-chevron-down"></i></button>
            </div>

            <div class="chart-box">
              <div class="bar-chart">
                <div class="bar-group"><div class="bar target" style="height:42%"></div><div class="bar actual" style="height:34%"></div><span class="bar-label">Jan</span></div>
                <div class="bar-group"><div class="bar target" style="height:51%"></div><div class="bar actual" style="height:47%"></div><span class="bar-label">Feb</span></div>
                <div class="bar-group"><div class="bar target" style="height:62%"></div><div class="bar actual" style="height:68%"></div><span class="bar-label">Mar</span></div>
                <div class="bar-group"><div class="bar target" style="height:58%"></div><div class="bar actual" style="height:63%"></div><span class="bar-label">Apr</span></div>
                <div class="bar-group"><div class="bar target" style="height:70%"></div><div class="bar actual" style="height:68%"></div><span class="bar-label">Mei</span></div>
                <div class="bar-group"><div class="bar target" style="height:82%"></div><div class="bar actual" style="height:53%"></div><span class="bar-label">Jun</span></div>
                <div class="bar-group"><div class="bar target" style="height:54%"></div><div class="bar actual" style="height:50%"></div><span class="bar-label">Jul</span></div>
                <div class="bar-group"><div class="bar target" style="height:72%"></div><div class="bar actual" style="height:41%"></div><span class="bar-label">Agu</span></div>
                <div class="bar-group"><div class="bar target" style="height:45%"></div><div class="bar actual" style="height:31%"></div><span class="bar-label">Sep</span></div>
                <div class="bar-group"><div class="bar target" style="height:83%"></div><div class="bar actual" style="height:50%"></div><span class="bar-label">Okt</span></div>
                <div class="bar-group"><div class="bar target" style="height:65%"></div><div class="bar actual" style="height:45%"></div><span class="bar-label">Nov</span></div>
                <div class="bar-group"><div class="bar target" style="height:55%"></div><div class="bar actual" style="height:39%"></div><span class="bar-label">Des</span></div>
              </div>
              <div class="legend"><span><i style="background:#ddd6fe"></i>Target</span><span><i style="background:#5b2cff"></i>Realisasi</span></div>
            </div>
          </div>
        </article>

        <article class="panel">
          <div class="panel-body">
            <div class="section-head">
              <h2>Penjualan Marketing</h2>
            </div>

            <div class="marketing-list">
              @forelse ($marketingStats as $index => $marketing)
                <div class="marketing-item">
                  <span class="rank">{{ $index + 1 }}</span>
                  <span class="avatar">{{ $marketing['inisial'] }}</span>
                  <div class="item-main">
                    <div class="item-title">{{ $marketing['nama'] }}</div>
                    <div class="item-sub">{{ $marketing['kode'] }} · Marketing</div>
                  </div>
                  <div class="item-value">{{ $marketing['jumlah'] }} Unit</div>
                </div>
              @empty
                <div class="item-sub">Belum ada data marketing.</div>
              @endforelse
            </div>
          </div>
        </article>

        <article class="panel">
          <div class="panel-body">
            <div class="section-head"><h2>Statistik Penggunaan Bank</h2></div>

            <div class="bank-list">
              @forelse ($bankStats as $index => $bank)
                <div class="bank-item">
                  <span class="rank">{{ $index + 1 }}</span>
                  <div class="item-main">
                    <div class="item-title">{{ $bank['nama'] }}</div>
                    <div class="item-sub">Bank KPR</div>
                  </div>
                  <div class="item-value">{{ $bank['jumlah'] }} Nasabah</div>
                  <div class="item-value">{{ $bank['persentase'] }}%</div>
                </div>
              @empty
                <div class="item-sub">Belum ada penggunaan bank.</div>
              @endforelse
            </div>
          </div>
        </article>
      </section>

    </div>
  </main>

    </div>
@endsection



