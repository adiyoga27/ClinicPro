<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ClinicPro — Sistem Rekam Medis Elektronik Modern</title>
    <meta name="description"
        content="Platform SaaS EMR modern untuk klinik Indonesia. Rekam medis digital, integrasi Satu Sehat, manajemen antrian, dan pembayaran online.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface-950 text-surface-100 font-sans antialiased">
    {{ $slot }}
</body>

</html>