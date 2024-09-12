<div class="form-floating mb-3">
    {{$slot}}
    <label for="{{$name}}">{{$placeholder}}</label>
    <x-form.error name="{{$name}}"/>
</div>