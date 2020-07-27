@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-md-10 offset-md-1">
        <div class="card ">
            <div class="card-body">
                <h2>
                    <i class="far fa-edit"></i>
                    {{$topic->id?'编辑话题':'新建话题'}}
                </h2>
                <hr>
                @if($topic->id)
                    <form action="{{ route('topics.update', $topic->id) }}" method="POST" accept-charset="UTF-8">
                    @method('PUT')
                @else
                    <form action="{{ route('topics.store') }}" method="POST" accept-charset="UTF-8">
                @endif
                        @csrf
                        @include('shared._error')

                        <div class="form-group">
                            <input class="form-control" type="text" name="title" placeholder="请填写标题" id="title-field" value="{{ old('title', $topic->title ) }}" />
                        </div>


                        <div class="form-group">
                            <select class="form-control" name="category_id" required>
                                <option value="" hidden disabled selected>请选择分类</option>
                                @foreach($categories as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <textarea name="body" id="body-field" class="form-control" placeholder="请填入至少三个字符的内容" rows="10">{{ old('body', $topic->body ) }}</textarea>
                        </div>

                        <div class="well well-sm">
                            <button type="submit" class="btn btn-primary">
                                <i class="far fa-save mr-2" aria-hidden="true"></i>
                                保存
                            </button>
                            <a class="btn btn-link float-xs-right" href="{{ route('topics.index') }}"> <- 回退</a>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

@endsection
