 <!-- jQuery -->
 <script src="<?php echo e(URL::asset('/build/js/jquery-3.7.1.min.js')); ?>"></script>

 <!-- Feather Icon JS -->
 <script src="<?php echo e(URL::asset('/build/js/feather.min.js')); ?>"></script>

 <!-- Slimscroll JS -->
 <script src="<?php echo e(URL::asset('/build/js/jquery.slimscroll.min.js')); ?>"></script>

 <!-- Bootstrap Core JS -->
 <script src="<?php echo e(URL::asset('/build/js/bootstrap.bundle.min.js')); ?>"></script>

 <!-- Chart JS -->
 <script src="<?php echo e(URL::asset('/build/plugins/apexchart/apexcharts.min.js')); ?>"></script>
 <script src="<?php echo e(URL::asset('/build/plugins/apexchart/chart-data.js')); ?>"></script>

 <!-- Sweetalert 2 -->
 <script src="<?php echo e(URL::asset('/build/plugins/sweetalert/sweetalert2.all.min.js')); ?>"></script>
 <script src="<?php echo e(URL::asset('/build/plugins/sweetalert/sweetalerts.min.js')); ?>"></script>

 <!-- Swiper JS -->
 <script src="<?php echo e(URL::asset('/build/plugins/swiper/swiper.min.js')); ?>"></script>

 <!-- FancyBox JS -->
 <script src="<?php echo e(URL::asset('/build/plugins/fancybox/jquery.fancybox.min.js')); ?>"></script>

 <!-- Select2 JS -->
 <script src="<?php echo e(URL::asset('/build/plugins/select2/js/select2.min.js')); ?>"></script>

 <!-- Datetimepicker JS -->
 <script src="<?php echo e(URL::asset('/build/js/moment.min.js')); ?>"></script>
 <script src="<?php echo e(URL::asset('/build/js/bootstrap-datetimepicker.min.js')); ?>"></script>
 <script src="<?php echo e(URL::asset('/build/plugins/daterangepicker/daterangepicker.js')); ?>"></script>

 <?php if(Route::is(['todo'])): ?>
     <!-- Datetimepicker CSS -->
     <script src="<?php echo e(URL::asset('/build/plugins/moment/moment.min.js')); ?>"></script>
 <?php endif; ?>

 <!-- Bootstrap Tagsinput JS -->
 <script src="<?php echo e(URL::asset('/build/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')); ?>"></script>

 <!-- Datatable JS -->
 <script src="<?php echo e(URL::asset('/build/js/jquery.dataTables.min.js')); ?>"></script>
 <script src="<?php echo e(URL::asset('/build/js/dataTables.bootstrap5.min.js')); ?>"></script>

 <!-- Summernote JS -->
 <script src="<?php echo e(URL::asset('/build/plugins/summernote/summernote-bs4.min.js')); ?>"></script>

 <!-- Mobile Input -->
 <script src="<?php echo e(URL::asset('/build/plugins/intltelinput/js/intlTelInput.js')); ?>"></script>

 <script src="<?php echo e(URL::asset('/build/js/plyr-js.js')); ?>"></script>

 <!-- Owl Carousel -->
 <script src="<?php echo e(URL::asset('/build/js/owl.carousel.min.js')); ?>"></script>

 <!-- Sticky-sidebar -->
 <script src="<?php echo e(URL::asset('/build/plugins/theia-sticky-sidebar/ResizeSensor.js')); ?>"></script>
 <script src="<?php echo e(URL::asset('/build/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')); ?>"></script>

 <?php if(Route::is(['sales-dashboard'])): ?>
     <!-- Map JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/jvectormap/jquery-jvectormap-2.0.5.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/jvectormap/jquery-jvectormap-world-mill.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/jvectormap/jquery-jvectormap-ru-mill.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/jvectormap/jquery-jvectormap-us-aea.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/jvectormap/jquery-jvectormap-uk_countries-mill.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/jvectormap/jquery-jvectormap-in-mill.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/js/jvectormap.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is('calendar')): ?>
     <!-- Full Calendar JS -->
     <script src="<?php echo e(URL::asset('/build/js/jquery-ui.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/fullcalendar/fullcalendar.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/fullcalendar/jquery.fullcalendar.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-clipboard'])): ?>
     <!-- Clipboard JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/clipboard/clipboard.min.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-drag-drop'])): ?>
     <!-- Dragula JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/dragula/js/dragula.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/dragula/js/drag-drop.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/dragula/js/draggable-cards.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-rating'])): ?>
     <!-- Rater JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/rater-js/index.js')); ?>"></script>
     <!-- Internal Ratings JS -->
     <script src="<?php echo e(URL::asset('/build/js/ratings.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-counter'])): ?>
     <!-- Stickynote JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/countup/jquery.counterup.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/countup/jquery.waypoints.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/countup/jquery.missofis-countdown.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-text-editor'])): ?>
     <!-- Summernote JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/summernote/summernote-bs4.min.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-rangeslider'])): ?>
     <!-- Rangeslider JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/ion-rangeslider/js/custom-rangeslider.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['form-mask'])): ?>
     <!-- Mask JS -->
     <script src="<?php echo e(URL::asset('/build/js/jquery.maskedinput.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/js/mask.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-scrollbar'])): ?>
     <!-- Plyr JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/scrollbar/scrollbar.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/scrollbar/custom-scroll.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-stickynote'])): ?>
     <!-- Stickynote JS -->
     <script src="<?php echo e(URL::asset('/build/js/jquery-ui.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/stickynote/sticky.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-toasts'])): ?>
     <!-- Mask JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/toastr/toastr.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/toastr/toastr.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['ui-lightbox'])): ?>
     <script src="<?php echo e(URL::asset('/build/plugins/lightbox/glightbox.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/lightbox/lightbox.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['chart-c3'])): ?>
     <!-- Chart JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/c3-chart/d3.v5.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/c3-chart/c3.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/c3-chart/chart-data.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['chart-flot'])): ?>
     <!-- Chart JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/flot/jquery.flot.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/flot/jquery.flot.fillbetween.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/flot/jquery.flot.pie.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/flot/chart-data.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['lightbox'])): ?>
     <!-- lightbox JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/lightbox/glightbox.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/lightbox/lightbox.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['chart-js'])): ?>
     <!-- Chart JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/chartjs/chart.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/chartjs/chart-data.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['chart-morris'])): ?>
     <!-- Chart JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/morris/raphael-min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/morris/morris.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/morris/chart-data.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['chart-peity'])): ?>
     <!-- Chart JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/peity/jquery.peity.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/peity/chart-data.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['form-select2'])): ?>
     <script src="<?php echo e(URL::asset('/build/js/custom-select2.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['form-fileupload'])): ?>
     <!-- Fileupload JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/fileupload/fileupload.min.js')); ?>"></script>
 <?php endif; ?>

 <?php if(Route::is(['form-wizard'])): ?>
     <!-- Wizard JS -->
     <script src="<?php echo e(URL::asset('/build/plugins/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/twitter-bootstrap-wizard/prettify.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/build/plugins/twitter-bootstrap-wizard/form-wizard.js')); ?>"></script>
 <?php endif; ?>

<!-- Custom JS -->
<script src="<?php echo e(URL::asset('/build/js/theme-script.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/build/js/script.js')); ?>"></script>

<!-- Page Specific Scripts -->
<?php echo $__env->yieldPushContent('scripts'); ?>
<?php /**PATH C:\laragon\www\laundry3\resources\views/layout/partials/footer-scripts.blade.php ENDPATH**/ ?>