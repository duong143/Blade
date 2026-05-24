@extends('admin.layout')

@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold text-primary">
                <i class="fas fa-envelope-open-text mr-2"></i> Nội dung tin nhắn từ: {{ $contact->name }}
            </h3>
            <div class="card-tools">
                <span class="badge badge-light p-2 border">
                    <i class="far fa-clock mr-1"></i> Gửi lúc: {{ $contact->created_at->format('H:i d/m/Y') }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 border-right">
                    <h5 class="text-muted border-bottom pb-2 mb-3">Thông tin người gửi</h5>
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $contact->name }}</p>
                    <p class="mb-2"><strong>Email:</strong> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                    <p class="mb-2"><strong>Số điện thoại:</strong> {{ $contact->phone ?? 'Không cung cấp' }}</p>
                    <p class="mb-2"><strong>Chủ đề:</strong> <span class="text-info">{{ $contact->subject ?? '(Không có chủ đề)' }}</span></p>
                </div>
                
                <div class="col-md-8">
                    <h5 class="text-muted border-bottom pb-2 mb-3">Lời nhắn chi tiết</h5>
                    <div class="p-4 bg-light rounded border" style="min-height: 250px; font-size: 1.1rem; line-height: 1.6; color: #333;">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white text-right">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
            </a>
            <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Xóa vĩnh viễn tin nhắn này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4">
                    <i class="fas fa-trash-alt mr-1"></i> Xóa tin này
                </button>
            </form>
        </div>
    </div>
</div>
@endsection