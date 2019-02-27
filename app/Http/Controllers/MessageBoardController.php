<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessageBoard;
use App\Clusters;
use App\Teams;

use Validator;
use Session;
use File;
use Carbon\Carbon;

class MessageBoardController extends Controller
{
    /**
    * THIS IS FOR ADMINI ROLE TO GET ALL MESSAGES
    *
    * @return \Illuminate\Http\Response
    */
    public function index($errors = null)
    {
        $msgboard_tbl = new MessageBoard();
        // get cluster id of the accounts owner
        $get_cluster = Clusters::get(['id','team_ids'])->map(function($cl){

            // if account owner is team leader
            if(!empty(Session::get('_t'))){
                // check its cluster
                if(in_array(Session::get('_t')[0]['id'],$cl['team_ids'])){
                    $cl['cl_id'] = $cl['id'];
                }
            }
            // if account owner
            if(!empty(Session::get('_a'))){
                // check its cluster
                if(in_array(Session::get('_a')[0]['id'],$cl['team_ids'])){
                    $cl['cl_id'] = $cl['id'];
                }
            }
            return $cl;
        });
        // get team id
        if(!empty(Session::get('_c'))){
            if(!empty(Session::get('_c')[0]['team_ids'])){
                // get team id
                $team_id = json_encode(Session::get('_c')[0]['team_ids']);
            }
        }elseif(!empty(Session::get('_t'))){
            // if cluster is empty
            $team_id = json_encode([Session::get('_t')[0]['id']]);
            // find team cluster
        }elseif(!empty(Session::get('_a'))){
            $team_id = json_encode([Session::get('_a')[0]['id']]);
        }else{
            $team_id = json_encode([null]);
        }

        Session::put('tl_id',$team_id);

        // check if has cluster if none set value to null
        $check_cl_id = !empty(array_values($get_cluster->pluck('cl_id')->filter()->toArray()))? array_values($get_cluster->pluck('cl_id')->filter()->toArray())[0]:null;
        // get cluster id if owner is cluster head, if not set to $check_cl_id
        $cl_id = !empty(Session::get('_c'))? Session::get('_c')[0]['id'] : $check_cl_id;

        // get allt he meassages
        $msg = $msgboard_tbl->get(['team_id','cluster_id','id'])->map(function($mess) use($msgboard_tbl,$cl_id){
            // check if this message belong to the owner cluster
            if(!empty(Session::get('_c')) ){
                if(in_array(Session::get('_c')[0]['id'],json_decode($mess['cluster_id'],true))){

                    $mess['c_ids_msg'] = $mess['id'];
                }
            }
            // check if this message belong to the owner cluster
            if(!empty(Session::get('_t')) ){
                // if has cluster and team
                if(in_array(Session::get('_t')[0]['id'],json_decode($mess['team_id'],true)) && in_array($cl_id,json_decode($mess['cluster_id'],true))){

                    $mess['t_ids_msg'] = $mess['id'];
                }else{
                    // if has team only
                    if(in_array(Session::get('_t')[0]['id'],json_decode($mess['team_id'],true))){
                        $mess['t_ids_msg'] = $mess['id'];
                    }
                }
            }
            // check if this message belong to the owner cluster
            if(!empty(Session::get('_a')) ){

                if(in_array(Session::get('_a')[0]['id'],json_decode($mess['team_id'],true))){

                    $mess['a_ids_msg'] = $mess['id'];
                }
            }
            if(in_array(null,json_decode($mess['team_id'],true)) && in_array(null,json_decode($mess['cluster_id'],true))){
                $mess['ad_ids_msg'] = $mess['id'];
            }
            return $mess;
        });
        // get all the id of the messages
        $c_id = $msg->pluck('c_ids_msg')->filter()->toArray();
        $t_id = $msg->pluck('t_ids_msg')->filter()->toArray();
        $a_id = $msg->pluck('a_ids_msg')->filter()->toArray();
        $ad_id = $msg->pluck('ad_ids_msg')->filter()->toArray();
        // merge array into 1 array
        $msg_ids = array_unique(array_merge($c_id,$t_id,$a_id,$ad_id));
        // put cl id to session
        Session::put('cl_id',$cl_id);
        // get all messages
        if(base64_decode(Auth()->user()->role) == 'administrator'){
            $all_post = MessageBoard::whereNotIn('pinned',[1])->orderBy('created_at','desc')->with(['user'])->paginate(10);
            $pinned = MessageBoard::where('pinned',1)->with(['user'])->get();
        }else{
            $all_post = MessageBoard::whereIn('id',$msg_ids)->whereNotIn('pinned',[1])->orderBy('created_at','desc')->with(['user'])->paginate(10);
            $pinned = MessageBoard::whereIn('id',$msg_ids)->where('pinned',1)->with(['user'])->get();
        }

        // return messages for this owner
        return view('app.message_board.message_board',[
            'messages' => $all_post,
            'pinned' => $pinned,
            ])->withErrors($errors);
        }

