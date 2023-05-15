@extends('layouts.app')

@section('content')

<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Task" aria-label="Task Name" aria-describedby="button-addon2" id="text-task">
                <button class="btn btn-outline-secondary" type="button" id="add-task">Add</button>
            </div>
        </div>
        <div class="col-3">       
            <button type="button" class="btn col-sm-12 btn-primary" data-bs-toggle="modal" data-bs-target="#SortTagsModal">
            Tags
            </button>

            <div class="modal fade" id="SortTagsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Tags</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="tags_form">
                        @csrf
                        <div class="modal-body tags_sort" id="sort-modal-body">
                            @include('tags')
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                        </div>
                    </form>
                  </div>
                </div>
            </div>

        </div>
        <div class="col-3 form-group">
            <input type="text" class="form-control" id="task_search_text" placeholder="Search Tasks ">
        </div>
    </div>

    <div id="task_data">
        @include('task_list')
    </div>
</div>
@endsection

@push('script')
    <script type="text/javascript" src="{{ asset('js/home.js') }}"></script>
@endpush