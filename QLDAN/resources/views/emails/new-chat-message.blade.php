<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .sender {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .message-text {
            color: #333;
            line-height: 1.6;
        }
        .file-attachment {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
        }
        .file-attachment i {
            margin-right: 5px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .button:hover {
            background: #5568d3;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Tin nhắn mới</h1>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $recipientName }}</strong>,</p>
            
            <p>Bạn có tin nhắn mới từ <strong>{{ $senderName }}</strong>:</p>
            
            <div class="message-box">
                <div class="sender">{{ $senderName }}</div>
                @if($message->message)
                    <div class="message-text">{{ $message->message }}</div>
                @endif
                
                @if($message->file_name)
                    <div class="file-attachment">
                        📎 File đính kèm: <strong>{{ $message->file_name }}</strong>
                    </div>
                @endif
            </div>
            
            @if($conversation->deTai)
                <p><strong>Đề tài:</strong> {{ $conversation->deTai->TenDeTai }}</p>
            @endif
            
            <p>Nhấn vào nút bên dưới để xem và trả lời tin nhắn:</p>
            
            <a href="{{ url('/') }}" class="button">Xem tin nhắn</a>
            
            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                <em>Đây là email tự động, vui lòng không trả lời email này.</em>
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Hệ thống Quản lý Đồ án - Trường Đại học Đồng Tháp</p>
        </div>
    </div>
</body>
</html>