        /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
        public function create()
        {
            //
        }

        /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
        public function store(Request $request)
        {
            // for validation
            $validator = Validator::make($request->all(),[
                'message' => 'required',
                'subject' => 'required',
            ]);

            $image_validex = ['png','jpeg','jpg'];


            if(!$validator->fails()){
                $msgboard_tbl = new MessageBoard();
                if(!empty($request->pinned)){
                    $msgboard_tbl->where(['posted_by'=> Auth()->user()->id,'pinned' => 1])->update(['pinned'=>0]);
                }
                $dom = new \DomDocument();
                $dom->loadHtml($request->message, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                // *** FOR IMAGES ***
                if(!empty($request->file('img'))){
                    foreach($request->file('img') as $image)
                    {
                        $name=uniqid().'-'.Carbon::today()->format('Y-m-d').'.';
                        $extension = $image->getClientOriginalExtension();
                        if(in_array($extension,$image_validex)){
                            $image->move('assets/images/message_board',$name.$extension);
                        }else{
                            return $this->index()->with('image_error', 'Image is not valid!');
                        }

                        $data[] = $name.$extension;
                    }
                }
                $files = !empty($data)? json_encode($data): null;

                // *** END FOR IMAGES ***

                $detail = $dom->saveHTML();
                $msgboard_tbl->create([
                    'cluster_id' =>json_encode([Session::get('cl_id')]),
                    'team_id' => Session::get('tl_id'),
                    'subject' => $request->subject,
                    'message' => $detail,
                    'posted_by' => Auth()->user()->id,
                    'pinned' => (!empty($request->pinned)) ? $request->pinned : 0,
                    'files' => $files,

                ]);
                return back();
            }else{
                // $request->session()->with('errors', $validator->errors());
                return $this->index($validator->errors());
            }
        }

        /**
        * Display the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function show(Request $request, $id)
        {
            //
            // MessageBoard::where($request[])
        }

        /**
        * Show the form for editing the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function edit($id)
        {
            //
        }

        /**
        * UPDATE MSG BOARD || CAN ALSO BE USED FOR CHANGE PINNED POST
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function update(Request $request)
        {
            $msgboard_tbl = new MessageBoard();

            if(!empty($request->post('pin'))){
                // clicked pin button

                $msgboard_tbl->where(['posted_by'=> Auth()->user()->id,'pinned' => 1])->update(['pinned' => 0]);
                $msgboard_tbl->where('id',$request->post('id')  )->update(['pinned' => 1]);

                return $this->index();

            }else{
                // Clicked update message button
                $validator = Validator::make($request->except('_token'),[
                    'message' => 'required',
                ]);

                if(!$validator->fails()){
                    $msg = $msgboard_tbl->where('id', $request->post('id'))->first();

                    $image_validex = ['png','jpeg','jpg'];
                    $data = [];
                    if(!empty($request->file('img'))){


                        foreach($request->file('img') as $image)
                        {
                            $name=uniqid().'-'.Carbon::today()->format('Y-m-d').'.';
                            $extension = $image->getClientOriginalExtension();
                            if(in_array($extension,$image_validex)){
                                $image->move('assets/images/message_board',$name.$extension);
                            }else{
                                return $this->index()->with('image_error', 'Image is not valid!');
                            }

                            $data[] = $name.$extension;
                        }
                        $files = json_encode($data);

                    }else{
                        $data = $files['files'];
                    }

                    $msgboard_tbl->where('id', $request->post('id'))->update([
                        'subject' => $request->subject,
                        'message' => $request->message,
                        'files' => $files,
                    ]);

                    // return 'success update message';
                    return $this->index();
                }else{
                    return $this->index($validator->errors());
                }
            }
        }

        /**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function destroy($id)
        {

        }
        // delete specific post
        public function delete(Request $request){
            //find id first
            $find_id = MessageBoard::findOrFail($request->post('id'));
            if($find_id){
                // delete post
                $image= MessageBoard::where('id',$request->post('id'))->first();
                if($image['files'] != null){
                    foreach(json_decode($image['files'],true) as $file)
                    {
                        // delete photo from storage
                        File::delete('assets/images/message_board/'.$file);
                    }
                }
                $image->delete();

                // return to index
                return redirect()->route('app.messages.store');
            }
        }
    }
