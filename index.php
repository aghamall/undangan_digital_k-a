<?php
include 'koneksi.php';
session_start();

// --- 1. SET ZONA WAKTU ---
date_default_timezone_set('Asia/Jakarta');

$nama_tamu = $_GET['to'] ?? $_GET['name'] ?? 'Tamu Undangan';

// --- LOGIKA AJAX ---
if (isset($_POST['ajax_action'])) {
    if ($_POST['ajax_action'] == 'kirim_rsvp') {
        $nama = mysqli_real_escape_string($conn, $_POST['nama_rsvp']);
        $hadir = mysqli_real_escape_string($conn, $_POST['kehadiran']);
        mysqli_query($conn, "INSERT INTO rsvp (nama, kehadiran) VALUES ('$nama', '$hadir')");
        echo "Sukses";
        exit;
    }
    if ($_POST['ajax_action'] == 'kirim_wish') {
        $nama_w = mysqli_real_escape_string($conn, $_POST['nama_wish']);
        $pesan_w = mysqli_real_escape_string($conn, $_POST['pesan_wish']);
        if (!empty($nama_w) && !empty($pesan_w)) {
            mysqli_query($conn, "INSERT INTO wishes (nama, pesan) VALUES ('$nama_w', '$pesan_w')");
            echo "Sukses";
        }
        exit;
    }
}
$ambil_ucapan = mysqli_query($conn, "SELECT * FROM wishes ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alma & Kavindhi Wedding</title>
    <link href="https://fonts.googleapis.com/css2?family=Allura&family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-purple: #9b59b6;
            --accent-purple: #8e44ad;
            --text-white: #ffffff;
            --glass-purple: rgba(25, 12, 38, 0.7);
            --border-purple: rgba(155, 89, 182, 0.35);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #000;
            color: var(--text-white);
            overflow-x: hidden;
            padding-bottom: 110px;
        }

        body.lock {
            overflow: hidden;
            height: 100vh;
        }

        /* Video Background */
        #main-video-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -3;
            object-fit: cover;
            filter: brightness(0.25);
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -2;
            top: 0;
            pointer-events: none;
        }

        /* Cover Layer - Premium Re-design */
        #cover {
            position: fixed;
            inset: 0;
            background: linear-gradient(rgba(15, 6, 25, 0.75), rgba(0, 0, 0, 0.85)), url('DSC06272(1).jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10005;
            transition: 1.5s cubic-bezier(0.77, 0, 0.175, 1);
            text-align: center;
            padding: 30px;
        }

        #cover.open {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        .cover-title-container {
            animation: fadeInDown 1.2s ease-out;
        }

        #cover h2 {
            font-family: 'Allura', cursive;
            font-size: clamp(80px, 12vw, 130px);
            color: var(--text-white);
            line-height: 0.9;
            margin: 10px 0;
            text-shadow: 0 0 25px rgba(155, 89, 182, 0.7), 0 0 50px rgba(155, 89, 182, 0.3);
        }

        /* Box Undangan Tamu Terhormat */
        .tamu-badge-box {
            background: rgba(255, 255, 255, 0.04);
            padding: 25px 40px;
            border-radius: 24px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            margin: 40px 0;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6);
            max-width: 460px;
            width: 100%;
        }

        /* Hero Section - Visual Boost */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.35), rgba(15, 6, 25, 0.85)), url('DSC06355(1).JPG');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .hero h1 {
            font-family: 'Allura', cursive;
            font-size: clamp(75px, 14vw, 120px);
            color: var(--text-white);
            margin: 10px 0;
            text-shadow: 0 0 25px rgba(155, 89, 182, 0.6);
        }

        #countdown {
            font-weight: 500;
            font-size: 1.15rem;
            letter-spacing: 2px;
            background: linear-gradient(135px, rgba(155, 89, 182, 0.35), rgba(0, 0, 0, 0.65));
            padding: 14px 35px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--border-purple);
            margin: 20px 0;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
            display: inline-block;
        }

        /* Content Sections */
        .section {
            padding: 100px 20px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .card {
            max-width: 850px;
            margin: 0 auto;
            padding: 50px 30px;
            background: var(--glass-purple);
            border-radius: 35px;
            border: 1px solid var(--border-purple);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }

        .fade {
            opacity: 0;
            transform: translateY(40px);
            transition: 1.2s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .fade.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- STORY 3 COLUMN STYLE --- */
        .story-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1100px;
            margin: 40px auto 0;
            padding: 0 10px;
        }

        .story-item {
            text-align: center;
            transition: 0.4s;
            background: rgba(255, 255, 255, 0.03);
            padding: 25px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .story-item:hover {
            transform: translateY(-10px);
            background: rgba(155, 89, 182, 0.08);
            border-color: rgba(155, 89, 182, 0.3);
        }

        .story-item p {
            white-space: pre-line;
        }

        .story-img-wrapper {
            width: 100%;
            aspect-ratio: 4/5;
            border-radius: 120px 120px 30px 30px;
            overflow: hidden;
            border: 1px solid var(--border-purple);
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        }

        .story-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }

        .story-item:hover .story-img-wrapper img {
            transform: scale(1.05);
        }

        .story-date {
            display: inline-block;
            font-size: 11px;
            letter-spacing: 2px;
            color: var(--primary-purple);
            border-bottom: 1px solid var(--primary-purple);
            margin-bottom: 15px;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* --- ULTRA-DYNAMIC BENTO GRID (7 PHOTOS) --- */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 150px;
            gap: 15px;
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .grid-item {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            border: 1px solid var(--border-purple);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            background: #111;
        }

        .grid-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 1s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .grid-item:hover img {
            transform: scale(1.1);
        }

        /* --- VARIASI EKSTREM DESKTOP (Min 768px) --- */
        @media (min-width: 768px) {
            .bento-grid {
                grid-template-columns: repeat(4, 1fr);
                grid-auto-rows: 170px;
                gap: 20px;
            }

            .grid-item:nth-child(1) {
                grid-column: span 2;
                grid-row: span 2;
            }

            .grid-item:nth-child(2) {
                grid-column: span 1;
                grid-row: span 2;
            }

            .grid-item:nth-child(3) {
                grid-column: span 1;
                grid-row: span 1;
            }

            .grid-item:nth-child(4) {
                grid-column: span 3;
                grid-row: span 2;
            }

            .grid-item:nth-child(5) {
                grid-column: span 1;
                grid-row: span 3;
                margin-top: -190px;
            }

            .grid-item:nth-child(6) {
                grid-column: span 2;
                grid-row: span 1;
            }

            .grid-item:nth-child(7) {
                grid-column: span 1;
                grid-row: span 1;
            }
        }

        /* --- MOBILE TWEAKS --- */
        @media (max-width: 767px) {
            .grid-item:nth-child(1) {
                grid-column: span 2;
                grid-row: span 2;
            }

            .grid-item:nth-child(4) {
                grid-column: span 2;
            }

            .grid-item:nth-child(5) {
                grid-row: span 2;
            }
            .grid-item:nth-child(1) {
                grid-column: span 2;
            }
        }

        /* --- E-GIFT CARDS ENHANCED --- */
        .gift-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            align-items: center;
            margin-top: 30px;
            width: 100%;
        }

        .bank-card {
            width: 100%;
            max-width: 350px;
            height: 200px;
            background: linear-gradient(135px, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.03));
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 25px;
            text-align: left;
            position: relative;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            transition: 0.3s;
        }

        .bank-card:hover {
            border-color: var(--primary-purple);
            transform: translateY(-5px);
        }

        .bank-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 180px;
            height: 180px;
            background: var(--primary-purple);
            filter: blur(70px);
            opacity: 0.35;
            z-index: -1;
        }

        .chip {
            width: 45px;
            height: 32px;
            background: linear-gradient(135px, #f1c40f, #f39c12);
            border-radius: 6px;
            margin-bottom: 25px;
            position: relative;
        }

        .chip::after {
            content: '';
            position: absolute;
            inset: 4px;
            border: 1px solid rgba(0,0,0,0.15);
            border-radius: 4px;
        }

        .bank-name {
            position: absolute;
            top: 25px;
            right: 25px;
            font-weight: 700;
            letter-spacing: 2px;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .card-number {
            font-family: 'Courier New', Courier, monospace;
            font-size: 22px;
            letter-spacing: 3px;
            margin-bottom: 12px;
            display: block;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .card-holder {
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1.5px;
            opacity: 0.85;
            display: inline-block;
        }

        /* Buttons & Forms */
        .btn {
            padding: 14px 30px;
            border-radius: 50px;
            background: linear-gradient(90deg, var(--primary-purple), var(--accent-purple));
            color: #fff;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 5px;
            box-shadow: 0 10px 20px rgba(155, 89, 182, 0.2);
        }

        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 25px rgba(155, 89, 182, 0.4);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255,255,255,0.4) !important;
            box-shadow: none;
        }

        .btn-outline:hover {
            background: var(--primary-purple) !important;
            border-color: var(--primary-purple) !important;
        }

        .btn-copy {
            position: absolute;
            bottom: 20px;
            right: 25px;
            margin: 0;
            padding: 8px 16px;
            font-size: 10px;
            border-radius: 12px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border-radius: 15px;
            border: 1px solid var(--border-purple);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            outline: none;
            font-family: 'Poppins';
            transition: 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-purple);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(155, 89, 182, 0.2);
        }

        /* --- STYLING UCAPAN DENGAN AVATAR --- */
        .wish-item {
            display: flex;
            gap: 15px;
            background: rgba(255, 255, 255, 0.04);
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary-purple);
            text-align: left;
            animation: slideIn 0.5s ease;
            border-top: 1px solid rgba(255,255,255,0.02);
            border-right: 1px solid rgba(255,255,255,0.02);
            border-bottom: 1px solid rgba(255,255,255,0.02);
        }

        .avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135px, var(--primary-purple), var(--accent-purple));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            text-transform: uppercase;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .wish-content {
            flex-grow: 1;
        }

        /* Navbar & Music Control */
        #music-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10001;
            background: var(--primary-purple);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(155, 89, 182, 0.4);
            transition: 0.3s;
        }

        #music-toggle:hover {
            transform: scale(1.05);
        }

        .bars {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            height: 15px;
        }

        .bars span {
            width: 3px;
            background: #fff;
            animation: bounce 1s infinite alternate;
        }

        @keyframes bounce {
            from {
                height: 4px;
            }

            to {
                height: 15px;
            }
        }

        .music-off span {
            animation: none;
            height: 2px;
        }

        .iphone-navbar {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%) translateY(150px);
            width: 92%;
            max-width: 550px;
            background: rgba(15, 10, 20, 0.75);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 30px;
            display: flex;
            justify-content: space-around;
            padding: 12px 8px;
            z-index: 9999;
            transition: 1s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        }

        .iphone-navbar.active {
            transform: translateX(-50%) translateY(0);
        }

        .nav-item {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 9px;
            transition: 0.3s;
            flex: 1;
            font-weight: 500;
        }

        .nav-item i {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .nav-item.active-nav {
            color: var(--primary-purple);
            transform: translateY(-4px);
            text-shadow: 0 0 10px rgba(155, 89, 182, 0.4);
        }

        .mempelai-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
        }

        .mempelai-item {
            width: 100%;
            max-width: 350px;
            background: rgba(255, 255, 255, 0.02);
            padding: 30px 20px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .square-frame {
            width: 100%;
            aspect-ratio: 1/1;
            margin: 0 auto 20px;
            overflow: hidden;
            border-radius: 20px;
            border: 2px solid var(--primary-purple);
        }

        .square-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #container-ucapan::-webkit-scrollbar {
            width: 5px;
        }

        #container-ucapan::-webkit-scrollbar-track {
            background: transparent;
        }

        #container-ucapan::-webkit-scrollbar-thumb {
            background: var(--primary-purple);
            border-radius: 10px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-15px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="lock">

    <div id="particles-js"></div>
    <video autoplay muted loop playsinline id="main-video-bg">
        <source src="Video Undangan Alma & Kavindhi.mp4">
    </video>

    <nav class="iphone-navbar" id="navbar">
    <a href="#home" class="nav-item active-nav">
        <i class="fa-solid fa-house"></i>
        <span>Home</span>
    </a>

    <a href="#mempelai" class="nav-item">
        <i class="fa-solid fa-heart"></i>
        <span>Mempelai</span>
    </a>

    <a href="#story" class="nav-item">
        <i class="fa-solid fa-book-open"></i>
        <span>Story</span>
    </a>

    <a href="#acara" class="nav-item">
        <i class="fa-solid fa-calendar-day"></i>
        <span>Acara</span>
    </a>

    <a href="#galeri" class="nav-item">
        <i class="fa-solid fa-images"></i>
        <span>Galeri</span>
    </a>

    <a href="#rsvp" class="nav-item">
        <i class="fa-solid fa-clipboard-check"></i>
        <span>RSVP</span>
    </a>

    <a href="#ucapan" class="nav-item">
        <i class="fa-solid fa-message"></i>
        <span>Ucapan</span>
    </a>

    <a href="#gift" class="nav-item">
        <i class="fa-solid fa-gift"></i>
        <span>Gift</span>
    </a>
