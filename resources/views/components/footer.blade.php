<footer class="bg-soft-primary pt-5">

  <div class="container pb-12 text-center">
    <div class="row mt-n10 mt-lg-0">
      <div class="col-xl-10 mx-auto">
        <!--/.row -->
        <p class="text-center">© {{date('Y')}} <a href="https://www.linkedin.com/in/ahmed-marei-7042a9154/" target="_blank">Ahmed Marei</a>. جميع الحقوق محفوظة.</p>
        <nav class="nav social justify-content-center">
          @if($settings->twitter_link!=null)
          <a href="{{$settings->twitter_link}}"><i class="fab fa-twitter"></i></a>
          @endif
          @if($settings->facebook_link!=null)
          <a href="{{$settings->facebook_link}}"><i class="fab fa-facebook-f"></i></a>
          @endif
          @if($settings->instagram_link!=null)
          <a href="{{$settings->instagram_link}}"><i class="fab fa-instagram"></i></a>
          @endif
          @if($settings->youtube_link!=null)
          <a href="{{$settings->youtube_link}}"><i class="fab fa-youtube"></i></a>
          @endif
        </nav>
        <!-- /.social -->
      </div>
      <!-- /column -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</footer>
