@extends('templ.head')
@section('tmplt-contnt')
<main id="main">
    <!-- ======= About Us Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>National Bank History</h2>
        </div>

      </div>
    </section><!-- End About Us Section -->

    <!-- ======= About Section ======= -->
    <section class="about" data-aos="fade-up">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <img src="{{asset('large logo.png')}}" class="img-fluid" alt="">
          </div>
          <div class="col-lg-8 pt-6 pt-lg-0">
            <ul>
                <li class="d-flex"><i class="bi bi-check2-circle"></i><p> The National Bank for Scientific Laboratories and Equipment (NBSLE) has been established by the decision of Supreme
                    Council of (SCU) in December 15, 2014. NBSLE aims to avail and share of the scientific laboratories and equipment inside the Egyptian Universities
                    in a comprehensive national bank to support the researchers at local and regional levels.</p></li>
                <li class="d-flex"><i class="bi bi-check2-circle"></i><p> Prof.Dr.Tareef Shawki ( Vice President of Beni Suef University for Post Graduates Studies and Researches) has been commissioned to be the president of committee to work on the establishment of the National Bank for laboratories and scientific equipment in the Egyptian universities.</p></li>
                <li class="d-flex"><i class="bi bi-check2-circle"></i><p> The committee consisted of a number of specialists from the Electronic and Knowledge Service Center (EKSC) at the SCU in addition to a professor from each Egyptian university.</p></li>
                <li class="d-flex"><i class="bi bi-check2-circle"></i><p> SCU has agreed on February 28, 2015 to establish a unit in each university under the name of the scientific laboratories and equipment unit, provided that these units serve as links between the national bank and the Egyptian universities.</p></li>
{{--              <li><i class="bi bi-check2-circle"></i>The Supreme Council of Universities issued a decision in 15/12/2014 commissioned by Prof. Dr. / Tarik Shawki Vice President of Beni Suef University for Graduate Studies and Research, headed by a committee to work on the establishment of the National Bank for laboratories and scientific instruments at Egyptian universities, a new subsidiary body of the Supreme Council of Universities</li>--}}
{{--              <li><i class="bi bi-check2-circle"></i>The committee consisted of a number of specialists from the Electronic and Knowledge Service Center at the Supreme Council of Universities, in addition to a professor from each Egyptian university to represent it at the bank.</li>--}}
{{--              <li><i class="bi bi-check2-circle"></i>Work began to provide the bank with data for laboratories and operating devices located in the faculties and institutes of Alexandria University. Taha Ibrahim Zaghloul held several workshops in which the bank was introduced and the objectives and method of work were explained.</li>--}}
{{--              <li><i class="bi bi-check2-circle"></i>The bank has created a page on the international information network and the site in the link on the side page has the ability to browse and search for devices as well as their characteristics and location, as well as the faculty member responsible for the laboratory in which the device is located.</li>--}}
{{--              <li><i class="bi bi-check2-circle"></i>The Supreme Council of Universities agreed on February 28, 2015 to establish a unit in each university under the name of the Laboratories and Scientific Equipment Unit, provided that these units serve as links between the Bank and Egyptian universities.</li>--}}
{{--              <li><i class="bi bi-check2-circle"></i>The bank's bylaw has been prepared, and the bank is expected to contribute a great deal to serving all researchers and graduate students in Egyptian universities.</li>--}}
            </ul>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->

  </main><!-- End #main -->



  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

@endsection
