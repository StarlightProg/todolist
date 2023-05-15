@foreach ($tasks as $i => $task)
    <div class="row mt-4 align-items-center justify-content-between" id="task_div_{{$i}}">
        <div id="task_image_div_{{$i}}" class="col-2">
            @if (!is_null($task->image))
                <a href="{{ asset('storage/' . $task->image) }}" target="_blank">
                    <img src="{{ asset('storage/'.$task->image) }}" style="width: 150px; height: 150px; padding-right: 0px; padding-left: 0px;" alt="Task image">
                </a>
            @endif
        </div>
        <div class="col d-flex align-items-center task_name">{{$task->task_text}}</div>

        <button type="button" class="btn col-sm-1 btn-primary" data-bs-toggle="modal" data-bs-target="#EditTagsModal_{{$i}}">
            Edit Tags
        </button>

        <div class="modal fade EditTagsModal" id="EditTagsModal_{{$i}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalTitle_{{$i}}">Tags</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="edit-modal-body_{{$i}}">
                        {{ $task->task_text }}
                        @include('task_tags')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary btn_save_changes" value="{{$task->task_text}}" 
                                data-modal-id="{{$i}}" data-modal-title="modalTitle_{{$i}}" data-modal-body="edit-modal-body_{{$i}}" 
                                data-toggle="modal" data-target="#EditTagsModal_{{$i}}" data-bs-dismiss="modal">
                            Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-1">
            <form id="add_image_form_{{$task->id}}" method="POST" enctype="multipart/form-data" action="{{ route('task.addImage') }}">
                @csrf
                <button type="button" class="btn btn-light">
                    <input style="display: none" type="file" name="image_file" class="custom-file-input" task_id="{{$task->id}}" id_on_page="{{$i}}" id="customFile_{{$i}}" accept=".jpg,.png">
                    <img src="{{ asset('images/add_image.png')}}" width="24" height="24" value="{{$i}}" alt="add" id="add_image_{{$i}}">
                </button>
            </form>
        </div>

        <div class="col-sm-1">
            <button type="button" class="btn btn-light">
                <img src="{{ asset('images/delete_image.png')}}" id="delete_img_{{$i}}" task_id="{{$task->id}}" value="{{$i}}" width="24" height="24" alt="delete_img">
            </button>
        </div>

        <div class="col-sm-1">
            <button type="button" class="btn btn-light">
                <img src="{{ asset('images/delete.png')}}" id="task_delete_{{$i}}" task_id="{{$task->id}}" value="{{$i}}" width="24" height="24" alt="delete">
            </button>
        </div>

    </div>
@endforeach