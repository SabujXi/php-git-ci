<?php


namespace Controller;
use \Symfony\Component\HttpFoundation\Request;
use Framework\BaseController;


class DataManager extends BaseController
{
    public function upload(Request $request){
        if($request->getMethod() === 'POST'){
            $file = $request->files->get('file');
            $fn = $file->getClientOriginalName();
            $file->move(DATA_DIR, $fn);
            $this->app->start_session()->getFlashBag()->add('messages', "Uploaded file: $fn");
            return $this->app->redirect('upload_data');
        }else{
            $messages = $this->app->start_session()->getFlashBag()->get('messages');
            $file_names = glob(DATA_DIR . '/{,.}*', GLOB_BRACE);
            return $this->app->templates->render('data_upload.html', ['messages'=>$messages, 'file_names' => $file_names]);
        }
    }
}