</nav>

    <div id="music-toggle" onclick="toggleMusic()">
        <div class="bars" id="music-bars"><span></span><span></span><span></span><span></span></div>
    </div>
    <audio id="music" loop>
        <source src="사탕 - SEVENTEEN.mp3">
    </audio>

    <div id="cover">
        <div class="cover-title-container">
            <p style="letter-spacing: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 300; opacity: 0.9;">THE WEDDING OF</p>
            <h2>Alma</h2>
            <h2>&</h2>
            <h2>Kavindhi</h2>
        </div>
        <div class="tamu-badge-box">
            <p style="font-size: 13px; opacity: 0.7; margin-bottom: 12px; letter-spacing: 1px;">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
            <h3 style="font-size: 32px; font-family: 'Playfair Display', serif; font-weight: 700; color: #fff;"><?= htmlspecialchars($nama_tamu) ?></h3>
        </div>
        <button class="btn" onclick="openInvite()">
            <i class="fa-solid fa-envelope-open-text"></i> Buka Undangan
        </button>
    </div>

    <section class="hero" id="home">
        <div class="fade show">
            <p style="letter-spacing: 6px; font-size: 14px; margin-bottom: 15px; font-weight: 400;">SABTU, 08 AGUSTUS 2026</p>
            <h1>Alma</h1>
            <h1>&</h1>
            <h1>Kavindhi</h1>
            <div id="countdown">Loading...</div>
            <br>
            <a href="https://calendar.google.com/calendar/event?action=TEMPLATE&tmeid=MnEzOTFkNGU3MGQ2NzZnMmFvbnRkZWxlYzYgYXprYWdhbWFsc3lhcmlmQG0&tmsrc=azkagamalsyarif%40gmail.com" target="_blank" class="btn btn-outline">
                <i class="fa-solid fa-calendar-plus"></i> Simpan Tanggal
            </a>
        </div>
    </section>

    <section class="section" id="mempelai">
        <div class="card fade">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 40px; letter-spacing: 2px;">MEMPELAI</h2>
            <div class="mempelai-container">
                <div class="mempelai-item">
                    <h3 style="font-family: 'Allura', cursive; font-size: 45px; color: var(--primary-purple); margin-bottom: 10px;">Alma Ghalizha, S.Psi</h3>
                    <p style="font-size: 14px; opacity: 0.8;">Putri dari <b>Bapak Sofyan Tsauri</b></p>
                    <p style="font-size: 14px; opacity: 0.8;"><b>& Ibu Nyayu Rogayah</b></p>
                </div>
                <div style="font-family: 'Allura', cursive; font-size: 35px; opacity: 0.6;">&</div>
                <div class="mempelai-item">
                    <h3 style="font-family: 'Allura', cursive; font-size: 45px; color: var(--primary-purple); margin-bottom: 10px;">Kavindhi Pradana Firmansyah, S.Psi</h3>
                    <p style="font-size: 14px; opacity: 0.8;">Putra dari <b>Bapak Andhika Firmansyah</b></p>
                    <p style="font-size: 14px; opacity: 0.8;"><b>& Ibu Vivi Suswanti</b></p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="story">
        <div class="fade">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 10px; letter-spacing: 2px;">LOVE STORY</h2>
            <p style="font-size: 14px; opacity: 0.7; margin-bottom: 40px;">Surat dari kami sebagai cerita perjalanan kami</p>
            
            <div class="story-grid">
                <!-- Cerita Kavindhi -->
                <div class="story-item">
                    <div class="story-img-wrapper"><img src="2.jpeg"></div>
                    <h4 style="font-family: 'Playfair Display'; font-size: 20px; color: var(--primary-purple); margin-bottom: 15px;">Surat dari Kavindhi untuk Alma</h4>
                    <p style="font-size: 13px; line-height: 1.7; opacity: 0.9;">Juli 2024 menjadi awal cerita yang tak pernah kami duga. Saat itu, aku melihat profil seorang wanita lucu dan menggemaskan di Bumble dan ketika melihat dia swipe right, aku langsung tertarik untuk membalasnya hingga kami match.

