<x-alert name="error" type="danger"/>
<div class="row">
  <div class="col-md-8">
    
  <div class="form-floating">
    <input type="text" class="form-control mb-3 @error('title') is-invalid @enderror" 
           name="title" value="{{$classwork->title}}" id="title" placeholder="Title">
    <label for="title">Title</label>
    @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror 
    <x-form.error name="title"/>
  </div>
  
  <div class="form-floating mb-3">
    <textarea class="form-control" id="description" name="description" value="{{$classwork->description}}" rows="3" placeholder="Description (Optional)" ></textarea>
    <label for="description">Description (Optional)</label>

    @error('description')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror 
    <x-form.error name="description"/>
  </div>

  </div>

  <div class="col-md-4">

    <div class="form-floating mb-3">
      <input name="published_at" type="date" value="{{$classwork->published_date}}" class="form-control" >
      <label for="published_at">Publish Date</label>
    </div>

    <div class="mb-3">
      @foreach ($classroom->students as $student)
      <div class="form-check">
        <input class="form-check-input" type="checkbox"name="students[]" value="{{$student->id}}" id="std-{{$student->id}}" @checked(in_array($student->id,$assigned ?? []))>
        <label class="form-check-label" for="std-{{$student->id}}">
          {{$student->name}}
        </label>
      </div>
      @endforeach
    </div>

    @if ($type == 'assignement') 
    <div class="form-floating mb-3">
        <input name="options[grade]" type="number" class="form-control" id="grade" placeholder="Enter grade"  value="{{$classwork->options['grade'] ?? ''}}" min="0">
        <label for="grade">Grade</label>
    </div>
    <div class="form-floating mb-3">
      <input name="options[due]" type="date" class="form-control"  value="{{$classwork->options['due'] ?? ''}}" >
      <label for="due">Due</label>
  </div>
    @endif
   
    <div class="form-floating mb-3">
      <select class="form-select" name="topic_id" id="topic_id">
          <option value="">No Topic</option>
          @foreach ($classroom->topics as $topic)
              <option @selected($topic->id == $classwork->topic_id) 
                value="{{$topic->id}}">{{$topic->name}}</option>
          @endforeach
      </select>
      <label for="topic_id">Topic (Optional)</label>
      <x-form.error name="topic_id" />
      
    </div>

  </div>
</div>