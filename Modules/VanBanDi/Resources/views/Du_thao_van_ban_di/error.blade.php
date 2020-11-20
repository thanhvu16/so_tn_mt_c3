@if(Session ::has('capso'))
    <h3 class="alert alert-success" style="background: rgb(178, 221, 224); padding: 18px; ">Số vừa duyệt là: <span style="color: red;font-weight: bold">{{Session::get('capso')}}</span></h3>
    @endif
