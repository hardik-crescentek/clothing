<!-- resources/views/articles/show.blade.php -->

@extends('layouts.master')
@section('title', 'Articles for ' . $article_no)
@section('content')

<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Articles for {{ $article_no }}</h4>
            </div>
            <div class="widget-body">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Article No</th>
                                <th>Color No</th>
                                <th>Role</th>
                                <th>Cut Wholesale</th>
                                <th>Retail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                                <tr>
                                    <td>{!! Form::text('article_no[]', $article->article_no, ['class' => 'form-control', 'readonly' => 'readonly']) !!}</td>
                                    <td>{!! Form::text('color_no[]', $article->color_no, ['class' => 'form-control', 'readonly' => 'readonly']) !!}</td>
                                    <td>{!! Form::text('roll[]', (isset($article->roll) && !empty($article->roll && $article->roll != "") ? $article->roll : 0 ), ['class' => 'form-control']) !!}</td>
                                    <td>{!! Form::text('cut_wholesale[]', (isset($article->cut_wholesale) && !empty($article->cut_wholesale && $article->cut_wholesale != "") ? $article->cut_wholesale : 0 ), ['class' => 'form-control']) !!}</td>
                                    <td>{!! Form::text('retail[]',(isset($article->retail) && !empty($article->retail && $article->retail != "") ? $article->retail : 0 ), ['class' => 'form-control']) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="form-group row d-flex align-items-center mt-5">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
