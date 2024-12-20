
<x-main-layout :title="__('Classrooms')">
  
    <div class="container">

        <h1> {{__('Classrooms')}}</h1>
        <!-- رح يعرض يلي داخل ملف الالرت  -->
      
        <x-alert name="success" id="success" class="alert-success"/>
        <x-alert name="error" id="error"class="alert-danger"/>
        <!-- <x-alert name="error"/> -->


      <div class="row">
        @foreach($classrooms as $classroom)
        <div class="col-md-3">
             
        <div class="card">
          <img src="{{$classroom->cover_image_url
           }}" class="card-img-top" alt="">
          <div class="card-body">
            <h5 class="card-title">{{$classroom->name}}</h5>
              <p class="card-text">{{$classroom->section}}-{{$classroom->room}}</p>
            <div class="d-flex justify-content-between">
            <a href="{{$classroom->url}}" class="btn btn-sm btn-primary">{{__('View')}}</a>
            <a href="{{route('classrooms.edit',$classroom->id)}}" class="btn btn-sm btn-dark">Edit</a>

            <form action="{{ route('classrooms.destroy', $classroom->id) }}" method="post">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-sm btn-danger">{{__('Delete')}}</button>
          </form>
        </div>  
          </div>
        </div>

        </div>
        @endforeach
      </div>  
    </div>   


@push('scripts')
<script>console.log('@ stack')</script>
@endpush()

</x-main-layout>