<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServerController extends Controller
{
    public function index()
    {
        return view('admin.servers.index');
    }

    public function get(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = 6;

        $servers = Server::orderBy('id', 'desc')->skip($offset)->take($limit)->get();

        $html = '';
        foreach ($servers as $server) {
            $html .= view('admin.servers.server_html', compact('server'))->render();
        }

        return response()->json([
            'html' => $html,
            'hasMore' => $servers->count() === $limit, // Check if more records are available
        ]);
    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $rules = [
            'portal_name' => 'required|string|max:255|unique:servers,name,' . $request->server_id . ',id,deleted_at,NULL',
            'login_url' => 'required',
            'username' => 'required',
        ];

        if (!$request->server_id) {
            $rules['server_password'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $result = ['status' => false, 'error' => $validator->errors(), 'data' => ''];
            return response()->json($result);
        }

        $server = $request->server_id ? Server::find($request->server_id) : new Server();
        if ($request->server_id && !$server) {
            return response()->json(['status' => false, 'message' => 'Server nicht gefunden', 'data' => []], 404);
        }

        $server->name = $request->input('portal_name');
        $server->login_url = $request->input('login_url');
        $server->username = $request->input('username');

        if ($request->input('server_password')){
            $server->password = $request->input('server_password');
        }

        if ($server->save()) {
            $message = $request->server_id ? 'Server erfolgreich aktualisiert.' : 'Server erfolgreich hinzugefügt.';
            $isNew = $request->server_id ? false : true;
            return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew,  'server' => $server]);
        }

        // Handle failure in saving data
        return response()->json(['status' => false, 'message' => 'Fehler beim Speichern der Daten', 'data' => []], 500);
    }

    public function detail(Request $request)
    {
        $server = Server::find($request->id);
        if (!$server) {
            return response()->json(['status' => false, 'message' => 'Server nicht gefunden', 'data' => []], 404);
        }
        return response()->json($server);
    }   
}
