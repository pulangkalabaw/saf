@extends ('layouts.app')

@section('content')
<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Announcements</li>
        <li class="active">Message Board</li>
    </ol>
</div>
<div class="container-fluid half-padding">
    <div class="template template__blank">
        <div class="row" style="padding: 15px;">
            <div class="col-md-12">
                <div class="row text">
                    <div class="col-md">
                        <!--FORM FOR NEW POST  -->                       
                        <form action="{{ route('app.messages.store') }}" method="post">
                            {{csrf_field()}}
                            <div class="template template_texteditor">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-danger">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Text Editor</h3>
                                            </div>
                                            <div class="panel-body">
                                               
                                                <h5 class="text-left"><b>SUBJECT</b></h5>
                                                @if($errors->has('subject'))
                                                    <div class="alert alert-danger text-danger">
                                                        {{ $errors->first('subject') }}
                                                    </div>
                                                @endif
                                                <input type="text" class="form-control" name="subject" value="">
                                                <br>
                                                @if($errors->has('message'))
                                                    <div class="alert alert-danger text-danger">
                                                        {{ $errors->first('message') }}
                                                    </div>
                                                @endif
                                                <h5 class="text-left">BODY</h5>
                                                <textarea class="summernote" name="message">
                                                </textarea>
                                                <br>
                                                <div class="text-right">
                                                    <input type="checkbox" id="pin"  name="pinned" value="1">
                                                    &nbsp;<label for="pin">Pin Post</label>&nbsp;
                                                    <button type="submit" class="btn btn-primary btn-sm" name="button"></i>Post</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <!--IF HAS PINNED POST  -->
                        <div class="col-md">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h4><i class="fa fa-thumb-tack" aria-hidden="true"></i> <b>Pinned Post</b></h4>
                                    <div class="col-md-12 text-left">
                                        <h5 class="text-left"><b>{{ $pinned->subject }}</b></h5>
                                    </div>
                                    <div class="col-md-12">
                                        {!! $pinned->message !!}
                                        <h5 class="text-right"><b>{{ $pinned->user->fname.' '.$pinned->user->lname }}</b></h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--LIST OF POST  -->
                        <!--if has post  -->
                        @foreach($messages as $post)
                        <div class="col-md">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 text-left">
                                            <h5 class="text-left"><b>{{ $post->subject }}</b></h5>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button  type="button" class="btn btn-xs" name="button" ><i class="fa fa-thumb-tack" aria-hidden="true"></i></button>
                                            <button  type="button" class="btn btn-xs" name="button" ><i class="fa fa-edit" aria-hidden="true"></i></button>
                                            <button  type="button" class="btn btn-xs" name="button" ><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        {!! $post->message !!}
                                        <h5 class="text-right"><b>{{ $post->user->fname.' '.$post->user->lname }}</b></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="row">
                            <div class="col-md text-center">
                                {{ $messages->links() }}
                            </div>
                        </div>



                        <!-- IF HAS NO POST/ POST IS EMPTY -->
                        <!-- <div class="col-md">
                            <div class="panel panel-default text-center">
                                <div class="panel-body">
                                    <h4><b>No Post Yet</b><h4>
                                </div>

                            </div> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @endsection
    @section('scripts')


    <script src="{{ asset('assets/libs/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('assets/js/template/texteditor.js') }}"></script>


    @endsection
