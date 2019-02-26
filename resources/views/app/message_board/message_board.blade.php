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
                        @if(empty(session::get('_a')))
                        <form action="{{ route('app.messages.store') }}" method="post" enctype="multipart/form-data">
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
                                                <textarea class="summernotee" name="message" value="">
                                                </textarea>
                                                <br>
                                                <input type="file" name="img[]" multiple >
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
                        @endif
                        <!--IF HAS PINNED POST  -->
                        @if(isset($pinned))
                        <div class="col-md">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 text-left">
                                            <h4><i class="fa fa-thumb-tack" aria-hidden="true"></i> <b>Pinned Post</b></h4>
                                        </div>
                                        @if(empty(session::get('_a')))
                                        <div class="col-md-6 text-right">
                                            <!-- <button data-toggle="modal" data-target="#modal2" type="button" class="btn btn-xs editbtn" onclick="editModal('{{ $pinned->subject }}','{{ $pinned->message }}')" name="button" ><i class="fa fa-edit" aria-hidden="true"></i></button> -->
                                            <button data-toggle="modal" data-target="#modal2" type="button" class="btn btn-xs editbtn" data-id="{{ $pinned->id }}" data-subject="{!! $pinned->subject !!}" data-message="{{ $pinned->message }}" name="button" ><i class="fa fa-edit" aria-hidden="true"></i></button>
                                            <button data-toggle="modal" data-target="#modal1" type="button" class="btn btn-xs deletebtn" data-id="{{ $pinned->id }}" name="button" ><i class="fa fa-trash" aria-hidden="true"></i></button>

                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-12 text-left">
                                        <h5 class="text-left"><b>{{ $pinned->subject }}</b></h5>
                                    </div>
                                    <div class="col-md-12">
                                        {!! $pinned->message !!}
                                    </div>

                                    @if($pinned->files != null)
                                    @foreach(json_decode($pinned->files,true) as $asd)
                                    <img src="{{ asset('assets/images/message_board/'.$asd)}}" alt="Smiley face" height="100" width="100" style="object-fit: cover;">
                                    @endforeach
                                    @endif
                                    <div class="col-md-12">
                                        <h5 class="text-right">Posted by: <b>{{ $pinned->user->fname.' '.$pinned->user->lname }}</b></h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endif
                        <!--LIST OF POST  -->
                        <!--if has post  -->
                        @foreach($messages as $post)
                        <div class="col-md">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 text-left">
                                            <h5 class="text-left thissubject"><b>{{ $post->subject }}</b></h5>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            @if(empty(Session::get('_a')))
                                            <button  data-toggle="modal" data-target="#modal3" data-id="{{ $post->id }}" type="button" class="btn btn-xs pinnedbtn" name="button" ><i class="fa fa-thumb-tack" aria-hidden="true"></i></button>
                                            <button  data-toggle="modal" data-target="#modal2" type="button" class="btn btn-xs editbtn" data-id="{{ $post->id }}" data-subject="{!! $post->subject !!}" data-message="{{ $post->message }}" name="button" ><i class="fa fa-edit" aria-hidden="true"></i></button>
                                            <button  data-toggle="modal" data-target="#modal1" data-id="{{ $post->id }}" type="button" class="btn btn-xs deletebtn" name="button" ><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="thismessage">{!! $post->message !!}</div>
                                    </div>

                                    @if($post->files != null)
                                    @foreach(json_decode($post->files,true) as $asd)
                                    <img src="{{ asset('assets/images/message_board/'.$asd)}}" alt="Smiley face" height="100" width="100" style="object-fit: cover;">
                                    @endforeach
                                    @endif
                                    <div class="col-md-12">
                                        <h5 class="text-right">Posted by: <b>{{ $post->user->fname.' '.$post->user->lname }}</b></h5>
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
            <!-- EDIT MODAL  -->
            <div class="modal fade" id="modal2">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('app.messages.update',1) }}" method="post" enctype="multipart/form-data">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"> Edit Post </h4>
                            </div>
                            <div class="modal-body">
                                {{csrf_field()}}
                                {{ method_field('PUT')}}
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
                                                        <input type="text" id="subjectInputModal" class="form-control" name="subject" value="">
                                                        <br>
                                                        <input type="hidden" id="idInputModal" name="id">
                                                        <textarea id="messageInputModal" name="message" class="summernotee"></textarea>
                                                        <br>
                                                        <input type="file" name="img[]" multiple >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-sm" name="button"></i>Update Post</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Delete MODAL  -->
            <!-- MODAL  -->
            <div class="modal fade" id="modal1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"> Delete Post</h4>
                        </div>
                        <div class="modal-body">
                            <form class="" action="{{route('app.delete-post')}}" method="post">
                                {{csrf_field()}}
                                <div class="container-fluid half-padding">
                                    <div class="template template_texteditor">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-danger">

                                                    <div class="panel-body">
                                                        <h4>Are you sure you want to delete this post?</h4>
                                                        <input type="hidden" id="deleteId" name="id">

                                                        <br>
                                                        <div class="row">
                                                            <div class="col-md-6 text-left">
                                                                <button class="btn btn-danger " type="submit" name="button">Yes</button>
                                                            </div>
                                                                <div class="col-md-6 text-right ">
                                                                <button  data-dismiss="modal"class="btn btn-success deletebtn"  name="button">No</button>
                                                            </div>
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
            <!-- Pin Post MODAL  -->
            <!-- MODAL  -->
            <div class="modal fade" id="modal3">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Pin Post</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('app.messages.update',1)}}" method="post">
                                {{csrf_field()}}
                                {{ method_field('PUT') }}
                                <div class="container-fluid half-padding">
                                    <div class="template template_texteditor">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-danger">

                                                    <div class="panel-body">
                                                        <h4>Are you sure you want to pin this post?</h4>
                                                        <input type="hidden" name="pin" value="1">
                                                        <input type="hidden" name="id" id="pinnedId">
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-md-6 text-left">
                                                                <button class="btn btn-danger" type="submit" name="button">Yes</button>
                                                            </div>
                                                            <div class="col-md-6 text-right">

                                                                <button  data-dismiss="modal"class="btn btn-success" type="button" name="button">No</button>
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
    <script type="text/javascript">
        $(document).ready(function(e) {

            $('.summernotee').summernote({
                placeholder:'Write a text here...',
                height: 150,
                toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'color']],
                ['font', ['fontname']],
                ['para',['ul','ol', 'listStyles','paragraph']],
                ['height',['height']],
                ['table', ['table']],
                ['insert', ['link', 'video'/*, 'hr', 'doc', 'readmore', 'lorem', 'emoji'*/]],
                ['view', ['codeview', 'fullscreen', 'findnreplace']],
                ['help',['help']]
                ],
            });

            $('.editbtn').each(function () {
                var $this = $(this);
                $this.on('click',function(){
                    $("#subjectInputModal").val($(this).data("subject"));
                    $('#messageInputModal').summernote('code', $(this).data("message"));
                    $('#idInputModal').val($(this).data("id"));
                });
            });

            $('.deletebtn').each(function () {
                var $this = $(this);
                $this.on('click',function(){
                    $('#deleteId').val($(this).data("id"));
                });
            });

            $('.pinnedbtn').each(function () {
                var $this = $(this);
                $this.on('click',function(){
                    $('#pinnedId').val($(this).data("id"));
                });
            });



        });

    // function editModal(subject, message){
    //     alert('asd');
    // }
</script>


@endsection
