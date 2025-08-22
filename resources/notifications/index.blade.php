@extends('layouts.app')

@section('content')
<h3>Notifikasi</h3>

@if($notifications->isEmpty())
    <p>Belum ada notifikasi.</p>
@else
    <ul class="list-group">
        @foreach($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->is_read ? '' : 'fw-bold' }}">
                <div>
                    <a href="{{ route('notifications.show', $notification->id) }}">
                        {{ $notification->message }}
                    </a>
                    <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->is_read)
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-sm btn-success">Tandai Dibaca</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>
@endif
@endsection