Awalnya komunikasi kami sempat terputus dua kali, pertama dia yang menghilang, lalu aku melakukan hal serupa. Namun takdir punya caranya sendiri... Sebuah mirror selfie lucunya di Instagram membuatku kembali ingin menghubunginya. Tak lama setelah itu, Alma justru mengambil langkah pertama mengajakku bertemu sepulang kerja, meski jarak kantor kami cukup jauh.

Pertemuan pertama kami berlangsung sederhana di HokBen Poris. Di sana, Alma mengucapkan kalimat yang hingga hari ini masih kuingat jelas.. "Setelah ini, kamu masih mau ketemu aku lagi nggak?" Tanpa ragu, aku menjawab, "Mau lah, kenapa gamau? Wkwk."

Perjalanan kami tidak selalu mudah, kami pernah dipisahkan jarak dan melewati hari-hari penuh rindu. Namun setiap langkah justru membawa kami semakin dekat. Pada Desember 2025, kami memohon restu kepada kedua orang tua, dan pada Februari 2026, kami resmi bertunangan.

Hari ini, aku bersyukur karena dari sekian banyak kemungkinan di dunia, aku dipertemukan dengan Alma. Sosok yang mengajarkanku bahwa cinta bukan hanya tentang menemukan seseorang yang sempurna, tetapi tentang menemukan seseorang yang membuatmu merasa pulang.

