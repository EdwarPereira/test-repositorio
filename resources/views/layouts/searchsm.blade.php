{!! Form::open(['method'=>'GET','url'=>$url,'class'=>'navbar-form navbar-left','role'=>'search'])  !!}

<div class="input-group custom-search-form">
    <input type="text" class="form-control" name="search" placeholder="{{trans('search.buscar')}}...">
    <span class="input-group-btn">
        <button class="btn btn-default-sm" type="submit">
            <i class="fa fa-search"></i>
        </button>
    </span>
</div>

{!! Form::close() !!}