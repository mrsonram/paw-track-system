<!DOCTYPE html>
<html>
<body style="font-family: sans-serif;">
    <h2>มีข้อความติดต่อใหม่จากเว็บไซต์ PawTrack</h2>
    <p><strong>ชื่อ:</strong> {{ $contact->name }}</p>
    <p><strong>อีเมล:</strong> {{ $contact->email }}</p>
    <p><strong>เรื่อง:</strong> {{ $contact->title }}</p>
    <p><strong>ข้อความ:</strong></p>
    <p>{{ $contact->message }}</p>
    <hr>
    <p><a href="{{ url('/contact/' . $contact->id) }}">ดูข้อความนี้ในระบบจัดการ</a></p>
</body>
</html>
