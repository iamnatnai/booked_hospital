<?php
header('Content-Type: application/json');

// ข้อมูลจำลองของตารางเวลาแพทย์
$data = [
    [
        'date' => '2024-09-16',
        'start_time' => '09:00',
        'end_time' => '12:00',
        'doctor_name' => 'ดร.สมชาย'
    ],
    [
        'date' => '2024-09-16',
        'start_time' => '13:00',
        'end_time' => '17:00',
        'doctor_name' => 'ดร.สมหญิง'
    ],
    [
        'date' => '2024-09-17',
        'start_time' => '09:00',
        'end_time' => '12:00',
        'doctor_name' => 'ดร.สมชาย'
    ],
    [
        'date' => '2024-09-17',
        'start_time' => '13:00',
        'end_time' => '17:00',
        'doctor_name' => 'ดร.สมหญิง'
    ],
    [
        'date' => '2024-09-18',
        'start_time' => '09:00',
        'end_time' => '12:00',
        'doctor_name' => 'ดร.สมศักดิ์'
    ],
    [
        'date' => '2024-09-18',
        'start_time' => '13:00',
        'end_time' => '17:00',
        'doctor_name' => 'ดร.สมหญิง'
    ]
];

// ส่งข้อมูลในรูปแบบ JSON
echo json_encode($data);
?>
