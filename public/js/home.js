$(document).ready(function() {
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $(document).on('keyup', '#task_search_text', function(event) {
        var searchText = $(this).val().toLowerCase();
        $('[id^="task_div_"]').each(function() {
            var taskText = $(this).text().toLowerCase();
            if (taskText.indexOf(searchText) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $(document).on('click', '[id^="tag_delete_"]', function(event) {
        event.preventDefault();
        delete_tag($(this).attr('value'));
    });

    $(document).on('click', '[id^="task_delete_"]', function(event) {
        event.preventDefault();
        delete_task($(this).attr('task_id'), $(this).attr('value'));
    });

    $(document).on('submit', '#tags_form', function(event) {
        event.preventDefault();

        filterTags($(this).serialize())
    });

    $(document).on('click', '[id^="add_image_"]', function(event) {
        $("#customFile_" + $(this).attr('value')).click();
    });

    $(document).on('click', '[id^="delete_img_"]', function(event) {
        delete_image($(this).attr('task_id'), $(this).attr('value'))
    });

    $(document).on('click', '[id^="add_tag_button"]', function(event) {
        $('#add_tag_button_' + $(this).val()).css("display", "none");
        $('#add_tag_form_' + $(this).val()).css("display", "");
        $('#tag_submit_' + $(this).val()).css("display", "");
    });

    $(document).on('click', '[id^="tag_submit"]', function(event) {
        event.preventDefault();
        let name = $("#tag_name_input_" + $(this).val()).val();
        add_tag(name)
    });

    $(document).on('click', '#add-task', function(event) {
        event.preventDefault();
        let text = $("#text-task").val();
        add_task(text);
    });

    $(document).on('click', '[id^="EditTagsModal"] .btn_save_changes', function(event) {
        event.preventDefault();

        let checkedValues = $('#EditTagsModal_' + $(this).attr('data-modal-id') + ' input[type=checkbox]:checked').map(function() {
            return this.value;
        }).get();

        let taskName = $(this).val();

        change_tags(checkedValues, taskName);
    });

    $(document).on('change', '[id^="customFile_"]', function(event) {
        let task_id = $(this).attr("task_id");
        let page_id = $(this).attr("id_on_page");

        add_image(task_id, page_id)
    });

    function add_task(text) {
        $.ajax({
            url: "/tasks",
            type: 'POST',
            data: {
                '_token': csrf_token,
                'text': text
            },
            success: function(message) {
                $('#task_data').html(message);
            }
        });
    }

    function add_tag(name) {
        $.ajax({
            url: "/tags",
            type: 'POST',
            data: {
                '_token': csrf_token,
                'name': name
            },
            success: function(message) {
                $('.tag_container').append("<div class='form-check mt-2 tag_row' id='div_tag_" + message.tag.id + "'><input class='form-check-input' type='checkbox' value='" + message.tag.id + "' id='flexCheckChecked'><label class='form-check-label' for='flexCheckChecked'>" + message.tag.name + "</label></div>");

                let tagRow = document.createElement('div');
                tagRow.classList.add('form-check', 'mt-2', 'tag_row', 'd-flex', 'align-items-center');
                tagRow.id = "div_tag_" + message.tag.id;

                let firstCol = document.createElement('div');
                firstCol.classList.add('col-sm-11');

                let checkbox = document.createElement('input');
                checkbox.classList.add('form-check-input');
                checkbox.type = 'checkbox';
                checkbox.value = message.tag.id;
                checkbox.name = 'tags[]';
                checkbox.id = 'flexCheck_' + message.tag.id;

                let label = document.createElement('label');
                label.classList.add('form-check-label');
                label.textContent = message.tag.name;
                label.setAttribute('for', 'flexCheck_' + message.tag.id);

                firstCol.appendChild(checkbox);
                firstCol.appendChild(label);

                let secondCol = document.createElement('div');
                secondCol.classList.add('col-sm-1', 'ml-auto');

                let button = document.createElement('button');
                button.type = 'button';
                button.classList.add('btn', 'btn-light');

                let img = document.createElement('img');
                img.src = 'images/delete.png';
                img.id = 'tag_delete_' + message.tag.id;
                img.setAttribute('value', message.tag.id);
                img.width = '24';
                img.height = '24';
                img.alt = 'delete';

                button.appendChild(img);
                secondCol.appendChild(button);

                tagRow.appendChild(firstCol);
                tagRow.appendChild(secondCol);

                document.getElementById('tag_container_head').appendChild(tagRow);
            }
        });
    }

    function change_tags(tags, name) {
        $.ajax({
            url: "/tasks/update",
            type: 'POST',
            data: {
                '_token': csrf_token,
                'tags': tags,
                'name': name
            },
            success: function(message) {

            }
        });
    }

    function add_image(id, page_id) {
        let formData = new FormData($('#add_image_form_' + id)[0]);
        formData.append('task_id', id);

        $.ajax({
            url: "/tasks/addImage",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                let link = document.createElement("a");
                link.href = 'storage/' + response['image_src'];
                link.target = "_blank";

                let img = document.createElement("img");
                img.src = 'storage/' + response['image_src'];
                img.style.width = "150px";
                img.style.height = "150px";
                img.style.paddingLeft = "0px";
                img.style.paddingRight = "0px";
                img.alt = "Task image";

                link.appendChild(img);
                $('#task_image_div_' + page_id).html(link);
            },
            error: function(xhr, status, error) {
                console.log(status + ': ' + error);
            }
        });
    }

    function delete_image(id, page_id) {
        $.ajax({
            url: "/tasks/deleteImage",
            type: 'POST',
            data: {
                '_token': csrf_token,
                'task_id': id
            },
            success: function(response) {
                $("#task_image_div_" + page_id).html("");
            }
        });
    }

    function delete_task(id, page_id) {
        $.ajax({
            url: "/tasks/destroy",
            type: 'POST',
            data: {
                '_token': csrf_token,
                'task_id': id
            },
            success: function(response) {
                $('#task_data').html(response);
            }
        });
    }

    function delete_tag(id) {
        $.ajax({
            url: "/tags/destroy",
            type: 'POST',
            data: {
                '_token': csrf_token,
                'tag_id': id
            },
            success: function(response) {
                $('#div_tag_' + id).removeClass("d-flex");
                $('#div_tag_' + id).css('display', 'none');
            }
        });
    }

    function filterTags(form_data) {
        $.ajax({
            url: "/tags/filterTags",
            type: 'POST',
            data: form_data,
            success: function(response) {
                $('#task_data').html(response);
            }
        });
    }

});