<!-- <div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Default checkbox
  </label>
</div> -->

<div class="container"> 
    <div class="tag_container_head" id="tag_container_head">
      @foreach ($tags as $tag) 
        <div class="form-check mt-2 tag_row d-flex align-items-center" id="div_tag_{{$tag->id}}">
            <div class="col-sm-11">
              <input class="form-check-input" type="checkbox" value="{{$tag->id}}" name="tags[]" id="flexCheck_{{$tag->id}}">
              <label class="form-check-label" for="flexCheck_{{$tag->id}}">
                {{$tag->name}}
              </label>
            </div>
            <div class="col-sm-1 ml-auto">
              <button type="button" class="btn btn-light">
                <img src="{{ asset('images/delete.png')}}" id="tag_delete_{{$tag->id}}" value="{{$tag->id}}" width="24" height="24" alt="delete">
              </button>
            </div>
        </div>
      @endforeach
    </div>
</div>
    
