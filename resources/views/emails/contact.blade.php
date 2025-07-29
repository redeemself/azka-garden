@component('mail::message')
# Pesan dari Kontak Website Azka Garden

Halo Tim Azka Garden,

Anda menerima pesan baru melalui form kontak website.

---

**Nama Pengirim:**
{{ $contact['name'] }}

**Email Pengirim:**
{{ $contact['email'] }}

@if(!empty($contact['phone']))
**Nomor Telepon:**
{{ $contact['phone'] }}
@endif

**Isi Pesan:**
> {{ $contact['message'] }}

---

Kami mengucapkan terima kasih atas kunjungan dan kepercayaan Anda untuk menghubungi Azka Garden.
Kami akan menindaklanjuti pesan ini sesegera mungkin.

Salam hangat,<br>
**Azka Garden**

@endcomponent
