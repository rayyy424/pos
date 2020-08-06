@extends('app')

@section('datatable-css')

@endsection

@section('datatable-js')

@endsection

@section('content')

  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Access Denied
      </h1>

      <ol class="breadcrumb">
      <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Access Denied</a></li>
      </ol>
    </section>

    <section class="content">

      <div class="row">
        <div class="error-page">
          <h3 style="vertical-align" class="headline text-red"> Error code 401</h3>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12  text-center">
          <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>

          <p>
            Access denied.
            You don't have permission to view this page.
          </p>

        </div>
      </div>
      <!-- /.error-page -->


   </section>

</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.0.1
  </div>
  <strong>Copyright &copy; 2014-2016 <a href="http://www.softoya.com">TrackerOnTheGo</a>.</strong> All rights
  reserved.
</footer>

@endsection
