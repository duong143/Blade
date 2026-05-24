@extends('layout')

@section('title', 'Trợ lý du lịch AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-3 d-flex align-items-center gap-3">
                    <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 1.5rem;">
                        🤖
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Trợ Lý Ảo Travel Link AI</h5>
                        <small class="opacity-75">Hỗ trợ tư vấn lịch trình & đặt tour 24/7</small>
                    </div>
                </div>

                <div class="card-body bg-light" id="chat-box" style="height: 450px; overflow-y: auto; padding: 20px;">
                    <div class="d-flex mb-3 align-items-start gap-2">
                        <div class="p-2 bg-white rounded-3 shadow-sm border" style="max-width: 75%;">
                            Chào bạn! Tôi là trợ lý ảo AI của Travel Link. Bạn đang muốn tìm kiếm combo du lịch đi đâu, ngân sách bao nhiêu hay cần lên lịch trình thế nào? Hãy nói cho tôi biết nhé! 😉
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white p-3 border-top-0">
                    <form id="chat-form" class="input-group">
                        @csrf
                        <input type="text" id="user-input" class="form-control bg-light border-0 py-3 ps-4 rounded-start-pill" placeholder="Nhập câu hỏi của bạn tại đây..." autocomplete="off" required>
                        <button class="btn btn-primary px-4 rounded-end-pill" type="submit" id="btn-send">
                            Gửi <i class="fas fa-paper-plane ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        
        let message = $('#user-input').val().trim();
        if(message === '') return;

        // 1. Hiển thị tin nhắn của User lên khung chat
        $('#chat-box').append(`
            <div class="d-flex mb-3 align-items-start gap-2 justify-content-end">
                <div class="p-2 bg-primary text-white rounded-3 shadow-sm" style="max-width: 75%;">
                    ${message}
                </div>
            </div>
        `);
        
        $('#user-input').val('');
        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

        // Hiển thị trạng thái AI đang gõ tin nhắn
        $('#chat-box').append(`
            <div class="d-flex mb-3 align-items-start gap-2" id="typing-indicator">
                <div class="p-2 bg-white rounded-3 shadow-sm border text-muted">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span> AI đang suy nghĩ...
                </div>
            </div>
        `);
        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

        // 2. Gửi dữ liệu Ajax lên Laravel Backend
        $.ajax({
            url: "{{ route('chatbot.send') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                message: message
            },
            success: function(response) {
                // Xóa dòng trạng thái đang gõ
                $('#typing-indicator').remove();

                // Hiển thị câu trả lời của AI (Chuyển đổi các dấu xuống dòng thành thẻ <br>)
                let formattedReply = response.reply.replace(/\n/g, '<br>');
                $('#chat-box').append(`
                    <div class="d-flex mb-3 align-items-start gap-2">
                        <div class="p-2 bg-white rounded-3 shadow-sm border text-dark" style="max-width: 75%; line-height: 1.6;">
                            ${formattedReply}
                        </div>
                    </div>
                `);
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            },
            error: function() {
                $('#typing-indicator').remove();
                $('#chat-box').append(`
                    <div class="d-flex mb-3 align-items-start gap-2">
                        <div class="p-2 bg-danger text-white rounded-3 shadow-sm" style="max-width: 75%;">
                            Úp, hệ thống mất kết nối API. Vui lòng kiểm tra cấu hình Key!
                        </div>
                    </div>
                `);
            }
        });
    });
</script>
@endsection
