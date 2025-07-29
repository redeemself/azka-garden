@component('mail::message')
# Pesan Baru Dari Website Azka Garden

**Nama:** {{ $name }}  
**Email:** {{ $email }}  
**Nomor HP:** {{ $phone }}

---

**Pesan:**  
{!! nl2br(e($messageText)) !!}

@endcomponent