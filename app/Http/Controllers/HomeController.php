<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
class HomeController
{
    public $data = __DIR__ . '/../../../resources/data/data.js';

    public function getData(){
        $res = false;
        $strContentData = file_get_contents($this->data);
        if($strContentData and strlen($strContentData) > 0){
            $res = json_decode($strContentData);
        }else{
            $res = $this->setData($this->callApi('https://jsonplaceholder.typicode.com/posts'));
        }
        return $res;
    }

    public function setData($data){
        if(is_array($data)){
            $data = json_encode($data);
        }
        $file = fopen($this->data,'wb');
        fwrite($file,$data);
        fclose($file);
        return json_decode($data);
    }

    public function updateData($post,$id){
        $data = $this->getData();
        if($id == 0){
            array_unshift($data,$post);
        }else{
            $n = false;
            foreach ($data as $i=>$row){
                if($row->id == $post->id){
                    $n = $i;
                }
            }
            $data[$n] = $post;
        }
        return $this->setData($data);
    }

    public function removeData($id){
        $data = $this->getData();
        $n = false;
        foreach ($data as $i=>$row){
            if($row->id == $id){
                $n = $i;
            }
        }
        array_splice($data, $n, 1);
        return $this->setData($data);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function homePage($post_id = 0): View
    {
        $data = $this->getData();
        $posts = [];
        $post = false;
        //$url = 'https://jsonplaceholder.typicode.com/posts' . (($post_id > 0) ? ('/' . $post_id) : '');
        if($post_id > 0){
            //$post = $this->callApi($url);
            foreach ($data as $row){
                if($row->id == $post_id){
                    $post = $row;
                }
            }
        }else{
            $posts = $data;
        }

        return view('home', ['posts' => $posts,'post' => $post]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function editPost($id): View {
        $data = $this->getData();
        $post = false;
        foreach ($data as $row){
            if($row->id == $id){
                $post = $row;
            }
        }
        //$post = ($id == 0) ? false : $this->callApi('https://jsonplaceholder.typicode.com/posts/' . $id);
        //var_dump($post);die;
        return view('edit-post', ['post' => $post]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function savePost(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required|min:10',
            'id' => 'required',
            'userId' => 'required',
        ]);
        $data = $request->all();

        $addText = $data['id']==0?'create':'edit';
        $postData = ['title'=>$data['title'],'body'=>$data['body'],'userId'=>$data['userId']];
        if($data['id']==0){
            $res = $this->callApi('https://jsonplaceholder.typicode.com/posts','POST',$postData);
            //var_dump($res);die;
        }else{
            $postData['id'] = $data['id'];
            $this->callApi('https://jsonplaceholder.typicode.com/posts/'.$data['id'],'PUT',$postData);
            $res = (object)$postData;
        }
        $this->updateData($res,$data['id']);
        return redirect("/")->withSuccess('Great! You have Successfully '.$addText.' post');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function deletePost($id): RedirectResponse {
        if((int)$id > 0){
            $data = $this->getData();
            foreach ($data as $row){
                if($row->id == $id){
                    $post = $row;
                }
            }
            if($post->userId == Auth::id()){
                $this->callApi('https://jsonplaceholder.typicode.com/posts/' . $id,"DELETE");
                $this->removeData($id);
            }
        }
        return redirect("/")->withSuccess('Post was successfully deleted');
    }

    public function callApi($url = 'https://jsonplaceholder.typicode.com/posts',$method = "GET",$post = []){
        $ch = curl_init();
        $data = json_encode($post);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            var_dump($error_msg);die;
        }
        //var_dump($response);die;
        $result = json_decode($response);
        curl_close($ch);
        return $result;
    }
}
