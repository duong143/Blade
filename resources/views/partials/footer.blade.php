<footer class="footer" style="background-color: #D0F2FE ;">
    <div class="container footer-top" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
        
        <div class="footer-col">
            <h4>Khám phá</h4>
            <p><a href="{{ route('combo.index') }}" style="color: inherit; text-decoration: none;">Trang chủ</a></p>
            <p><a href="{{ route('blogs.index') }}" style="color: inherit; text-decoration: none;">Cẩm nang</a></p>
            <p><a href="{{ route('contact.index') }}" style="color: inherit; text-decoration: none;">Liên hệ</a></p>
        </div>

        <div class="footer-col">
            <h4>Thông tin đồ án</h4>
            <p>Sinh viên: <strong>Trần Đức Dương</strong></p>
            <p>MSSV: 20221635</p>
            <p>Giảng viên: TS. Mai Đình Sinh</p>
        </div>

        <div class="footer-col">
            <h4>Liên hệ dự án</h4>
            <p>Email: {{ $footerSettings['company_email'] ?? 'sinhvien@eaut.edu.vn' }}</p>
            <p>SĐT: {{ $footerSettings['company_phone'] ?? '0123 456 789' }}</p>
            <p>Địa chỉ: {{ $footerSettings['company_address'] ?? 'Hà Nội' }}</p>
        </div>
    
    </div>
</footer>