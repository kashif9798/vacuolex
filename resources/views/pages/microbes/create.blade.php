@extends('layouts/contentLayoutMaster')

@section('title', 'Create Microbe')
@push('styles')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            display: none !important;
        }

        .microbe-image {
            width: 10rem;
            height: 10rem;
            object-fit: cover;
        }

        .microbe-image-edit-btn {
            position: absolute;
            top: 0px;
            right: 0px;
        }

        #microbe-image-container {
            cursor: pointer;
        }

        .tox-statusbar{
            display: none !important;
        }

    </style>
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('microbes.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <div class="position-relative" id="microbe-image-container">
                        <img class="rounded microbe-image border" id="microbe-image"
                            src="{{ asset('images/elements/placeholder.jpg') }}" alt="Microbe Image">
                        <button type="button" class="rounded-circle microbe-image-edit-btn btn btn-icon btn-primary">
                            <i data-feather="edit"></i>
                        </button>
                        <p class="mt-1 text-center">Microbe Image</p>
                    </div>
                </div>

                <input type="file" name="image" id="image" class="d-none" accept="image/*">
                @error('image')
                    <strong class="text-danger mt-2">{{ $message }}</strong>
                @enderror

                <div class="row mt-2">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                                id="title" value="{{ old('title') }}" />
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="sub-category-id">Subcategory</label>
                            <select class="select2 form-control form-control-lg" id="sub-category-id"
                                name="sub_category_id">
                                @if (empty(old('sub_category_id')))
                                    <option selected disabled>Select a subcategory for the microbe</option>
                                @endif
                                @foreach ($subcategories as $subcategory)
                                    @if (old('sub_category_id') == $subcategory->id)
                                        <option value="{{ $subcategory->id }}" selected>{{ $subcategory->title }}
                                        </option>
                                    @else
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('sub_category_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="excerpt">Excerpt</label>
                            <textarea class="form-control" name="excerpt" id="excerpt" rows="2">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="30">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">
                                Create Microbe
                            </button>
                        </div>
                    </div>

                </div>
        </div>
        </form>
    </div>
    </div>
@endsection

@push('scripts')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.3/tinymce.min.js" integrity="sha512-ykwx/3dGct2v2AKqqaDCHLt1QFVzdcpad7P5LfgpqY8PJCRqAqOeD4Bj63TKnSQy4Yok/6QiCHiSV/kPdxB7AQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '#description',
                plugins: "advlist, autolink, lists, link, image, charmap, fullscreen, media, table",
                toolbar: 'undo redo | styleselect | forecolor | bold italic | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent | charmap | link | table | image | media | fullscreen',
                setup: function (editor) {
                    editor.on('init change', function () {
                        editor.save();
                    });
                },
                image_class_list: [
                    {title: 'img-fluid', value: 'img-fluid'},
                ],
                image_title: true,
                relative_urls : false,
                remove_script_host : false,
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '/admin/microbes/repo/images');
                    var token = '{{ csrf_token() }}';
                    xhr.setRequestHeader("X-CSRF-Token", token);
                    xhr.onload = function () {
                        var json;
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location);
                    };
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                },
                file_picker_types: 'image',
                file_picker_callback: function(cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function() {
                        var file = this.files[0];

                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = function () {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), { title: file.name });
                        };
                    };
                    input.click();
                }
            });

            $("#microbe-image-container").on("click", function(event) {
                event.stopPropagation();
                event.preventDefault();
                $("#image").trigger("click");
            });

            $("#image").on("change", function(event) {
                let reader = new FileReader();
                reader.onload = () => {
                    $("#microbe-image").attr("src", reader.result);
                };
                reader.readAsDataURL(event.target.files[0]);
            });
        });
    </script>
@endpush
