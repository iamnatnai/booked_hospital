<?php
// อ่านข้อมูลจากไฟล์ JSON
$jsonFile = 'holiday.json'; // เปลี่ยนเป็นเส้นทางของไฟล์ JSON ของคุณ
$jsonData = file_get_contents($jsonFile);

// แปลง JSON เป็น Array
$data = json_decode($jsonData, true);

// แสดงข้อมูลวันหยุด
if (isset($data['VCALENDAR']) && isset($data['VCALENDAR'][0]['VEVENT'])) {
    $events = $data['VCALENDAR'][0]['VEVENT'];
    
    foreach ($events as $event) {
        $dateStart = isset($event['DTSTART;VALUE=DATE']) ? $event['DTSTART;VALUE=DATE'] : 'ไม่ทราบ';
        $summary = isset($event['SUMMARY']) ? $event['SUMMARY'] : 'ไม่มีข้อมูล';
        $description = isset($event['DESCRIPTION']) ? $event['DESCRIPTION'] : 'ไม่มีข้อมูล';

        // แปลงวันที่จากรูปแบบ YYYYMMDD เป็น DD/MM/YYYY
        $formattedDate = substr($dateStart, 6, 2) . '/' . substr($dateStart, 4, 2) . '/' . substr($dateStart, 0, 4);

        echo "Date: " . $formattedDate . "<br>";
        echo "Summary: " . $summary . "<br>";
        echo "Description: " . $description . "<br><br>";
    }
} else {
    echo "ไม่สามารถดึงข้อมูลวันหยุดได้";
}
?>
