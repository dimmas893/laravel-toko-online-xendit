@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Blog Posts</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary my-3">
                            Add Post
                        </a>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show my-1" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show my-1" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Desc</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $key => $item)
                                    <tr>
                                        <th scope="row">{{ ++$key }}</th>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ Str::limit(strip_tags($item->desc), 60) }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>
                                            <a href="{{ route('posts.edit', $item->id) }}"
                                                class="btn btn-success btn-sm">Edit</a>
                                            <form method="POST" action="{{ route('posts.destroy', [$item->id]) }}"
                                                class="d-inline" onsubmit="return confirm('Move post to trash ?')">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
