<div class="form-floating">
  {{-- <x-form.input name="section" value="{{$classroom->section}}"
  placeholder="Section"
  />   --}}
 <input type="text" value="{{old('name',$classroom->name)}}"class="form-control 
mb-3 @error('name') is-invalid @enderror" name="name" id="name" placeholder="Name">
<label for="name">Name</label>
@error('name')
<div class="invalid-feedback">{{$message}}</div>
@enderror 
{{-- <x-form.error name="name"/> --}}

</div>  


    <div class="form-floating">
            {{-- <x-form.input name="section" value="{{$classroom->section}}"
            placeholder="Section"
            />   --}}
           <input type="text" value="{{old('section',$classroom->section)}}"class="form-control 
          mb-3 @error('section') is-invalid @enderror" name="section" id="section" placeholder="Section">
          <label for="section">Section</label>
          @error('section')
          <div class="invalid-feedback">{{$message}}</div>
          @enderror 
          <x-form.error name="section"/>

    </div>
    <div class="form-floating mb-3">
    {{-- <x-form.input name="subject" value="{{$classroom->subject}}"
      placeholder="Subject"
      /> --}}
          <input type="text"  value="{{old('subject',$classroom->subject)}}"class="form-control 
          mb-3 @error('subject') is-invalid @enderror"  name="subject" id="subject" placeholder="Subject">
          <label for="subject"> Subject</label>
           @error('subject')
          <div class="invalid-feedback">{{$message}}</div>
          @enderror
          <x-form.error name="subject"/>

    </div>
    <div class="form-floating mb-3">    
          {{-- <!-- <input type="text"  value="{{old('name',$classroom->room)}}"class="form-control 
          mb-3 @error('room') is-invalid @enderror"  name="room" id="room" placeholder="Room"> -->
          <x-form.input name="room" value="{{$classroom->room}}"
           placeholder="Room"/>
          <label for="room">Room</label>
          <!-- @error('room')
          <div class="invalid-feedback">{{$message}}</div>
          @enderror -->
          <x-form.error name="room"/> --}}

          <input type="text"  value="{{old('room',$classroom->subject)}}"class="form-control 
          mb-3 @error('room') is-invalid @enderror"  name="room" id="room" placeholder="Room">
          <label for="room"> Room</label>
           @error('room')
          <div class="invalid-feedback">{{$message}}</div>
          @enderror
          <x-form.error name="room"/>


    </div>
    <div class="form-floating">
        @if($classroom->cover_image_path)
          <img src="{{asset('storage/'.$classroom->cover_image_path)}}"  alt="">
        @endif  
          <input type="file" class="form-control 
          mb-3 @error('cover_image') is-invalid @enderror"  name="cover_image" id="cover_image" placeholder="Cover Image"> 
          {{-- <x-form.input type="file" name="cover_image" value="{{$classroom->Cover_image}}"
           placeholder="Cover Image"/> --}}

          <label for="cover_image">Cover Image</label>
          @error('cover_image')
          <div class="invalid-feedback">{{$message}}</div>
          @enderror
    </div>
        
    <button type="submit" class="btn btn-primary">{{$button_lable}}</button>