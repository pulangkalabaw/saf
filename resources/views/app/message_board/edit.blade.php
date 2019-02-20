@extends ('layouts.app')

@section('content')
<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Announcements</li>
        <li class="active">Edit Post</li>
    </ol>
</div>
<div class="container-fluid half-padding">
    <div class="template template__blank">
        <div class="row">
            <div class="col-md">

                <div class="row">
                    <div class="col-md">
                        <form class="" action="index.html" method="post">
                            {{csrf_field()}}
                            <div class="container-fluid half-padding">
                                <div class="template template_texteditor">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-danger">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Post Editor</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <h5>Subject</h5>
                                                    <input type="text" class="form-control" name="" value="">
                                                    <br>
                                                    <div class="summernote">
                                                    </div>
                                                    <br>
                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary btn-sm" name="button"></i>Update Post</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
