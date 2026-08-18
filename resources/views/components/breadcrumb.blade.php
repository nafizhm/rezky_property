    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-danger p-0 m-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-primary">Dashboard</a>
            </li>
            @isset($breadcrumb)
                @foreach ($breadcrumb as $item)
                    @if ($loop->last)
                        <li class="breadcrumb-item active text-muted" aria-current="page">{{ $item }}</li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $item['url'] }}" class="text-primary">{{ $item['label'] }}</a>
                        </li>
                    @endif
                @endforeach
            @endisset
        </ol>
    </nav>
