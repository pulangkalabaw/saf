<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessageBoard;

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

        if( !empty(Session::get('_c')) ){
            $data['allposts'] = $msgboard_tbl->where(['cluster_id' => Session::get('_c')[0]['id']])->whereNotIn('pinned',[1])->orderBy('created_at','desc')->with(['user'])->paginate(10);
            $data['pinned'] = $msgboard_tbl->where(['cluster_id' => Session::get('_c')[0]['id']])->where('pinned',1)->with(['user'])->first();
            $data['role'] = 'CL';
        }elseif(!empty(Session::get('_t'))){
            $data['allposts'] = $msgboard_tbl->where(['team_id' => Session::get('_c')[0]['id']])->whereNotIn('pinned',[1])->orderBy('created_at','desc')->with(['user'])->paginate(10);
            $data['pinned'] = $msgboard_tbl->where(['team_id' => Session::get('_c')[0]['id']])->where('pinned',1)->with(['user'])->first();
            $data['role'] = 'TL';
        }else{
            $data['allposts'] = $msgboard_tbl->whereNotIn('pinned',[1])->orderBy('created_at','desc')->with(['user'])->paginate(10);
            $data['pinned'] = $msgboard_tbl->where('pinned',1)->with(['user'])->first();
            $data['role'] = 'A';
        }
        return view('app.message_board.message_board',[
            'messages' => $data['allposts'],
            'pinned' => $data['pinned'],
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
        // return $request->all();
        $validator = Validator::make($request->all(),[
            'message' => 'required',
            'subject' => 'required',
        ]);

        if(!$validator->fails()){
            $msgboard_tbl = new MessageBoard();
            if(!empty($request->pinned)){
                $msgboard_tbl->where('pinned',1)->update(['pinned'=>0]);
            }
            $dom = new \DomDocument();
            $dom->loadHtml($request->message, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            // *** FOR IMAGES ***
            if(!empty($request->file('img'))){

                foreach($request->file('img') as $image)
                {
                    $name=uniqid().'-'.Carbon::today()->format('Y-m-d').'.';
                    $extension = $image->getClientOriginalExtension();
                    $image->move('assets/images/message_board',$name.$extension);

                    $data[] = $name.$extension;
                }
            }
            $files = !empty($data)? json_encode($data): null;

            // $images = $dom->getElementsByTagName('img');
            // foreach($images as $k => $img){

            //     $data = $img->getAttribute('src');

            //     list($type, $data) = explode(';', $data);

            //     list(, $data)      = explode(',', $data);

            //     $data = base64_decode($data);

            //     $image_name= "/assets/message_board/" . time().$k.'.png';

            //     $path = public_path() . $image_name;

            //     file_put_contents($path, $data);

            //     $img->removeAttribute('src');

            //     $img->setAttribute('src', $image_name);

            // }

            // *** END FOR IMAGES ***

            $detail = $dom->saveHTML();
            $msgboard_tbl->create([
                'cluster_id' => (!empty(Session::get('_c'))) ? Session::get('_c')[0]['id'] : null,
                'team_id' => (!empty(Session::get('_t'))) ? json_encode(Session::get('_t')) : null,
                'subject' => $request->subject,
                'message' => $detail,
                'posted_by' => Auth()->user()->id,
                'pinned' => (!empty($request->pinned)) ? $request->pinned : 0,
                'files' => $files,

            ]);
            return $this->index();
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

            $msgboard_tbl->where('pinned', 1)->update(['pinned' => 0]);
            $msgboard_tbl->where('id',$request->post('id'))->update(['pinned' => 1]);

            return $this->index();

        }else{
            // Clicked update message button
            $validator = Validator::make($request->except('_token'),[
                'message' => 'required',
            ]);

            if(!$validator->fails()){
                if(!empty($request->file('img'))){

                    foreach($request->file('img') as $image)
                    {
                        $name = $image->getClientOriginalName();
                        $image->move('assets/images/message_board',$name);

                        $data[] = $name;
                    }
                    $files = json_encode($data);

                }else{
                    $files = $msgboard_tbl->where('id', $request->post('id'))->first();
                    $files = $files['files'];
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
            return $this->index();
        }
    }
}
