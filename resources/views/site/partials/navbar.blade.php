<nav class="navbar navbar-expand-lg navbar-dark bg-transparence" dir="rtl">

<!--  Show this only on mobile to medium screens  -->
  <a class="navbar-brand d-lg-none text-center m-auto" href="#"><img class="logo-img" src="{{asset('images/logo.png')}}"></a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

<!--  Use flexbox utility classes to change how the child elements are justified  -->
  <div class="collapse navbar-collapse justify-content-between" id="navbarToggle">

    <ul class="navbar-nav w-30 p-0">
     <img class="logo-img m-auto w-60" src="{{asset('images/logo.png')}}">
    </ul>


<!--   Show this only lg screens and up   -->
    {{-- <a class="navbar-brand d-none d-lg-block m-auto" href="#">Navsssssssssbar</a> --}}
    <form class="form-inline w-30" dir="rtl">
        <div class=" form-group has-search">
            <span class="fa fa-search form-control-feedback half-border"></span>
            <input type="text" class="form-control nav-search" placeholder="{{trans('global.search')}}">
        </div>
    </form>


    <ul class="navbar-nav w-40">
      <li class="nav-item">
        <a class="nav-link" onclick="get_popup_data('about_us')" href="#" data-toggle="modal" data-target="#NavModel" >{{trans('site.nav.about_us')}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="get_popup_data('global_questions')" data-toggle="modal" data-target="#NavModel">{{trans('site.nav.global_questions')}}</a>
      </li>
      <li class="nav-item" >

        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-map-marker-alt"></i> {{trans('panel.cities.gada')}}
        </a>
        <div class="dropdown-menu drop-nav" aria-labelledby="navbarDropdown">
            @foreach(Config::get('cities') as $city)
                <a class="dropdown-item" href="#">{{trans('panel.cities.'.$city)}}</a>
            @endforeach
        </div>
      </li>
      @if(count(config('panel.available_languages', [])) > 1)
			 <li class="nav-item  ">
                 <a class="nav-link dropdown-toggle" href="#" id="Language_Dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   {{ strtoupper(app()->getLocale()) }}
                 </a>

				<div class="dropdown-menu drop-nav" aria-labelledby="Language_Dropdown">
					@foreach(config('panel.available_languages') as $langLocale => $langName)
						<a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
					@endforeach
				</div>
			</li>
		@endif
    </ul>
  </div>
</nav>
<!-- Modal -->

 <div class="modal fade bd-example-modal-lg" id="NavModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center m-auto" id="NavModelLabel">


        </h5>
        <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="NavModelBody">
        ...
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}
    </div>
  </div>
</div>

{{--
<nav class="navbar navbar-light  bg-transparence " dir="rtl">
    <div class="row col-sm-12">
        <div class="col-sm-3">
            <img class="logo-img" src="{{asset('images/logo.png')}}">
        </div>
        <div class="col-sm-4">
            <form class="form-inline">
                <div class="form-group has-search">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input type="text" class="form-control nav-search" placeholder="Search">
                </div>
            </form>
        </div>
        <div class="col-sm-5">
                <ul class=" horizantial-menu mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href="#">نبذه عننا </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">الأسئله الشائعه</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      language
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="#">Action</a>
                    </div>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      language
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="#">Action</a>
                    </div>
                  </li>
                </ul>
        </div>

    </div>

</nav> --}}