Terima kasih telah memilihku, Alma. Kini dan selamanya, aku ingin pulang kepadamu. ❤️</p>
                </div>
                
                <!-- Cerita Alma -->
                <div class="story-item">
                    <div class="story-img-wrapper"><img src="DSC06320.JPG"></div>
                    <h4 style="font-family: 'Playfair Display'; font-size: 20px; color: var(--primary-purple); margin-bottom: 15px;">Surat dari Alma untuk Kavindhi</h4>
                    <p style="font-size: 13px; line-height: 1.7; opacity: 0.9;">Dari Alma untuk Mas Kevin, yang awalnya hanya sebuah profil di Bumble pada akhir Juli 2024. Lucu rasanya mengingat kalau semua ini dimulai karena aku yang lebih dulu swipe. Percakapan kecil kita terasa nyaman sampai kita bertemu pertama kali di HokBen. Aku masih ingat ketika dengan sedikit ragu aku bertanya, “setelah ini, kamu mau gak ketemu aku lagi?” dan kamu menjawab, “mau kok.” Sejak hari itu, tanpa sadar kamu mulai menjadi bagian dari hariku.

Kita pernah menghabiskan akhir pekan dengan bertemu sesering mungkin, lalu belajar merindukan saat jarak memisahkan—7 bulan tanpa bertemu sama sekali, antara Tangerang Selatan-Surabaya selama 4 bulan dan Yogyakarta selama 3 bulan. Jarak tidak selalu mudah, tapi entah bagaimana, kita tetap memilih untuk bertahan dan percaya bahwa semua ini akan membawa kita ke tujuan yang sama.

