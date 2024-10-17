<x-main-layout title="Create Classworks">
  <div class="container">
    <h1>{{$classroom->name}} (#{{$classroom->id}})</h1>
    <h3> {{$classwork->title}} </h3>
    <x-alert name="success" class="alert-success"/>
    <x-alert name="error" class="alert-danger"/>
    <hr>

    <div class="row">
        <!-- Left Column (col-md-8) -->
        <div class="col-md-8">
            <div>
                <p>{{$classwork->description}}</p>
            </div>
            <h4>Comments</h4>

            <form action="{{route('comments.store')}}" method="post">
              @csrf
              <input type="hidden" name="id" value="{{$classwork->id}}">
              <input type="hidden" name="type" value="classwork">
            
              <div class="d-flex">
                <div class="col-8">
                  <div class="form-floating mb-3">
                    <textarea class="form-control" id="content" name="content" rows="3" placeholder="Comment"></textarea>
                    <label for="content">Comment</label>
                    @error('content')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror 
                    <x-form.error name="content"/>
                  </div>
                </div>

                <div class="ms-1">
                  <button type="submit" class="btn btn-primary">Create</button>
                </div> 
              </div>
            </form>

            <div class="mt-4">
              @foreach ($classwork->comments as $comment)
                <div class="row">
                  <div class="col-md-2">
                    <img src="">
                  </div>
                  <div class="col-md-10">
                    <p>By: {{$comment->user->name}}. Time: {{$comment->created_at->diffForHumans()}}</p>
                    <p>{{$comment->content}}</p>
                  </div>
                </div>
              @endforeach
            </div>
        </div>
        
        <!-- Right Column (col-md-4) -->
        <div class="col-md-4">
            <div class="bordered rounded p-3 bg-light">
              <h4>Submission</h4>

              @if($submissions->count())
                  <ul>
                      @foreach ($submissions as $submission)
                      <li><a href="{{route('submissions.file',$submission->id)}}">File #{{$loop->iteration}}</a></li>  
                      @endforeach
                  </ul>
              @else


              <form action="{{route('submissions.store', $classwork->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-floating mb-3">
                  <input class="form-control" name="files[]" type="file" placeholder="Select Files" multiple>
                  <label for="files">Upload Files</label>
                  <button class="btn btn-primary mt-3" type="submit">Submit</button>
                </div>
              </form>
              @endif

            </div>
        </div>
    </div>
  </div>  
</x-main-layout>
