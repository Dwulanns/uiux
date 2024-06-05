<!DOCTYPE html>
<html>

<head>
    @include('home.css')
    <style type="text/css">
        .detail-box {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .full-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="hero_area">
        @include('home.header')
    </div>

    <section class="why_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>
                    Sertifikat Produk Kami 
                </h2>
                <p>Selamat datang di Nirwana, tempat di mana kelezatan laut bertemu dengan jaminan kualitas tertinggi! Kami bangga menjadi destinasi pilihan bagi para penggemar ikan dan makanan laut yang mencari produk berkualitas tinggi dengan sertifikasi lengkap.</p>
                <p>Di Nirwana, kami menawarkan berbagai macam produk ikan segar dan olahan ikan yang tidak hanya lezat tetapi juga dipastikan kualitasnya dengan sertifikasi resmi. Dari ikan segar seperti bandeng dan kutuk hingga berbagai olahan ikan seperti abon dan bakso ikan, setiap produk kami telah melewati standar ketat BPOM (Badan Pengawas Obat dan Makanan) untuk memastikan keamanan dan kebersihan.</p>
                <p>Kami juga bangga memiliki sertifikasi halal untuk semua produk kami, sehingga pelanggan kami yang memperhatikan aspek keagamaan dapat membeli dan menikmati produk kami dengan keyakinan yang sepenuhnya. Tidak hanya itu, sertifikasi SKP (Sertifikat Kelayakan Produksi) juga kami miliki, menegaskan komitmen kami untuk menjaga standar produksi yang tinggi dan memenuhi persyaratan yang ditetapkan oleh otoritas terkait.</p>
                <p>Jadi, kunjungi Nirwana hari ini dan temukan pengalaman berbelanja yang tak tertandingi di dunia ikan-ikanan yang aman, halal, dan berkualitas! Dan berikut adalah sertifikatnya:</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="box">
                        <div class="detail-box">
                            <p>
                                Surat BPOM
                            </p>
                        </div>
                        <div class="img-box">
                            <img src="images/sertif3.jpg" alt="Image description" class="full-image">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="detail-box">
                            <p>
                                Sertifikat Kelayakan pangan
                            </p>
                        </div>
                        <div class="img-box">
                            <img src="images/sertif2.jpg" alt="Image description" class="full-image">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="detail-box">
                            <p>
                                Sertifikat Halal
                            </p>
                        </div>
                        <div class="img-box">
                            <img src="images/sertif1.jpg" alt="Image description" class="full-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- info section -->

    @include('home.footer')

    <!-- end info section -->
</body>

</html>
