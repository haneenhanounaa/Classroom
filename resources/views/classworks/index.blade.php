<x-main-layout :title="$classroom->name">
  <div class="container">
    <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
    <h3>{{ $classroom->section }}</h3>
    <hr>

    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        + Create
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create', [$classroom->id, 'type' => 'assignment']) }}">Assignment</a></li>
        <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create', [$classroom->id, 'type' => 'material']) }}">Material</a></li>
        <li><a class="dropdown-item" href="{{ route('classrooms.classworks.create', [$classroom->id, 'type' => 'question']) }}">Question</a></li>
      </ul>
    </div>

    <hr>

    <form action="{{URL::current()}}" method="get" class="row row-cols-lg-auto g-3 align-items-center"> 
      <div class="col-12"> 
          <input type="text" placeholder="Search..." class="form-control" name="search">
       </div>

       <div class="col-12"> 
          <button class="btn btn-primary ms-2" type="submit">Find</button>
      </div>   
    </form>
      {{-- <h3>{{ $group->first()->topic->name ?? 'No Topic'}}</h3> --}}

      <div class="accordion accordion-flush" id="accordionFlushExample">
        @foreach ($classworks as $classwork)
          <div class="accordion-item">
            <h2 class="accordion-header" id="flush-heading{{ $classwork->id }}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $classwork->id }}" aria-expanded="false" aria-controls="flush-collapse{{ $classwork->id }}">
                {{ $classwork->title }}
              </button>
            </h2>
            <div id="flush-collapse{{ $classwork->id }}" class="accordion-collapse collapse" aria-labelledby="flush-heading{{ $classwork->id }}" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                {{ $classwork->description }}
                <div>
                  <a class="btn btn-sm btn-outline-success" href="{{route('classrooms.classworks.show',[$classwork->classroom_id,$classwork->id])}}">View </a>
                  <a class="btn btn-sm btn-outline-dark" href="{{route('classrooms.classworks.edit',[$classwork->classroom_id,$classwork->id])}}">Edit</a>
                </div>
             
             
              </div>
            </div>
          </div>
        @endforeach
      </div>

    {{-- @empty
      <p class="text-center fs-4">No Classworks Found</p>
    @endforelse --}}
    {{$classworks->withQueryString()->links('vendor.pagination.bootstrap-5')}}

  </div> 
</x-main-layout>