<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทดสอบส่งข้อความผ่าน LINE OA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>ทดสอบส่งข้อความผ่าน LINE OA</h2>
        <form action="send_line_message.php" method="POST">
            <div class="mb-3">
                <label for="line_id" class="form-label">User ID หรือ Group ID ของผู้รับ</label>
                <input type="text" class="form-control" id="line_id" name="line_id" placeholder="ใส่ LINE ID ของผู้รับ" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">ข้อความ</label>
                <textarea class="form-control" id="message" name="message" rows="3" placeholder="ใส่ข้อความที่ต้องการส่ง" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">ส่งข้อความ</button>
        </form>
    </div>
</body>
</html>
