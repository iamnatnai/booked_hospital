<?php
include 'conection.php';

// SQL ดึงข้อมูลการจองพร้อมเชื่อมกับ timeslots
$sql = "SELECT 
            bc.booked_id, bc.national_id, bc.name_patient, bc.clinic_id, bc.time_slot_id, 
            ts.start_time, ts.end_time, bc.date_booked, bc.date_booked_stamp, bc.doctor_name, 
            bc.booked_count, bc.status, bc.confirm_date, bc.complete
        FROM 
            booked_confirm bc
        JOIN 
            time_slots ts ON bc.time_slot_id = ts.id
        WHERE 
            ts.is_delete = 0";

$result = $conn->query($sql);

// สร้าง array สำหรับเก็บข้อมูล
$bookings = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // กำหนด className ตามสถานะการจอง
        $className = $row['status'] === 'approved' ? 'approved' : 'pending';

        $booking = array(
            'id' => $row['booked_id'],
            'title' => $row['name_patient'], // แสดงชื่อผู้ป่วยเป็นชื่อ event
            // รวมวันที่จาก date_booked กับเวลาจาก start_time และ end_time
            'start' => $row['date_booked'] . 'T' . date('H:i:s', strtotime($row['start_time'])),
            'end' => $row['date_booked'] . 'T' . date('H:i:s', strtotime($row['end_time'])),
            'description' => 'หมอ: ' . $row['doctor_name'] . ' สถานะ: ' . $row['status'],
            'className' => $className, // เพิ่ม className เพื่อใช้ใน FullCalendar
            'extendedProps' => array(
                'date_booked' => $row['date_booked'],
                'confirm_date' => $row['confirm_date'],
                'complete' => $row['complete']
            )
        );
        
        array_push($bookings, $booking);
    }
}

// ส่งข้อมูลกลับไปในรูปแบบ JSON
header('Content-Type: application/json'); // เพิ่ม header สำหรับ JSON
echo json_encode($bookings);
?>
