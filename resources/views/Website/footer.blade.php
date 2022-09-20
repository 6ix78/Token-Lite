 <!-- footer section start -->
 <footer class="xs-footer-sec">
     <div class="container">
         <div class="footer-area">
             <div class="row">
                 <div class="col-lg-4 col-md-12">
                     <div class="footer-widget">
                         <div class="footer-logo">
                             <a href="https://osis.world/login">
                                 <img src="website/assets/images/logo1.png" alt="">
                             </a>
                         </div>
                         <p>
                             OSIS is brought to you by Apotheosis.
                         </p>
                     </div>
                 </div><!-- col end -->
                 <div class="col-lg-4 col-md-12">
                     <div class="footer-widget">
                         <h4 class="widget-title">Company</h4> 
                         <ul>
                             <li><a href="/about-us"> About Us</a></li>
                             <li><a href="https://osis.world/vsl.html"> What is OSIS?</a></li>
                             <li><a href="https://discord.gg/osis"> Join the Community</a></li>
                             <li><a href="https://osis.world/login"> Get OSIS</a></li>
                         </ul>
                     </div>
                 </div><!-- col end -->
                 <div class="col-lg-4 col-md-12">
                     <div class="footer-widget support">
                         <h4 class="widget-title">Support</h4>
                         <ul>
                             <li><a href="https://apotheosis.zendesk.com"> Contact Us</a></li>
                             <li><a href="/terms-and-conditions/"> Terms & Conditions</a></li>
                             <li><a href="/privacy-policy/"> Privacy Policy</a></li>
                         </ul>
                     </div>
                 </div><!-- col end -->
             </div><!-- row end -->
         </div><!-- footer area end -->

         <!-- copyright area -->
         <div class="footer-copyright">
             <p>
                 ©Copyright 2022 | Apotheosis | All Rights Reserved
             </p>
         </div>

     </div><!-- container end -->
 </footer>
 <!-- footer section end -->

 <!--back to top start-->
 <div class="BackTo">
     <a href="#" class="fa fa-angle-up" aria-hidden="true"></a>
 </div>
 <!--back to top end-->


 <script src="website/assets/js/jquery-3.2.1.min.js"></script>
 <script src="website/assets/js/bootstrap.min.js"></script>
 <script src="website/assets/js/jquery.magnific-popup.min.js"></script>
 <script src="website/assets/js/owl.carousel.min.js"></script>
 <script src="website/assets/js/navigation.js"></script>
 <script src="website/assets/js/jquery.appear.min.js"></script>
 <script src="website/assets/js/wow.min.js"></script>
 <script src="website/assets/js/chart.min.js"></script>
 <script src="website/assets/js/particles.min.js"></script>
 <script src="website/assets/js/smooth-scroling.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/venobox/1.9.3/venobox.js"></script>
 <!-- <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script> -->
 <script src="website/assets/js/main.js"></script>

 <script>
     $(document).ready(function() {
         $('.venobox').venobox();
     });
 </script>
 <!-- <script type="text/javascript">
     function googleTranslateElementInit() {
         new google.translate.TranslateElement({
             pageLanguage: 'en',
            //  layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
             autoDisplay: false
         }, 'google_translate_element');
     }
 </script>
 <script type="text/javascript">
     $('.translation-links a').click(function() {
         var lang = $(this).data('lang');
        //  console.log(lang);
         var $frame = $('.goog-te-menu-frame:first');
        //  console.log($frame);
        //  if (!$frame.size()) {
        //      alert("Error: Could not find Google translate frame.");
        //      return false;
        //  }
        console.log($frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')'));
         $frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')').get(0).click();
         var removePopup = document.getElementById('goog-gt-tt');
            removePopup.parentNode.removeChild(removePopup);
         return false;
     });
 </script> -->

 <!-- Use CSS to replace link text with flag icons -->

 <!-- Code provided by Google -->
 <div id="google_translate_element"></div>
 <script type="text/javascript">
     function googleTranslateElementInit() {
         new google.translate.TranslateElement({
             pageLanguage: 'en',
             layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
             autoDisplay: false
         }, 'google_translate_element');
     }
 </script>
 <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>

 <!-- Flag click handler -->
 <script type="text/javascript">
     $('.translation-links a').click(function() {
         var lang = $(this).data('lang');
         var $frame = $('.goog-te-menu-frame:first');
         // if (!$frame.size()) {
         // alert("Error: Could not find Google translate frame.");
         // return false;
         // }
         $frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')').get(0).click();
         var removePopup = document.getElementById('goog-gt-tt');
         if(removePopup){
            removePopup.parentNode.removeChild(removePopup);
         }
         return false;
     });
 </script>
 </body>

 </html>