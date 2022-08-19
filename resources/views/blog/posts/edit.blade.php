@extends('layouts.app')
@section('content')
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Blog Tags</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show my-1" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf
                        <div class="form-group">
                            <label for="cover">Cover</label>
                            <input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror"
                                id="cover" value="{{ old('cover') ? old('cover') : $post->cover }}">
                            @error('cover')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') ? old('title') : $post->title }}" required>
                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug') ? old('slug') : $post->slug }}" required>
                            @error('slug')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="desc">Description</label>
                            <textarea name="desc" id="desc" cols="30" rows="10"
                                class="form-control @error('desc') is-invalid @enderror"
                                required>{{ old('desc') ? old('desc') : $post->desc }}</textarea>
                            @error('desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category" id="category"
                                class="form-control @error('category') is-invalid @enderror" required>
                                <option value="" disabled selected>Choose one</option>
                                @foreach ($categories as $category)
                                    <option {{ $category->id == $post->category_id ? 'selected' : '' }}
                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tag">Tags</label>
                            <select name="tags[]" id="tag" class="form-control select2 @error('tags') is-invalid @enderror"
                                required multiple  data-toggle="select2">
                                @foreach ($post->tags as $tag)
                                    <option selected value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                                @foreach ($tags as $tags)
                                    <option value="{{ $tags->id }}">{{ $tags->name }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keywords">Keywords</label>
                            <input type="text" name="keywords" class="form-control @error('keywords') is-invalid @enderror"
                                value="{{ old('keywords') ? old('keywords') : $post->keywords }}" required>
                            @error('keywords')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="meta_desc">Meta Desc</label>
                            <input type="text" name="meta_desc"
                                class="form-control @error('meta_desc') is-invalid @enderror"
                                value="{{ old('meta_desc') ? old('meta_desc') : $post->meta_desc }}" required>
                            @error('meta_desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div><br>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    @if (isset($post->desc))
        <script>
            CKEDITOR.instances['deskripsi'].setData("{{ $post->desc }}");
        </script>
    @endif



    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Choose Some Tags"
            });
        });
    </script> --}}
    <script>
        CKEDITOR.replace('desc');
    </script>
@endsection
