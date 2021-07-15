@extends('layouts.site')

@section('content')
    <div class="container">
      <div class="row nurse-container">
        <div class="col-sm-4">
            <div class="card register-card bg-white doctor-card">
                <div class="card-header bg-red text-center">
                    <img src="{{asset('images/doctor.png')}}" class="m-auto">
                </div>
                <div class="card-body  bg-white">
                    <a href="javascript:void(0);" onclick="regiseration_popup('available_today')" data-toggle="modal" data-target="#registeratio_modal">
                        <h4 class="text-center text-red">{{trans("site.available_today")}}</h4>
                    </a>
                </div>

            </div>
        </div>
        <div class="col-sm-3">
          <img src="{{asset('images/nursee.png')}}" class="m-auto nurse-img">
        </div>
        <div class="col-sm-4">
            <div class="card register-card bg-primary appointment-card">
                <div class="card-header text-center bg-white">
                    <img src="{{asset('images/appointment.png')}}" class="m-auto">
                </div>
                <div class="card-body bg-primary">
                    <a href="javascript:void(0);" onclick="regiseration_popup('regist_now')" data-toggle="modal" data-target="#registeratio_modal">
                        <h4 class="text-center text-white">{{trans("site.regist_now")}}</h4>
                    </a>
                </div>

            </div>
        </div>
      </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="registeration_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title text-center m-auto" id="RegistModelLabel">


           </h5>
           <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body" id="RegistModelBody">

         </div>
         {{-- <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
           <button type="button" class="btn btn-primary">Save changes</button>
         </div> --}}
       </div>
     </div>
   </div>
@endsection
@section('scripts')
@parent

@endsection
