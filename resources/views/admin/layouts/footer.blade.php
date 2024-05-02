<!-- footer start-->
<footer class="footer">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 footer-copyright">
        <p class="mb-0">{{__('admin_main.copyright')}} <span data-toggle="year-copy"></span> {{__('admin_main.copyright_context')}}</p>
      </div>
      <div class="col-md-6">
        <p class="pull-right mb-0">CMS v1.0</p>
      </div>
    </div>
  </div>
</footer>
</div>
</div>
<!-- latest jquery-->
<script src="{{ asset('admin-asset/js/jquery-3.5.1.min.js') }}"></script>
<!-- feather icon js-->
<script src="{{ asset('admin-asset/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/icons/feather-icon/feather-icon.js') }}"></script>
<!-- Sidebar jquery-->
<script src="{{ asset('admin-asset/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('admin-asset/js/config.js') }}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('admin-asset/js/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/bootstrap/bootstrap.min.js') }}"></script>
<!-- Plugins JS Start-->
<script src="{{ asset('admin-asset/js/dashboard/default.js') }}"></script>
<script src="{{ asset('admin-asset/js/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/sweet-alert/app.js') }}"></script>
<script src="{{ asset('admin-asset/js/tooltip-init.js') }}"></script>
<script src="{{ asset('admin-asset/js/owlcarousel/owl.carousel.js') }}"></script>
<script src="{{ asset('admin-asset/js/product-list-custom.js') }}"></script>
<script src="{{ asset('admin-asset/js/form-validation-custom.js') }}"></script>
<script src="{{ asset('admin-asset/js/cropperjs/cropper.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ asset('admin-asset/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ asset('admin-asset/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
<script src="{{ asset('admin-asset/custom.js') }}"></script>
<script src="{{ asset('admin-asset/js/sortable.js') }}"></script>
<script src="{{ asset('admin-asset/js/jquery.nestable.js') }}"></script>
<script src="{{ asset('admin-asset/js/photoswipe/photoswipe.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/photoswipe/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/photoswipe/photoswipe.js') }}"></script>
<script src="{{ asset('admin-asset/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/select2/select2-custom.js') }}"></script>
<!-- Plugins JS Ends-->
<!-- Datatable Plugins Start-->
<script src="{{ asset('admin-asset/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/jszip.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.autoFill.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.select.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.colReorder.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.rowReorder.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/dataTables.scroller.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/datatable/datatable-extension/custom.js') }}"></script>
<!-- Datatable Plugins End-->
<!-- Theme js-->
<script src="{{ asset('admin-asset/js/script.js') }}"></script>
<!-- Theme Preference -->
@if (session('theme_dark'))
<script>
  $(".customizer-color.dark li").ready(function () {
        $(".customizer-color.dark li").removeClass('active');
        $(this).addClass("active");
        $("body").attr("class", "dark-only");
        localStorage.setItem("dark", "dark-only");
    });
</script>
@endif
</body>
</html>
