
<x-main-layout title="Classrooms">
  
    <div class="container">

        <h1>Trashed Classrooms</h1>
        <!-- رح يعرض يلي داخل ملف الالرت  -->
      
        <x-alert name="success" id="success" class="alert-success"/>
        <x-alert name="error" id="error"class="alert-danger"/>
        <!-- <x-alert name="error"/> -->


      <div class="row">
        @foreach($classrooms as $classroom)
        <div class="col-md-3">
             
        <div class="card">
          <img src="{{asset('storage/'.$classroom->cover_image_path)}}"class="card-img-top" alt="">
          <div class="card-body">
            <h5 class="card-title">{{$classroom->name}}</h5>
              <p class="card-text">{{$classroom->section}}-{{$classroom->room}}</p>

            <div class="d-flex justify-content-between">
            <form action="{{ route('classrooms.restore', $classroom->id) }}" method="post">
                @csrf
                @method('put')
                <button type="submit" class="btn btn-sm btn-success">Restore</button>
            </form>
            <form action="{{ route('classrooms.force-delete', $classroom->id) }}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-sm btn-danger">Delete Forever</button>
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