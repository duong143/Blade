<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Combo;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function chat(Request $request)
    {
        $userMessage = trim((string)$request->input('message'));
        if (empty($userMessage)) {
            return response()->json(['reply' => 'Bạn muốn hỏi tôi điều gì ạ?']);
        }

        // Chuyển tin nhắn về chữ thường không dấu để rà soát từ khóa thông minh hơn
        $lowerMsg = Str::lower($userMessage);
        $cleanMsg = $this->removeVietnameseSign($lowerMsg);

        // 1. XỬ LÝ KỊCH BẢN CHÀO HỎI / CÂU HỎI THƯỜNG GẶP
        if (preg_match('/(hello|hi|chao|xin chao|lo la)/i', $cleanMsg)) {
            return response()->json([
                'reply' => "Chào bạn! Tôi là trợ lý ảo AI thông minh của Travel Link. Tôi có thể giúp bạn tìm kiếm các combo du lịch lý tưởng, tư vấn lịch trình hoặc kiểm tra slot phòng. Bạn đang dự định đi du lịch ở đâu thế ạ? Nhập tên địa điểm để tôi tìm tour cho bạn nhé! 😉"
            ]);
        }

        if (preg_match('/(ai la ban|ban la ai|ten la gi|tro ly)/i', $cleanMsg)) {
            return response()->json([
                'reply' => "Tôi là Trợ lý ảo thông minh được phát triển bởi Travel Link, luôn túc trực 24/7 để đồng hành và hỗ trợ bạn thiết kế những chuyến đi tuyệt vời nhất!"
            ]);
        }

        if (preg_match('/(gia|bao nhieu|re nhat|mac nhat|tien)/i', $cleanMsg) && !preg_match('/(da lat|vung tau|ha noi|sa pa|nha trang|phu quoc|da nang|ha long)/i', $cleanMsg)) {
            return response()->json([
                'reply' => "Dạ, các combo bên Travel Link luôn có mức giá cực kỳ cạnh tranh và đã bao gồm đầy đủ thuế phí xe đưa đón + khách sạn. Bạn muốn khảo giá cho điểm đến nào cụ thể (ví dụ: Đà Lạt, Phú Quốc...) để tôi lọc giá tốt nhất cho mình ạ?"
            ]);
        }

        // 2. ENGINE AI QUÉT DATABASE TỰ ĐỘNG THEO ĐỊA ĐIỂM
        // Lấy toàn bộ combo đang hoạt động để làm phễu lọc từ khóa
        $combos = Combo::where('status', 1)->get();
        $matchedCombos = collect();

        foreach ($combos as $combo) {
            $titleClean = $this->removeVietnameseSign(Str::lower($combo->title));
            
            // Tách các từ khóa địa điểm phổ biến từ tiêu đề combo
            $locations = ['da lat', 'sa pa', 'vung tau', 'ha noi', 'phu quoc', 'nha trang', 'da nang', 'ha long', 'cat ba', 'quy nhon', 'phong nha', 'hue'];
            
            foreach ($locations as $loc) {
                // Nếu khách gõ trúng địa danh có trong tiêu đề combo
                if (str_contains($cleanMsg, $loc) && str_contains($titleClean, $loc)) {
                    $matchedCombos->push($combo);
                    break;
                }
            }
        }

        // Loại bỏ trùng lặp nếu có
        $matchedCombos = $matchedCombos->unique('id');

        // 3. ĐÓNG GÓI CÂU TRẢ LỜI SẮC NÉT KHỚP 100% VỚI DATA THẬT
        if ($matchedCombos->isNotEmpty()) {
            $reply = "Travel Link đang có các combo cực hot đáp ứng đúng nhu cầu tìm kiếm của bạn đây ạ:\n\n";
            
            foreach ($matchedCombos as $index => $combo) {
                $stt = $index + 1;
                $reply .= "📌 {$stt}. Combo: *{$combo->title}*\n";
                $reply .= "⏱ Thời gian: {$combo->duration_days} ngày {$combo->duration_nights} đêm\n";
                $reply .= "👉 Link xem chi tiết & đặt chỗ: /combo/{$combo->slug}\n\n";
            }
            
            $reply .= "Bạn thấy các phương án trên thế nào? Hãy bấm vào đường link để xem chi tiết lịch trình cụ thể hoặc nói cho tôi biết nếu bạn muốn điều chỉnh thêm nhé! 🥰";
            return response()->json(['reply' => $reply]);
        }

        // 4. NẾU KHÁCH HỎI ĐỊA ĐIỂM LẠ HOẶC CHƯA CÓ TRONG CƠ SỞ DỮ LIỆU
        return response()->json([
            'reply' => "Dạ hiện tại hệ thống chưa tìm thấy Combo nào khớp chính xác với từ khóa này rồi ạ. 😢\n\nTuy nhiên, các tuyến điểm hot như *Đà Lạt, Sa Sa, Phú Quốc, Nha Trang* đang có chương trình giảm giá lên đến 30% đấy ạ. Bạn có muốn tham khảo thử lịch trình của các tuyến điểm này không, hãy nói cho tôi biết nhé!"
        ]);
    }

    /**
     * Hàm helper phụ trợ xóa dấu tiếng Việt để chatbot hiểu thực tế hơn
     */
    private function removeVietnameseSign($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|ã|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|á|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        return $str;
    }
}