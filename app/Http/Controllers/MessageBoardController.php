<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessageBoard;

use Validator;
use Session;
use File;

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
        // $data = $msgboard_tbl->where(['cluster_id' => Session::get('_c')[0]])->where('team_id', 'like', '%'.Session::get('_t')[0].'%')->with(['user'])->get();

        if( !empty(Session::get('_c')[0]) ){
            $data['allposts'] = $msgboard_tbl->where(['cluster_id' => Session::get('_c')[0]])->whereNotIn('pinned',[1])->orderBy('created_at','desc')->with(['user'])->paginate(10);
            $data['pinned'] = $msgboard_tbl->where('pinned',1)->with(['user'])->first();
        }else{
            $data['allposts'] = $msgboard_tbl->whereNotIn('pinned',[1])->orderBy('created_at','desc')->with(['user'])->paginate(10);
            $data['pinned'] = $msgboard_tbl->where('pinned',1)->with(['user'])->first();
        }


        // dd(Session::get('_t'));
        return view('app.message_board.message_board', [
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
        //
        // return $request->all();
        $data = [];
        if(!empty($request->file('img'))){
            $image = $request->file('img');
            $name=$image->getClientOriginalName();
                $image->move(public_path('assets/images/message_board'),$image);
                dd(file_exists(public_path('/images/message_board')));

                // dd($image->move(public_path('/images/message_board'),$name));


            // foreach($request->file('img') as $image)
            // {
            //     $name=$image->getClientOriginalName();
            //     $image->move(public_path().'/images/message_board', $name);
            //
            //     $data[] = $name;
            //
            // }

        }else{

            dd('asp');
        }
        dd('asd');
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
                'cluster_id' => (!empty(Session::get('_c'))) ? Session::get('_c')[0] : null,
                'team_id' => (!empty(Session::get('_t'))) ? json_encode(Session::get('_t')) : null,
                'subject' => $request->subject,
                'message' => $detail,
                'posted_by' => Auth()->user()->id,
                'pinned' => (!empty($request->pinned)) ? $request->pinned : 0,


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

                $msgboard_tbl->where('id', $request->post('id'))->update([
                    'subject' => $request->subject,
                    'message' => $request->message,
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
            MessageBoard::where('id',$request->post('id'))->delete();
            // return to index
            return $this->index();
        }
    }
}
