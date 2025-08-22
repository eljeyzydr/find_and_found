@extends('layouts.app')

@section('content')
<h3>Detail Notifikasi</h3>

<div class="card">
    <div class="card-body">
        <p>{{ $notification->message }}</p>
        <p><small class="text-muted">Diterima: {{ $notification->created_at->format('d M Y H:i') }}</small></p>
    </div>
</div>

<a href="{{ route('notifications.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