Juli 2025, jarak itu selesai saat kamu bertugas di Jakarta. Desember 2025 menjadi langkah berani kita meminta restu, lalu Februari 2026 keluarga kita dipertemukan dalam sebuah lamaran. 
Kemudian hari ini, aku bersyukur karena dari semua kebetulan kecil itu, akhirnya aku menemukan rumah—di kamu.

Terima kasih sudah berkata “mau kok” di hari itu. Karena ternyata, jawaban sederhana itu membawa kita sampai di sini.</p>
                </div>
            </div>

            <!-- Gambar ketiga ditaruh di luar grid agar tidak merusak layout -->
            <div style="text-align: center; margin-top: 40px; padding: 0 10px;">
                <div class="story-img-wrapper" style="max-width: 500px; display: inline-block; border-radius: 30px;">
                    <img src="1.jpg" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="acara">
        <div class="card fade">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 35px; letter-spacing: 2px;">WAKTU & LOKASI</h2>
            <div style="margin-bottom: 25px;">
                <h3 style="color: var(--primary-purple); font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 5px;">AKAD NIKAH</h3>
                <p style="font-size: 15px; letter-spacing: 1px;">09.00 WIB</p>
            </div>
            <div style="margin-bottom: 30px;">
                <h3 style="color: var(--primary-purple); font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 5px;">RESEPSI</h3>
                <p style="font-size: 15px; letter-spacing: 1px;">10.00 - 12.00 WIB</p>
            </div>
            <h3 style="color: var(--primary-purple); font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 10px;">LOKASI</h3>
            <p style="font-size: 15px; line-height: 1.6; max-width: 500px; margin: 0 auto 20px;"><b>Halaman Tujuh</b><br>Jalan Raya, Pd. Cabe Udik, South City Selatan, Kota Tangerang Selatan, Banten 15418</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4621.1077702449675!2d106.76476991122345!3d-6.345439762056648!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1d02f90ff77%3A0xa0fc2d2b89641946!2sHalaman%20Tujuh!5e1!3m2!1sid!2sid!4v1775080817779!5m2!1sid!2sid" width="100%" height="250" style="border:0; border-radius:20px; margin-top:20px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);" loading="lazy"></iframe>
            <a href="https://maps.app.goo.gl/E6SiAMBETUf96veXA" target="_blank" class="btn" style="width: 100%; margin-top: 20px;">
                <i class="fa-solid fa-location-dot"></i> Lihat Rute Navigasi
            </a>
        </div>
    </section>

    <section class="section" id="galeri" style="padding: 100px 10px;">
        <div class="fade" style="margin-bottom: 40px;">
            <h2 style="font-family: 'Playfair Display', serif; letter-spacing: 4px; text-transform: uppercase; font-size: 2.2rem;">
                Our Gallery
            </h2>
            <div style="width: 60px; height: 2px; background: var(--primary-purple); margin: 15px auto;"></div>
        </div>

        <div class="bento-grid">
            <div class="grid-item"><img src="DSC06238(1).jpg"></div>
            <div class="grid-item"><img src="DSC06355(1).jpg"></div>
            <div class="grid-item"><img src="DSC06115.JPG"></div>
            <div class="grid-item"><img src="DSC06260.jpg"></div>
            <div class="grid-item"><img src="DSC06649 (1).JPG"></div>
            <div class="grid-item"><img src="DSC06704(1).JPG"></div>
            <div class="grid-item"><img src="DSC06691.JPG"></div>
        </div>
    </section>

    <section class="section" id="rsvp">
        <div class="card fade">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 25px; letter-spacing: 2px;">RSVP</h2>
            <form id="formRSVP">
                <input type="text" id="nama_rsvp" value="<?= htmlspecialchars($nama_tamu) ?>" readonly style="text-align: center; font-weight: 600;">

                <select id="kehadiran" name="kehadiran" style="cursor: pointer; text-align-last: center;">
                    <option value="" disabled selected>— Pilih Kehadiran —</option>
                    <option value="Hadir" style="background-color: #150619; color: white;">Hadir</option>
                    <option value="Tidak Hadir" style="background-color: #150619; color: white;">Berhalangan</option>
                </select>

                <button type="button" onclick="submitRSVP()" class="btn" style="width: 100%; margin-top: 15px;">
                    Konfirmasi
                </button>
            </form>
        </div>
    </section>

    <section class="section" id="ucapan">
        <div class="card fade">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 25px; letter-spacing: 2px;">UCAPAN & DOA</h2>
            <form id="formWish">
                <textarea id="pesan_wish" placeholder="Tulis doa dan ucapan manis Anda di sini..." rows="4" required></textarea>
                <button type="button" onclick="submitWish()" class="btn" style="width: 100%; margin-top: 5px;">Kirim Ucapan</button>
            </form>
            <div id="container-ucapan" style="margin-top: 35px; max-height: 450px; overflow-y: auto; padding-right: 8px;">
                <?php while ($row = mysqli_fetch_assoc($ambil_ucapan)):
                    $inisial = strtoupper(substr($row['nama'], 0, 1));
                    $tgl_display = date('d M, H:i', strtotime($row['created_at']));
                ?>
                    <div class="wish-item">
                        <div class="avatar"><?= $inisial ?></div>
                        <div class="wish-content">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <p style="font-weight: 600; font-size: 14px; color: var(--primary-purple); margin:0;"><?= htmlspecialchars($row['nama']) ?></p>
                                <small style="font-size: 10px; opacity: 0.5;"><i class="fa-regular fa-clock"></i> <?= $tgl_display ?></small>
                            </div>
                            <p style="font-size: 12px; line-height: 1.6; opacity: 0.9; margin: 0;"><?= nl2br(htmlspecialchars($row['pesan'])) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="section" id="gift">
        <div class="card fade">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 15px; letter-spacing: 2px;">E-GIFT</h2>
            <p style="font-size: 13px; opacity: 0.7; margin-bottom: 20px;">Doa restu Anda adalah karunia terindah, namun jika ingin memberikan tanda kasih, Anda dapat melalui rekening di bawah ini:</p>
            
            <div class="gift-container">
                <div class="bank-card">
                    <div class="bank-name">BCA</div>
                    <div class="chip"></div>
                    <span class="card-number" id="rek1">8010924961</span>
                    <div class="card-holder">A/N ALMA GHALIZHA</div>
                    <button class="btn btn-copy" onclick="copyText('rek1')">Salin</button>
                </div>
                
                <div class="bank-card">
                    <div class="bank-name">BCA</div>
                    <div class="chip"></div>
                    <span class="card-number" id="rek2">5075188282</span>
                    <div class="card-holder">A/N KAVINDHI PRADANA F</div>
                    <button class="btn btn-copy" onclick="copyText('rek2')">Salin</button>
                </div>
                
                <div class="bank-card">
                    <div class="bank-name">BRI</div>
                    <div class="chip"></div>
                    <span class="card-number" id="rek3">057601017940501</span>
                    <div class="card-holder">A/N KAVINDHI PRADANA F</div>
                    <button class="btn btn-copy" onclick="copyText('rek3')">Salin</button>
                </div>
            </div>
        </div>
    </section>

    <footer style="padding: 100px 20px 40px; text-align: center; position: relative; z-index: 1;">
        <div style="font-family: 'Allura', cursive; font-size: 36px; text-shadow: 0 0 10px rgba(155, 89, 182, 0.3);">Alma & Kavindhi</div>
        <p style="font-size: 10px; opacity: 0.4; margin-top: 15px; letter-spacing: 1px;">&copy; 2026 Digital Wedding Invitation by @aghamall</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 45
                },
                "color": {
                    "value": "#ffffff"
                },
                "opacity": {
                    "value": 0.25
                },
                "size": {
                    "value": 2
                },
                "move": {
                    "enable": true,
                    "speed": 1.0,
                    "direction": "top"
                }
            }
        });

        function openInvite() {
            document.getElementById('cover').classList.add('open');
            document.body.classList.remove('lock');
            document.getElementById('navbar').classList.add('active');
            const m = document.getElementById('music');
            m.play().catch(() => {});
        }

        function toggleMusic() {
            const m = document.getElementById('music');
            const b = document.getElementById('music-bars');
            m.paused ? (m.play(), b.classList.remove('music-off')) : (m.pause(), b.classList.add('music-off'));
        }

        const navItems = document.querySelectorAll('.nav-item');

        function setActive(targetId) {
            navItems.forEach(nav => {
                const href = nav.getAttribute('href').replace('#', '');
                nav.classList.toggle('active-nav', href === targetId);
            });
        }

        navItems.forEach(item => {
            item.addEventListener('click', function() {
                navItems.forEach(n => n.classList.remove('active-nav'));
                this.classList.add('active-nav');
            });
        });

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.querySelectorAll('.fade').forEach(f => f.classList.add('show'));
                    if (entry.target.classList.contains('fade')) entry.target.classList.add('show');

                    const id = entry.target.getAttribute('id');
                    if (id) setActive(id);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: "0px"
        });

        document.querySelectorAll('section, .fade').forEach(s => observer.observe(s));

        function copyText(id) {
            navigator.clipboard.writeText(document.getElementById(id).innerText);
            alert("Berhasil disalin!");
        }

        function submitRSVP() {
            const formData = new FormData();
            formData.append('ajax_action', 'kirim_rsvp');
            formData.append('nama_rsvp', document.getElementById('nama_rsvp').value);
            formData.append('kehadiran', document.getElementById('kehadiran').value);
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            }).then(() => alert('RSVP Terkirim!'));
        }

        function submitWish() {
            const nama = "<?= htmlspecialchars($nama_tamu) ?>";
            const pesan = document.getElementById('pesan_wish').value;
            const inisial = nama.charAt(0).toUpperCase();
            if (!pesan.trim()) return;

            const formData = new FormData();
            formData.append('ajax_action', 'kirim_wish');
            formData.append('nama_wish', nama);
            formData.append('pesan_wish', pesan);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            }).then(() => {
                const html = `
                    <div class="wish-item">
                        <div class="avatar">${inisial}</div>
                        <div class="wish-content">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <p style="font-weight: 600; font-size: 14px; color: var(--primary-purple); margin:0;">${nama}</p>
                                <small style="font-size: 10px; opacity: 0.5;"><i class="fa-regular fa-clock"></i> Baru saja</small>
                            </div>
                            <p style="font-size: 12px; line-height: 1.5;">${pesan.replace(/\n/g, '<br>')}</p>
                        </div>
                    </div>`;
                document.getElementById('container-ucapan').insertAdjacentHTML('afterbegin', html);
                document.getElementById('pesan_wish').value = "";
            });
        }

        const targetDate = new Date("August 08, 2026 11:00:00").getTime();
        setInterval(() => {
            const now = new Date().getTime();
            const d = targetDate - now;
            if (d < 0) {
                document.getElementById('countdown').innerHTML = "Acara Sedang Berlangsung";
                return;
            }
            const days = Math.floor(d / 86400000);
            const hours = Math.floor((d % 86400000) / 3600000);
            const mins = Math.floor((d % 3600000) / 60000);
            const secs = Math.floor((d % 60000) / 1000);
            document.getElementById('countdown').innerHTML = `${days} Hari : ${hours} Jam : ${mins} Menit : ${secs} Detik`;
        }, 1000);
    </script>
</body>

</html>