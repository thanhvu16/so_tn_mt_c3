@if(Session ::has('capso'))
{{--    <h3 class="alert alert-success" style="background: rgb(178, 221, 224); padding: 18px; ">Số vừa duyệt là: <span style="color: red;font-weight: bold">{{Session::get('capso')}}</span></h3>--}}

    <div class="alert alert-success">
        <strong>Số vừa duyệt là:</strong> <span style="font-size: 18px;color: red">{{Session::get('capso')}}</span>
    </div>
    @endif
{{--@if(Session::has('erro'))--}}
{{--    <p class="alert alert-danger">{{Session::get('erro')}}</p>--}}
{{--@endif--}}
@foreach($errors->all() as $erro)
    <p class="alert alert-danger time-out">{{$erro}}</p>
@endforeach

