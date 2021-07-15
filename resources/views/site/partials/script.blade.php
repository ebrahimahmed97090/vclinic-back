<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
@if(Lang::locale()=='ar')
    <script src="https://cdn.rtlcss.com/bootstrap/v4.5.3/js/bootstrap.min.js" integrity="sha384-VmD+lKnI0Y4FPvr6hvZRw6xvdt/QZoNHQ4h5k0RL30aGkR9ylHU56BzrE2UoohWK" crossorigin="anonymous"></script>
@else
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
@endif
<script src="{{asset('js/script.min.js')}}" ></script>
<script>
function get_popup_data(type){
    //var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = '{{route('site.get_popup_data',':type')}}';
    url = url.replace(':type', type);
   $.ajax({
        url:url,
        type:'get',
        //data:form_data+'&_token=' + CSRF_TOKEN,
    }).done(function(result){
        if (result.status == true){
              $('#NavModelLabel').html(result.type+"<hr>");
              $('#NavModelBody').html(result.html);
        }else{

        }
  });
}
</script>
@yield('scripts')
