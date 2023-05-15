<!-- <div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Default checkbox
  </label>
</div> -->

<div class="container"> 
  <div class="tag_container">
  @foreach ($tags as $tag) 
    <div class="form-check mt-2 tag_row" id="div_tag_{{$tag->id}}">
      
      @if (!is_null($task->tag_id) && gettype(array_search($tag->id,json_decode($task->tag_id))) == "integer" )
        <input class="form-check-input" type="checkbox" value="{{$tag->id}}" id="flexCheckChecked" checked>
      @else
        <input class="form-check-input" type="checkbox" value="{{$tag->id}}" id="flexCheckChecked">
      @endif
      
      <label class="form-check-label" for="flexCheckChecked">
        {{$tag->name}}
      </label>
    </div>
  @endforeach
  </div>
  <div class="col-md-6">
    <div class="row mt-2">
      <button id="add_tag_button_{{$i}}" value="{{$i}}" class="btn btn-primary">Add tag</button>
      <form id="add_tag_form_{{$i}}" value="{{$i}}" class="row" style="display:none;">
          <div class="form-group col-md-9">
          <input type="text" id="tag_name_input_{{$i}}" name="tag_name_input" class="form-control" placeholder="Tag name">
          </div>
          <button id="tag_submit_{{$i}}" value="{{$i}}" class="btn btn-success col-md-3" style="display:none;">Add</button>
      </form>
      </div>
    </div>
</div>
    
